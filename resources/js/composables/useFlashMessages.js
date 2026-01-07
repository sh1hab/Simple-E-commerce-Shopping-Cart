import { usePage } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import { watch } from 'vue';

export function useFlashMessages() {
    const page = usePage();
    const toast = useToast();

    // Watch for flash messages from Laravel
    watch(
        () => page.props.flash,
        (flash) => {
            if (flash?.success) {
                toast.success(flash.success);
            }
            if (flash?.error) {
                toast.error(flash.error);
            }
            if (flash?.warning) {
                toast.warning(flash.warning);
            }
            if (flash?.info) {
                toast.info(flash.info);
            }
        },
        { deep: true, immediate: true }
    );

    return {
        toast,
        success: (message) => toast.success(message),
        error: (message) => toast.error(message),
        warning: (message) => toast.warning(message),
        info: (message) => toast.info(message),
    };
}
