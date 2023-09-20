    <template>
        <Header page-title="Sign in to your account" />

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
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        />
                    </div>
                    <validation-errors :errors="errorFor('email')" />
                </div>

            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    <div class="text-sm">
                        <router-link
                            :to="{ name: 'auth.forgot-password' }"
                            class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500"
                        >Forgot password?</router-link>
                    </div>
                </div>
                <div class="mt-2">
                    <input
                        v-model="formData.password"
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                </div>
                <validation-errors :errors="errorFor('password')" />
            </div>

            <div>
                <button
                    type="submit"
                    class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    :disabled="state.loading"
                    @click.prevent="login"
                >Sign in</button>
            </div>
        </form>

        <p class="mt-10 text-center text-sm text-gray-500">
            Not a member?
            <router-link
                :to="{ name: 'auth.register' }"
                class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500"
            >Register</router-link>
        </p>
    </div>
</template>

<script setup>
import Header from "../../Components/Auth/Header.vue";
import { reactive } from 'vue';
import axios from "axios";
import router from "../../Router";
import { useUserStore } from '../../store/user'
import useValidationErrorHandling from "../../Shared/Composable/useValidationErrorHandling.js";
import ValidationErrors from "../../Components/Form/ValidationErrors.vue";

const { errors, errorFor } = useValidationErrorHandling();

const state = reactive ({
    loading: false
});

const userStore = useUserStore()

const formData = reactive({
    email: "",
    password: ""
});

const login = async () => {
    state.loading = true;

    await axios.get('/sanctum/csrf-cookie')

    axios
        .post("/api/auth/login", {
            email: formData.email,
            password: formData.password
        })
        .then((response) => {
            userStore.setUserDetails(response)

            router.push({ name: 'app.dashboard' })
        })
        .catch(error => {
            errors.value = error.response.data.errors;
        })
    state.loading = false;
}
</script>
