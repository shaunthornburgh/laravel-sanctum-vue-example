import {ref} from 'vue';

export default function useValidationErrorHandling() {
    const errors = ref(null)

    function errorFor(field) {
        return errors.value?.[field] ?? [];
    }

    return {
        errors,
        errorFor
    }
}
