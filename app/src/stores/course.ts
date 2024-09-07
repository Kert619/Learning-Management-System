import { defineStore } from 'pinia';
import { Ref, ref } from 'vue';
import { api } from 'boot/axios';
import { Notify } from 'quasar';
import { AxiosError } from 'axios';
import errorMessages from 'src/error-messages';

export type CourseObject = {
  id?: number;
  course_code: string;
  course_title: string;
  $guid?: string;
  errors?: CourseError;
};

type CourseError = {
  course_code?: string;
  course_title?: string;
};

type ValidationErrorResponse = {
  errors: CourseError;
  message: string;
};

export const useCourseStore = defineStore('course', () => {
  const index: Ref<CourseObject[]> = ref([]);
  const created: Ref<Map<string, CourseObject>> = ref(new Map());

  const fetchIndex = async (force = false) => {
    if (index.value.length === 0 || force) {
      return api
        .get('courses')
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

  const create = (prefill: Partial<CourseObject> = {}) => {
    const guid = crypto.randomUUID();

    created.value.set(guid, {
      course_code: '',
      course_title: '',
      ...prefill,
      $guid: guid,
    });

    return guid;
  };

  const store = async (id: string) => {
    const course = created.value.get(id);
    if (!course) return;

    return api
      .post('courses', course)
      .then((response) => {
        Notify.create({
          message: 'New course has been added',
          type: 'positive',
        });

        delete course.errors;

        return response;
      })
      .catch((error: AxiosError<ValidationErrorResponse>) => {
        if (error.response?.status === 422) {
          course.errors = error.response?.data?.errors;
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
    const course = index.value.find((course) => course.id == id);
    if (!course) return;

    return api
      .put(`courses/${id}`, course)
      .then((response) => {
        Notify.create({
          message: 'Course has been updated',
          type: 'positive',
        });

        delete course.errors;

        return response;
      })
      .catch((error: AxiosError<ValidationErrorResponse>) => {
        if (error.response?.status === 422) {
          course.errors = error.response?.data.errors;
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
      .delete(`courses/${id}`)
      .then((response) => {
        Notify.create({
          message: 'Course has been removed',
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
