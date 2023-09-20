<template>
    <div>
        <span class="flex items-center font-medium tracking-wide text-red-500 text-xs mt-1 ml-1"
              v-for="(error, index) in props.errors"
              :key="key(index)"
        >{{ error }}</span>
    </div>
</template>

<script setup>
    const props = defineProps(['errors']);

    const key = (index) => {
        return `validation_error_${index}_${Math.random()}`;
    }
</script>
