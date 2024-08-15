import { RouteRecordRaw } from 'vue-router';
import { auth } from 'src/middlewares/auth';
import { guest } from 'src/middlewares/guest';
import { role } from 'src/middlewares/role';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('layouts/LoginLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Auth/LoginPage.vue') },
    ],
    beforeEnter: [guest],
  },

  {
    path: '/admin',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/IndexPage.vue') }],
    beforeEnter: [auth, role],
    meta: {
      role: 'admin',
    },
  },
  {
    path: '/user',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/IndexPage.vue') }],
    beforeEnter: [auth, role],
    meta: {
      role: 'user',
    },
  },
  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
];

export default routes;
