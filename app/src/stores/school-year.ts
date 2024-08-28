import { defineStore } from 'pinia';
import { ref, Ref } from 'vue';
import { api } from 'boot/axios';
import { Notify } from 'quasar';

export type SchoolYear = {
  id?: number;
  school_year: string;
  status: SchoolYearStatus;
  $guid?: string;
};

export type SchoolYearStatus = 'open' | 'close';

type SchoolYearError = {
  id: number;
  school_year: string;
  status: string;
};

export const useSchoolYearStore = defineStore('school-year', () => {
  const index: Ref<SchoolYear[]> = ref([]);
  const created: Ref<Map<string, SchoolYear>> = ref(new Map());
  const createdErrors: Ref<Map<string, SchoolYearError>> = ref(new Map());

  const fetchIndex = async (force = false) => {
    if (index.value.length === 0 || force) {
      return api
        .get('school-years')
        .then((response) => {
          index.value = response.data;
          return response;
        })
        .catch((error) => {
          Notify.create({
            message: error.response.data.message,
            type: 'negative',
          });

          throw error;
        });
    }
  };

  const create = (prefill: Partial<SchoolYear> = {}) => {
    const guid = crypto.randomUUID();

    created.value.set(guid, {
      school_year: '',
      status: 'close',
      ...prefill,
      $guid: guid,
    });

    return guid;
  };

  const store = async (id: string) => {
    const schoolYear = created.value.get(id);
    if (!schoolYear) return;

    return api
      .post('school-years', schoolYear)
      .then((response) => {
        Notify.create({
          message: 'New school year has been added',
          type: 'positive',
        });

        return response;
      })
      .catch((error) => {
        Notify.create({
          message: error.response.data.message,
          type: 'negative',
        });

        throw error;
      });
  };

  const update = async (id: number) => {
    const schoolYear = index.value.find((schoolYear) => schoolYear.id == id);
    if (!schoolYear) return;

    return api
      .put(`school-years/${id}`, schoolYear)
      .then((response) => {
        Notify.create({
          message: 'School year has been updated',
          type: 'positive',
        });

        return response;
      })
      .catch((error) => {
        Notify.create({
          message: error.response.data.message,
          type: 'negative',
        });

        throw error;
      });
  };

  const destroy = async (id: number) => {
    return api
      .delete(`school-years/${id}`)
      .then((response) => {
        Notify.create({
          message: 'School year has been removed',
          type: 'positive',
        });

        return response;
      })
      .catch((error) => {
        Notify.create({
          message: error.response.data.message,
          type: 'negative',
        });

        throw error;
      });
  };

  return {
    index,
    created,
    createdErrors,
    fetchIndex,
    create,
    store,
    update,
    destroy,
  };
});
