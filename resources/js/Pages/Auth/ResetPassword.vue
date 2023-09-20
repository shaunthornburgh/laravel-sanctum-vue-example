<template>
    <Header page-title="Enter Your New Password" />

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <success v-if="state.successAlert">{{ state.successAlert }}</success>
        <form class="space-y-6" action="#" method="POST">
            <div>
                <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                <div class="mt-2">
                    <input
                        v-model="formData.password"
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
                <validation-errors :errors="errorFor('password')" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                <div class="mt-2">
                    <input
                        v-model="formData.password_confirmation"
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
                <validation-errors :errors="errorFor('password_confirmation')" />
            </div>

            <div>
                <button
                    :disabled="state.loading"
                    @click.prevent="resetPassword"
                    type="submit"
                    class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >Reset Password</button>
            </div>
        </form>

        <p class="mt-10 text-center text-sm text-gray-500">
            Back to
            <router-link
                :to="{ name: 'auth.login' }"
                class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500"
            >login</router-link>
        </p>
    </div>
</template>

<script setup>
import Header from "../../Components/Auth/Header.vue";
import {reactive} from "vue";
import axios from "axios";
import { useRoute } from 'vue-router'
import useValidationErrorHandling from "../../Shared/Composable/useValidationErrorHandling.js";
import useGetErrorResponseStatus from "../../Shared/Composable/useGetErrorResponseStatus.js";
import ValidationErrors from "../../Components/Form/ValidationErrors.vue";
import Success from "../../Components/Alert/Success.vue";

const route = useRoute()

const { errors, errorFor } = useValidationErrorHandling();
const { is422, is500 } = useGetErrorResponseStatus();

const state = reactive ({
    loading: false,
    successAlert: null,
});

const formData = reactive({
    password: "",
    password_confirmation: ""
});

const resetPassword = async () => {
    state.errors = [];
    state.loading = true;
    state.successAlert = null;

    axios
        .post("/api/auth/reset-password", {
            password: formData.password,
            password_confirmation: formData.password_confirmation,
            email: route.query.email,
            token: route.query.token,
        })
        .then((response) => {
            state.successAlert = response.data.message;
        })
        .catch(error => {
            if (is422(error)) {
                errors.value = error.response.data.errors;
            } else if (is500(error)) {
                state.warningAlert = error.response.data.message;
            }
        })
    state.loading = false;
}
</script>
