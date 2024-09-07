import { defineStore } from 'pinia';
import { Ref, ref } from 'vue';
import { api, web } from 'boot/axios';
import { Notify } from 'quasar';
import { Role } from 'src/enums/role';

export type LoginCredential = {
  email: string;
  password: string;
};

export type User = {
  id: number;
  email: string;
  role: Role;
};

export const useAuthStore = defineStore('user', () => {
  const user: Ref<User | null> = ref(null);

  const login = async (credential: LoginCredential) => {
    await web.get('/sanctum/csrf-cookie');

    return web
      .post('login', credential)
      .then((response) => {
        return response;
      })
      .catch((error) => {
        Notify.create({
          message: error.response.data?.message,
          type: 'negative',
        });

        throw error;
      });
  };

  const logout = async () => {
    return web
      .post('logout')
      .then(() => {
        window.location.reload();
      })
      .catch((error) => {
        Notify.create({
          message: error.response.data?.message,
          type: 'negative',
        });

        throw error;
      });
  };

  const getUser = async () => {
    return api
      .get('users/user')
      .then((response) => {
        user.value = response.data;
        return response;
      })
      .catch((error) => {
        return error;
      });
  };

  return { user, login, logout, getUser };
});
