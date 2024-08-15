import { useAuthStore } from 'src/stores/auth';
import { NavigationGuardNext, RouteLocationNormalized } from 'vue-router';
import ErrorNotFound from 'pages/ErrorNotFound.vue';

export const role = async (
  to: RouteLocationNormalized,
  _from: RouteLocationNormalized,
  next: NavigationGuardNext
) => {
  const authStore = useAuthStore();

  if (!authStore.user) return next({ path: '/' });
  if (!to.meta.role) return next();

  if (authStore.user.role != to.meta.role) {
    const lastMatched = to.matched[to.matched.length - 1];

    if (lastMatched && lastMatched.components) {
      lastMatched.components.default = ErrorNotFound;
    }
  }

  next();
};
