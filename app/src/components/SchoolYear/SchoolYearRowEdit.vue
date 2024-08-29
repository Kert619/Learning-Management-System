<template>
  <q-tr>
    <q-td auto-width>
      <q-chip
        icon="mdi-identifier"
        size="xs"
        color="primary"
        :label="schoolYearRef.id"
      />
    </q-td>
    <q-td>
      <q-input
        v-model="schoolYearRef.school_year"
        dense
        borderless
        :error="schoolYearRef.errors?.school_year !== undefined"
        :error-message="schoolYearRef.errors?.school_year?.toString()"
      />
    </q-td>
    <q-td>
      <q-toggle
        :model-value="schoolYearRef.status"
        true-value="open"
        false-value="close"
        size="xs"
        dense
        :label="schoolYearRef.status === 'open' ? 'Open' : 'Close'"
        left-label
        @update:model-value="onToggleStatus"
      />
    </q-td>
    <q-td auto-width>
      <q-btn
        flat
        round
        dense
        size="xs"
        icon="mdi-content-save"
        color="positive"
        :disable="disableSave"
        @click="onSaved"
      />
      <q-btn
        flat
        round
        dense
        size="xs"
        icon="mdi-delete"
        color="negative"
        @click="emit('deleted', schoolYearRef.id as number)"
      />
    </q-td>
  </q-tr>
</template>

<script setup lang="ts">
import { SchoolYear, SchoolYearStatus } from 'src/stores/school-year';
import { computed, toRef } from 'vue';

const emit = defineEmits<{
  saved: [id: number];
  deleted: [id: number];
  toggleStatus: [id: number, value: SchoolYearStatus];
}>();

const props = defineProps<{
  schoolYear: SchoolYear;
}>();

const schoolYearRef = toRef(props.schoolYear);
const original: SchoolYear = JSON.parse(JSON.stringify(props.schoolYear));

const isDirty = computed(() => {
  return JSON.stringify(schoolYearRef.value) !== JSON.stringify(original);
});

const disableSave = computed(() => {
  return !schoolYearRef.value.school_year.trim() || !isDirty.value;
});

const onToggleStatus = (value: SchoolYearStatus) => {
  emit('toggleStatus', schoolYearRef.value.id as number, value);
};

const onSaved = () => {
  if (disableSave.value) return;
  emit('saved', schoolYearRef.value.id as number);
};
</script>
