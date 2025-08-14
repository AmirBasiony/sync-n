import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

export function useConfirmDelete() {
    const confirmDelete = (routeName: string, typeId: string, options = {}) => {
        const isDark = document.documentElement.classList.contains('dark');

        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            background: isDark ? '#1f2937' : '#fff',
            color: isDark ? '#f9fafb' : '#000',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(route(routeName, typeId), {
                    preserveScroll: true,
                    ...options,
                    onSuccess: () => {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Item has been deleted.',
                            icon: 'success',
                            background: isDark ? '#1f2937' : '#ffffff',
                            color: isDark ? '#f9fafb' : '#000000',
                            confirmButtonColor: '#3085d6',
                        });

                        options.onSuccess?.();
                    },
                    onError: () => {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                        options.onError?.();
                    },
                });
            }
        });
    };

    return { confirmDelete };
}
