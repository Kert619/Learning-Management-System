<template>
  <q-page padding class="flex flex-center">
    <q-form class="login-form text-center bg-white" @submit="handleSubmit">
      <q-card>
        <q-card-section>
          <div class="text-h6 text-primary">Welcome</div>
          <div class="text-subtitle text-grey-7">
            Sign in below to access your account
          </div>
        </q-card-section>

        <q-card-section class="column q-gutter-md">
          <q-input
            outlined
            v-model="form.email"
            dense
            label="Email"
            :error="(v$.email.$error && !v$.email.$pending) || loginFailed"
            :error-message="v$.email.$errors[0]?.$message.toString()"
            @blur="handleBlur('email')"
            :loading="v$.email.$pending"
          >
            <template #prepend>
              <q-icon
                name="mdi-email-outline"
                :color="
                  (v$.email.$error && !v$.email.$pending) || loginFailed
                    ? 'negative'
                    : ''
                "
              />
            </template>
          </q-input>

          <q-input
            outlined
            v-model="form.password"
            dense
            label="Password"
            type="password"
            :error="v$.password.$error || loginFailed"
            :error-message="v$.password.$errors[0]?.$message.toString()"
            @blur="handleBlur('password')"
          >
            <template #prepend>
              <q-icon
                name="mdi-lock-outline"
                :color="v$.password.$error || loginFailed ? 'negative' : ''"
              />
            </template>
          </q-input>

          <div>
            <span
              class="text-subtitle text-grey-7 cursor-pointer text-italic forgot-password"
              >Forgot Password?</span
            >
          </div>

          <q-btn
            type="submit"
            label="Sign in"
            color="primary"
            unelevated
            :loading="loading"
          />
        </q-card-section>

        <q-separator inset />

        <q-card-section>
          <div class="text-subtitle text-grey-7">
            Don't have an account yet?
            <span class="text-primary cursor-pointer text-weight-bold sign-up"
              >Sign up</span
            >
          </div>
        </q-card-section>
      </q-card>
    </q-form>
  </q-page>
</template>

<script setup lang="ts">
import { computed, Ref, ref } from 'vue';
import { required, email, helpers } from '@vuelidate/validators';
import useVuelidate from '@vuelidate/core';
import { LoginCredential, useAuthStore } from 'src/stores/auth';
import { useRouter } from 'vue-router';

const router = useRouter();
const authStore = useAuthStore();
const loading = ref(false);
const loginFailed = ref(false);

const form: Ref<LoginCredential> = ref({
  email: '',
  password: '',
});

const rules = computed((): { [K in keyof LoginCredential]: object } => {
  return {
    email: {
      required: helpers.withMessage('Email is required', required),
      email: helpers.withMessage('Must be a valid email', email),
      // isUnique: helpers.withMessage(
      //   'This email is already taken',
      //   helpers.withAsync(async () => {
      //     //stop async validation if sync validation fails
      //     if (
      //       v$.value.email['required'].$invalid ||
      //       v$.value.email['email'].$invalid
      //     )
      //       return true;

      //     return new Promise((resolve) => {
      //       setTimeout(() => resolve(false), 5000);
      //     });
      //   })
      // ),
    },

    password: {
      required: helpers.withMessage('Password is required', required),
    },
  };
});

const v$ = useVuelidate(rules, ref({ ...form.value }), { $lazy: true });

const handleBlur = (field: keyof LoginCredential) => {
  v$.value[field].$model = form.value[field];
  v$.value[field].$touch();
};

const handleSubmit = async () => {
  let isFormCorrect = !v$.value.$error;

  if (!v$.value.$dirty) isFormCorrect = await v$.value.$validate();

  if (!isFormCorrect) return;

  loading.value = true;
  loginFailed.value = false;

  authStore
    .login(form.value)
    .then(async () => {
      await authStore.getUser();
      router.replace('/admin');
    })
    .catch(() => (loginFailed.value = true))
    .finally(() => (loading.value = false));
};
</script>

<style scoped lang="scss">
.login-form {
  width: 100%;
  max-width: 400px;
}

.forgot-password:hover {
  text-decoration: underline;
}

.sign-up:hover {
  text-decoration: underline;
}
</style>
