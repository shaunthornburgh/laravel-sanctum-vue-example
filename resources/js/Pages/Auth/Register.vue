<template>
    <Header page-title="Create an Account" />

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <success v-if="state.successAlert">{{ state.successAlert }}</success>
        <form class="space-y-6" action="#" method="POST">
            <div>
                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                <div class="mt-2">
                    <input
                        v-model="formData.name"
                        id="name"
                        name="name"
                        type="text"
                        autocomplete="name"
                        required
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
                <validation-errors :errors="errorFor('name')" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                <div class="mt-2">
                    <input
                        v-model="formData.email"
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
                <validation-errors :errors="errorFor('email')" />
            </div>

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
                    @click.prevent="register"
                    type="submit"
                    class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >Register</button>
            </div>
        </form>

        <p class="mt-10 text-center text-sm text-gray-500">
            Already a member?
            <router-link
                :to="{ name: 'auth.login' }"
                class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500"
            >Login</router-link>
        </p>
    </div>
</template>

<script setup>
import Header from "../../Components/Auth/Header.vue";
import {reactive} from "vue";
import axios from "axios";
import Success from "../../Components/Alert/Success.vue";
import useValidationErrorHandling from "../../Shared/Composable/useValidationErrorHandling.js";
import ValidationErrors from "../../Components/Form/ValidationErrors.vue";

const { errors, errorFor } = useValidationErrorHandling();

const state = reactive ({
    loading: false,
    successAlert: ''
})

const formData = reactive({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
})

const register = async () => {
    errors.value = [];
    state.loading = true;
    state.successAlert = null;
    axios
        .post("/api/auth/register", {
            name: formData.name,
            email: formData.email,
            password: formData.password,
            password_confirmation: formData.password_confirmation,
        })
        .then(() => {
            state.successAlert = 'Registration successful, please log in';
        })
        .catch(error => {
            errors.value = error.response.data.errors;
        })
    state.loading = false;
}
</script>
