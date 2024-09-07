<template>
  <q-page>
    <CourseIndex />
  </q-page>
</template>

<script setup lang="ts">
import CourseIndex from 'components/Admin/Course/CourseIndex.vue';
import { Loading } from 'quasar';
import { useCourseStore } from 'src/stores/course';

defineOptions({
  async preFetch({ store }) {
    Loading.show();
    const courseStore = useCourseStore(store);
    await courseStore.fetchIndex();
    Loading.hide();
  },
});
</script>
