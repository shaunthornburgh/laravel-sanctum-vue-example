<template>
    <div>Welcome back {{ userStore.user.name }}</div>
</template>

<script setup>
import {useUserStore} from "../../Store/user.js";

const userStore = useUserStore()
</script>
