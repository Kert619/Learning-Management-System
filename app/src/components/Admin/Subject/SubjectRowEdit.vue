<template>
  <q-tr>
    <q-td auto-width>
      <q-chip icon="mdi-identifier" size="xs" :label="subjectRef.id" />
    </q-td>
    <q-td>
      <q-input
        v-model="subjectRef.subject_code"
        dense
        borderless
        :error="subjectRef.errors?.subject_code !== undefined"
        :error-message="subjectRef.errors?.subject_code?.toString()"
      />
    </q-td>
    <q-td>
      <q-input
        v-model="subjectRef.subject_title"
        dense
        borderless
        :error="subjectRef.errors?.subject_title !== undefined"
        :error-message="subjectRef.errors?.subject_title?.toString()"
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
        :disable="!isDirty"
        @click="onSaved"
      />
      <q-btn
        flat
        round
        dense
        size="xs"
        icon="mdi-delete"
        color="negative"
        @click="emit('deleted', subjectRef.id as number)"
      />
    </q-td>
  </q-tr>
</template>

<script setup lang="ts">
import { SubjectObject } from 'src/stores/subject';
import { computed, toRef, watch } from 'vue';

const emit = defineEmits<{
  saved: [id: number];
  deleted: [id: number];
}>();

const props = defineProps<{
  subject: SubjectObject;
}>();

const subjectRef = toRef(props.subject);
const original: SubjectObject = JSON.parse(JSON.stringify(props.subject));

const isDirty = computed(() => {
  return JSON.stringify(subjectRef.value) !== JSON.stringify(original);
});

const onSaved = () => {
  if (!isDirty.value) return;

  emit('saved', subjectRef.value.id as number);
};

watch(
  () => subjectRef.value.subject_code,
  (newVal) => {
    subjectRef.value.subject_code = newVal.toUpperCase();
  }
);

watch(
  () => subjectRef.value.subject_title,
  (newVal) => {
    subjectRef.value.subject_title = newVal.toUpperCase();
  }
);
</script>
