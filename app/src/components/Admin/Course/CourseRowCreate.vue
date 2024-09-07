<template>
  <q-tr>
    <q-td auto-width>
      <q-chip icon="mdi-identifier" size="xs" color="primary" label="New" />
    </q-td>
    <q-td>
      <q-input
        v-model="courseRef.course_code"
        dense
        borderless
        :error="courseRef.errors?.course_code !== undefined"
        :error-message="courseRef.errors?.course_code?.toString()"
      />
    </q-td>
    <q-td>
      <q-input
        v-model="courseRef.course_title"
        dense
        borderless
        :error="courseRef.errors?.course_title !== undefined"
        :error-message="courseRef.errors?.course_title?.toString()"
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
        @click="emit('deleted', courseRef.$guid as string)"
      />
    </q-td>
  </q-tr>
</template>

<script setup lang="ts">
import { CourseObject } from 'src/stores/course';
import { toRef, watch } from 'vue';

const emit = defineEmits<{
  saved: [id: string];
  deleted: [id: string];
}>();

const props = defineProps<{
  course: CourseObject;
}>();

const courseRef = toRef(props.course);

const onSaved = () => {
  emit('saved', courseRef.value.$guid as string);
};

watch(
  () => courseRef.value.course_code,
  (newVal) => {
    courseRef.value.course_code = newVal.toUpperCase();
  }
);

watch(
  () => courseRef.value.course_title,
  (newVal) => {
    courseRef.value.course_title = newVal.toUpperCase();
  }
);
</script>
