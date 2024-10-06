import { defineStore } from 'pinia';
import { Ref, ref } from 'vue';
import { api } from 'boot/axios';
import { Notify } from 'quasar';
import { AxiosError } from 'axios';
import errorMessages from 'src/error-messages';

export type SubjectObject = {
  id?: number;
  subject_code: string;
  subject_title: string;
  $guid?: string;
  errors?: SubjectError;
};

type SubjectError = {
  subject_code?: string;
  subject_title?: string;
};

type ValidationErrorResponse = {
  errors: SubjectError;
  message: string;
};

export const useSubjectStore = defineStore('subject', () => {
  const index: Ref<SubjectObject[]> = ref([]);
  const created: Ref<Map<string, SubjectObject>> = ref(new Map());

  const fetchIndex = async (force = false) => {
    if (index.value.length === 0 || force) {
      return api
        .get('subjects')
        .then((response) => {
          index.value = response.data;
          return response;
        })
        .catch((error) => {
          Notify.create({
            message: errorMessages[500],
            type: 'negative',
          });

          throw error;
        });
    }
  };

  const create = (prefill: Partial<SubjectObject> = {}) => {
    const guid = crypto.randomUUID();

    created.value.set(guid, {
      subject_code: '',
      subject_title: '',
      ...prefill,
      $guid: guid,
    });

    return guid;
  };

  const store = async (id: string) => {
    const subject = created.value.get(id);
    if (!subject) return;

    return api
      .post('subjects', subject)
      .then((response) => {
        Notify.create({
          message: 'New subject has been added',
          type: 'positive',
        });

        delete subject.errors;

        return response;
      })
      .catch((error: AxiosError<ValidationErrorResponse>) => {
        if (error.response?.status === 422) {
          subject.errors = error.response?.data?.errors;
        } else {
          Notify.create({
            message: errorMessages[500],
            type: 'negative',
          });
        }

        throw error;
      });
  };

  const update = async (id: number) => {
    const subject = index.value.find((subject) => subject.id == id);
    if (!subject) return;

    return api
      .put(`subjects/${id}`, subject)
      .then((response) => {
        Notify.create({
          message: 'Subject has been updated',
          type: 'positive',
        });

        delete subject.errors;

        return response;
      })
      .catch((error: AxiosError<ValidationErrorResponse>) => {
        if (error.response?.status === 422) {
          subject.errors = error.response?.data.errors;
        } else {
          Notify.create({
            message: errorMessages[500],
            type: 'negative',
          });
        }

        throw error;
      });
  };

  const destroy = async (id: number) => {
    return api
      .delete(`subjects/${id}`)
      .then((response) => {
        Notify.create({
          message: 'Subject has been removed',
          type: 'positive',
        });

        return response;
      })
      .catch((error) => {
        Notify.create({
          message: errorMessages[500],
          type: 'negative',
        });

        throw error;
      });
  };

  return { index, created, fetchIndex, create, store, update, destroy };
});
