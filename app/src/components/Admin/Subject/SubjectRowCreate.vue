<template>
  <q-tr>
    <q-td auto-width>
      <q-chip icon="mdi-identifier" size="xs" label="New" />
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
        @click="onSaved"
      />
      <q-btn
        flat
        round
        dense
        size="xs"
        icon="mdi-delete"
        color="negative"
        @click="emit('deleted', subjectRef.$guid as string)"
      />
    </q-td>
  </q-tr>
</template>

<script setup lang="ts">
import { SubjectObject } from 'src/stores/subject';
import { toRef, watch } from 'vue';

const emit = defineEmits<{
  saved: [id: string];
  deleted: [id: string];
}>();

const props = defineProps<{
  subject: SubjectObject;
}>();

const subjectRef = toRef(props.subject);

const onSaved = () => {
  emit('saved', subjectRef.value.$guid as string);
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
