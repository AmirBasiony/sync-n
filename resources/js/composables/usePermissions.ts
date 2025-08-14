import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const user = computed(() => page.props.auth.user)

export function usePermissions() {

    const permissions = computed(() => user.value?.permissions_list || [])

    function hasPermission(permission: any) {
        return permissions.value.includes(permission)
    }

    return { hasPermission }
}