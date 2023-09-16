<template>
    <Header page-title="Reset Your Password" />

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-6" action="#" method="POST">
            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                <div class="mt-2">
                    <input
                        v-model="formData.email"
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                </div>
            </div>

            <div>
                <button
                    :disabled="state.loading"
                    @click.prevent="forgotPassword"
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

const state = reactive ({
    loading: false
});

const formData = reactive({
    email: ""
});

const forgotPassword = async () => {
    state.errors = [];
    state.loading = true;
    axios
        .post("/api/auth/forgot-password", {
            email: formData.email,
        })
        .then((response) => {
            // show notification
        })
        .catch(error => {
            state.loading = false;
        })
}
</script>
