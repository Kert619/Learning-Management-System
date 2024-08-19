<template>
  <q-layout view="hHh lpR fFf">
    <q-header class="bg-primary text-grey-1 q-py-xs" height-hint="58">
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          @click="toggleLeftDrawer"
          aria-label="Menu"
          icon="menu"
        />

        <q-btn flat no-caps no-wrap class="q-ml-xs" v-if="$q.screen.gt.xs">
          <q-icon name="school" color="white" size="28px" />
          <q-toolbar-title shrink class="text-weight-bold">
            E-Learn App
          </q-toolbar-title>
        </q-btn>

        <q-space />

        <div class="q-gutter-sm row items-center no-wrap">
          <q-btn round dense flat color="grey-1" icon="message">
            <q-tooltip>Messages</q-tooltip>
          </q-btn>
          <q-btn round dense flat color="grey-1" icon="notifications">
            <q-badge color="red" text-color="white" floating> 2 </q-badge>
            <q-tooltip>Notifications</q-tooltip>
          </q-btn>
          <q-btn round flat>
            <q-avatar size="26px">
              <img src="https://cdn.quasar.dev/img/boy-avatar.png" />
            </q-avatar>
            <q-menu>
              <q-list style="min-width: 100px" dense>
                <q-item clickable>
                  <q-item-section avatar>
                    <q-toggle
                      v-model="$q.dark.isActive"
                      icon="alarm"
                      size="sm"
                      label="Theme"
                      left-label
                      checked-icon="mdi-brightness-3"
                      unchecked-icon="mdi-brightness-5"
                    />
                  </q-item-section>
                </q-item>
                <q-item clickable v-close-popup>
                  <q-item-section>Profile</q-item-section>
                </q-item>
                <q-item clickable v-close-popup>
                  <q-item-section>Change Password</q-item-section>
                </q-item>
                <q-separator />
                <q-item clickable v-close-popup @click="handleLogout">
                  <q-item-section>Logout</q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered :width="240">
      <q-scroll-area class="fit">
        <q-list padding>
          <q-item
            v-for="menu in menus"
            :key="menu.text"
            :to="menu.link"
            v-ripple
            clickable
            :focused="route.path === menu.link"
          >
            <q-item-section avatar>
              <q-icon :name="menu.icon" />
            </q-item-section>

            <q-item-section>
              <q-item-label>{{ menu.text }}</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { useQuasar } from 'quasar';
import { useAuthStore } from 'src/stores/auth';
import { ref, watch } from 'vue';
import { useRoute } from 'vue-router';

const $q = useQuasar();
const route = useRoute();
const authStore = useAuthStore();
const leftDrawerOpen = ref(false);

const toggleLeftDrawer = () => {
  leftDrawerOpen.value = !leftDrawerOpen.value;
};

const menus: { icon: string; text: string; link: string }[] = [
  { icon: 'calendar_month', text: 'School Year', link: '/admin/school-year' },
  { icon: 'library_books', text: 'Courses', link: '' },
  { icon: 'group', text: 'Instructors', link: '' },
  { icon: 'cable', text: 'Course Assignment', link: '' },
];

const handleLogout = async () => {
  authStore.logout().then(() => {
    window.location.reload();
  });
};

watch(
  () => $q.dark.isActive,
  (newVal) => {
    $q.dark.set(newVal);
    $q.localStorage.set('color_scheme', newVal ? 'true' : 'false');
  }
);
</script>
