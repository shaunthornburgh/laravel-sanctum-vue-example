import { defineStore } from 'pinia'
import { useStorage } from "@vueuse/core";

export const useUserStore = defineStore({
    id: 'user',
    state: () => ({
        user: useStorage('user', {
            name: null,
            email: null
        })
    }),
    actions: {
        setUserDetails(response) {
            this.user.id = response.data.user.id
            this.user.name = response.data.user.name
            this.user.email = response.data.user.email
        },

        clearUser() {
            this.user.id = null
            this.user.name = null
            this.user.email = null
        }
    },
    persist: true
});
