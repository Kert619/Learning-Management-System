<template>
  <router-view />
</template>

<script setup lang="ts">
import { LocalStorage, useQuasar } from 'quasar';
import { onMounted } from 'vue';

onMounted(() => {
  const $q = useQuasar();
  $q.dark.set(LocalStorage.getItem('color_scheme') === 'true' ? true : false);
});
</script>
