import { useAuthStore } from 'src/stores/auth';
import { NavigationGuardNext, RouteLocationNormalized } from 'vue-router';

export const role = async (
  to: RouteLocationNormalized,
  _from: RouteLocationNormalized,
  next: NavigationGuardNext
) => {
  const authStore = useAuthStore();
  if (!authStore.user) return next('/');
  if (!to.meta.role) return next();
  if (authStore.user.role != to.meta.role) return next('/');

  next();
};
