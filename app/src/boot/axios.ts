import { boot } from 'quasar/wrappers';
import axios, { AxiosInstance } from 'axios';
import { Cookies } from 'quasar';

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $axios: AxiosInstance;
    $api: AxiosInstance;
  }
}

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)
const api = axios.create({
  baseURL: process.env.API_URL + '/api',
  withCredentials: true,
});
const web = axios.create({ baseURL: process.env.API_URL });

export default boot(({ app, ssrContext }) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api

  app.config.globalProperties.$axios = axios;
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api;
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API

  //attach cookies during SSR
  if (process.env.SERVER) {
    let cookies = (
      process.env.SERVER ? Cookies.parseSSR(ssrContext) : Cookies
    ).getAll();

    cookies = Object.entries(cookies)
      .map(([key, value]) => `${key}=${value}`)
      .join('; ');

    api.defaults.headers.common['Cookie'] = cookies;

    api.defaults.headers.common['Origin'] = process.env.APP_URL;
  }
});

export { api, web };
