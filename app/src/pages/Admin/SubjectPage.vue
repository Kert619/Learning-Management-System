<template>
  <q-page>
    <SubjectIndex />
  </q-page>
</template>

<script setup lang="ts">
import SubjectIndex from 'components/Admin/Subject/SubjectIndex.vue';
import { Loading } from 'quasar';
import { useSubjectStore } from 'src/stores/subject';

defineOptions({
  async preFetch({ store }) {
    Loading.show();
    const subjectStore = useSubjectStore(store);
    await subjectStore.fetchIndex();
    Loading.hide();
  },
});
</script>
