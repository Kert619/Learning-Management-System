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
        <SubjectRowCreate
          v-for="created in createRows"
          :key="created.$guid?.toString()"
          :subject="created"
          @deleted="subjectStore.created.delete(created.$guid ?? '')"
          @saved="handleSavedCreate"
        />
      </template>

      <template #body="props">
        <SubjectRowEdit
          v-if="!refresh"
          :subject="props.row"
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
import SubjectRowCreate from 'components/Admin/Subject/SubjectRowCreate.vue';
import SubjectRowEdit from 'components/Admin/Subject/SubjectRowEdit.vue';
import { QTableColumn, useQuasar } from 'quasar';
import { computed, nextTick, onMounted, ref } from 'vue';
import { useSubjectStore } from 'src/stores/subject';

const $q = useQuasar();
const subjectStore = useSubjectStore();
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
    name: 'subject_code',
    label: 'Subject Code',
    field: 'subject_code',
    align: 'left',
    sortable: true,
  },
  {
    name: 'subject_title',
    label: 'Subject Title',
    field: 'subject_title',
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
  subjectStore.created = new Map();
});

const indexRows = computed(() => {
  return subjectStore.index;
});

const createRows = computed(() => {
  if (!(subjectStore.created instanceof Map)) return [];
  return Array.from(subjectStore.created.values());
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
  subjectStore.create();
};

const handleSavedCreate = async (id: string) => {
  $q.loading.show();
  subjectStore
    .store(id)
    .then(async () => {
      subjectStore.created.delete(id);
      await subjectStore.fetchIndex(true);
      await refreshRow();
    })
    .finally(() => $q.loading.hide());
};

const handleSavedEdit = async (id: number) => {
  $q.loading.show();
  subjectStore
    .update(id)
    .then(async () => {
      await subjectStore.fetchIndex(true);
      await refreshRow();
    })
    .finally(() => $q.loading.hide());
};

const handleDeleted = async (id: number) => {
  $q.dialog({
    title: 'Confirm',
    message: 'Do you want to remove this subject?',
    cancel: true,
  }).onOk(() => {
    $q.loading.show();
    subjectStore
      .destroy(id)
      .then(async () => {
        await subjectStore.fetchIndex(true);
        await refreshRow();
      })
      .finally(() => $q.loading.hide());
  });
};
</script>
