<template>
  <q-tr>
    <q-td auto-width>
      <q-chip icon="mdi-identifier" size="xs" color="primary" label="New" />
    </q-td>
    <q-td>
      <q-input v-model="schoolYearRef.school_year" dense borderless />
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
        @click="emit('deleted', schoolYearRef.$guid as string)"
      />
    </q-td>
  </q-tr>
</template>

<script setup lang="ts">
import { SchoolYear, SchoolYearStatus } from 'src/stores/school-year';
import { computed, toRef } from 'vue';

const emit = defineEmits<{
  saved: [id: string];
  deleted: [id: string];
  toggleStatus: [id: string, value: SchoolYearStatus];
}>();

const props = defineProps<{
  schoolYear: SchoolYear;
}>();

const schoolYearRef = toRef(props.schoolYear);

const disableSave = computed(() => {
  return !schoolYearRef.value.school_year.trim();
});

const onToggleStatus = (value: SchoolYearStatus) => {
  emit('toggleStatus', schoolYearRef.value.$guid as string, value);
};

const onSaved = () => {
  if (disableSave.value) return;
  emit('saved', schoolYearRef.value.$guid as string);
};
</script>
