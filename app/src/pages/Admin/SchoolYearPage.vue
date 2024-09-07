<template>
  <q-page>
    <SchoolYearIndex />
  </q-page>
</template>

<script setup lang="ts">
import SchoolYearIndex from 'components/Admin/SchoolYear/SchoolYearIndex.vue';
import { Loading } from 'quasar';
import { useSchoolYearStore } from 'src/stores/school-year';

defineOptions({
  async preFetch({ store }) {
    Loading.show();
    const schoolYearStore = useSchoolYearStore(store);
    await schoolYearStore.fetchIndex();
    Loading.hide();
  },
});
</script>
