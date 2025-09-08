import { ref, onUnmounted, readonly } from 'vue';

export function Error(duration = 5000) {
    const error = ref('');
    let errorTimeout = null;

    const setError = (message) => {
        error.value = message;
        
        if (errorTimeout) {
            clearTimeout(errorTimeout);
        }
        
        if (message) {
            errorTimeout = setTimeout(() => {
                clearError();
            }, duration);
        }
    };

    const clearError = () => {
        error.value = '';
        if (errorTimeout) {
            clearTimeout(errorTimeout);
            errorTimeout = null;
        }
    };

    onUnmounted(() => {
        if (errorTimeout) {
            clearTimeout(errorTimeout);
        }
    });

    return {
        error: readonly(error),
        setError,
        clearError
    };
}