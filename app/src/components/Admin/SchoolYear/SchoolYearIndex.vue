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
        <SchoolYearRowCreate
          v-for="created in createRows"
          :key="created.$guid?.toString()"
          :school-year="created"
          @deleted="schoolYearStore.created.delete(created.$guid ?? '')"
          @toggle-status="handleToggleStatusCreate"
          @saved="handleSavedCreate"
        />
      </template>

      <template #body="props">
        <SchoolYearRowEdit
          v-if="!refresh"
          :school-year="props.row"
          @deleted="handleDeleted"
          @toggle-status="handleToggleStatusEdit"
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
import SchoolYearRowCreate from 'components/Admin/SchoolYear/SchoolYearRowCreate.vue';
import SchoolYearRowEdit from 'components/Admin/SchoolYear/SchoolYearRowEdit.vue';
import { QTableColumn, useQuasar } from 'quasar';
import { SchoolYearStatus, useSchoolYearStore } from 'src/stores/school-year';
import { computed, nextTick, onMounted, ref } from 'vue';

const $q = useQuasar();
const schoolYearStore = useSchoolYearStore();
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
    name: 'school_year',
    label: 'School Year',
    field: 'school_year',
    align: 'left',
    sortable: true,
  },
  {
    name: 'status',
    label: 'Status',
    field: 'status',
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
  schoolYearStore.created = new Map();
});

const indexRows = computed(() => {
  return schoolYearStore.index;
});

const createRows = computed(() => {
  if (!(schoolYearStore.created instanceof Map)) return [];
  return Array.from(schoolYearStore.created.values());
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
  schoolYearStore.create();
};

/**
 * Only allow 1 school year to be open for create
 */
const handleToggleStatusCreate = (id: string, status: SchoolYearStatus) => {
  const created = schoolYearStore.created.get(id);
  if (!created) return;

  schoolYearStore.created.forEach((schoolYear) => {
    schoolYear.status = 'close';
  });

  created.status = status;
};

/**
 * Only allow 1 school year to be open for edit
 */
const handleToggleStatusEdit = (id: number, status: SchoolYearStatus) => {
  const edit = schoolYearStore.index.find((schoolYear) => schoolYear.id == id);
  if (!edit) return;

  schoolYearStore.index.forEach((schoolYear) => {
    schoolYear.status = 'close';
  });

  edit.status = status;
};

const handleSavedCreate = async (id: string) => {
  $q.loading.show();
  schoolYearStore
    .store(id)
    .then(async () => {
      schoolYearStore.created.delete(id);
      await schoolYearStore.fetchIndex(true);
      await refreshRow();
    })
    .finally(() => $q.loading.hide());
};

const handleSavedEdit = async (id: number) => {
  $q.loading.show();
  schoolYearStore
    .update(id)
    .then(async () => {
      await schoolYearStore.fetchIndex(true);
      await refreshRow();
    })
    .finally(() => $q.loading.hide());
};

const handleDeleted = async (id: number) => {
  $q.dialog({
    title: 'Confirm',
    message: 'Do you want to remove this school year?',
    cancel: true,
  }).onOk(() => {
    $q.loading.show();
    schoolYearStore
      .destroy(id)
      .then(async () => {
        await schoolYearStore.fetchIndex(true);
        await refreshRow();
      })
      .finally(() => $q.loading.hide());
  });
};
</script>
