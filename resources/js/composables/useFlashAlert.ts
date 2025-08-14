import { usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { onMounted } from 'vue';

export function useFlashAlert() {
    const page = usePage();
    const isDark = document.documentElement.classList.contains('dark');

    onMounted(() => {
        const flash = page.props.flash || {};

        if (flash.success) {
            Swal.fire({
                title: 'Success',
                background: isDark ? '#1f2937' : '#fff',
                color: isDark ? '#f9fafb' : '#000',
                text: flash.success,
                icon: 'success',
                timer: 3000,
                showConfirmButton: false,
            });
        }

        if (flash.error) {
            Swal.fire({
                title: 'Error',
                background: isDark ? '#1f2937' : '#fff',
                color: isDark ? '#f9fafb' : '#000',
                text: flash.error,
                icon: 'error',
                timer: 3000,
                showConfirmButton: false,
            });
        }
    });
}
