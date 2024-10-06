<template>
  <q-tr>
    <q-td auto-width>
      <q-chip icon="mdi-identifier" size="xs" :label="courseRef.id" />
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
        @click="emit('deleted', courseRef.id as number)"
      />
    </q-td>
  </q-tr>
</template>

<script setup lang="ts">
import { CourseObject } from 'src/stores/course';
import { computed, toRef, watch } from 'vue';

const emit = defineEmits<{
  saved: [id: number];
  deleted: [id: number];
}>();

const props = defineProps<{
  course: CourseObject;
}>();

const courseRef = toRef(props.course);
const original: CourseObject = JSON.parse(JSON.stringify(props.course));

const isDirty = computed(() => {
  return JSON.stringify(courseRef.value) !== JSON.stringify(original);
});

const onSaved = () => {
  if (!isDirty.value) return;

  emit('saved', courseRef.value.id as number);
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
