<template>
  <div class="q-pa-sm" style="padding-top: 65px">
    <q-table
      :columns="columns"
      :rows="indexRows"
      row-key="id"
      dense
      flat
      bordered
      :rows-per-page-options="[0]"
      :filter="filter"
    >
      <template #top-row>
        <CourseRowCreate
          v-for="created in createRows"
          :key="created.$guid?.toString()"
          :course="created"
          @deleted="courseStore.created.delete(created.$guid ?? '')"
          @saved="handleSavedCreate"
        />
      </template>

      <template #body="props">
        <CourseRowEdit
          v-if="!refresh"
          :course="props.row"
          @deleted="handleDeleted"
          @saved="handleSavedEdit"
          :key="props.row.id"
        />
      </template>
    </q-table>
  </div>

  <!-- place QPageSticky at end of page -->
  <q-page-sticky
    expand
    position="top"
    style="height: 60px"
    :class="{ 'bg-grey-1': !$q.dark.isActive, 'bg-dark': $q.dark.isActive }"
  >
    <q-toolbar class="q-gutter-sm full-height" style="background: inherit">
      <q-input
        v-model="filter"
        dense
        outlined
        label="Search"
        style="width: min(100%, 500px)"
      >
        <template #prepend>
          <q-icon name="mdi-magnify" />
        </template>
      </q-input>
      <q-btn
        label="Add"
        size="md"
        color="primary"
        icon="mdi-plus"
        no-wrap
        @click="handleCreate"
      />
    </q-toolbar>

    <q-separator class="full-width" />
  </q-page-sticky>
</template>

<script setup lang="ts">
import CourseRowCreate from 'components/Admin/Course/CourseRowCreate.vue';
import CourseRowEdit from 'components/Admin/Course/CourseRowEdit.vue';
import { QTableColumn, useQuasar } from 'quasar';
import { computed, nextTick, onMounted, ref } from 'vue';
import { useCourseStore } from 'src/stores/course';

const $q = useQuasar();
const courseStore = useCourseStore();
const filter = ref('');
const refresh = ref(false);

const columns: QTableColumn[] = [
  {
    name: 'id',
    label: '#Id',
    field: 'id',
    align: 'left',
    sortable: true,
  },
  {
    name: 'course_code',
    label: 'Course Code',
    field: 'course_code',
    align: 'left',
    sortable: true,
  },
  {
    name: 'course_title',
    label: 'Course Title',
    field: 'course_title',
    align: 'left',
    sortable: true,
  },
  {
    name: 'action',
    label: '',
    field: '',
  },
];

onMounted(() => {
  courseStore.created = new Map();
});

const indexRows = computed(() => {
  return courseStore.index;
});

const createRows = computed(() => {
  if (!(courseStore.created instanceof Map)) return [];
  return Array.from(courseStore.created.values());
});

/**
 * work around to refresh rows
 */
const refreshRow = async () => {
  refresh.value = true;
  await nextTick();
  refresh.value = false;
};

const handleCreate = () => {
  courseStore.create();
};

const handleSavedCreate = async (id: string) => {
  $q.loading.show();
  courseStore
    .store(id)
    .then(async () => {
      courseStore.created.delete(id);
      await courseStore.fetchIndex(true);
      await refreshRow();
    })
    .finally(() => $q.loading.hide());
};

const handleSavedEdit = async (id: number) => {
  $q.loading.show();
  courseStore
    .update(id)
    .then(async () => {
      await courseStore.fetchIndex(true);
      await refreshRow();
    })
    .finally(() => $q.loading.hide());
};

const handleDeleted = async (id: number) => {
  $q.dialog({
    title: 'Confirm',
    message: 'Do you want to remove this course?',
    cancel: true,
  }).onOk(() => {
    $q.loading.show();
    courseStore
      .destroy(id)
      .then(async () => {
        await courseStore.fetchIndex(true);
        await refreshRow();
      })
      .finally(() => $q.loading.hide());
  });
};
</script>
