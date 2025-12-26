<template>
    <div v-if="linksArray.length > 3" class="flex justify-center mt-4">
        <nav aria-label="Pagination" class="inline-flex space-x-1">
            <template v-for="(link, key) in linksArray" :key="key">
                <div v-if="link.url === null"
                     class="border border-gray-300 bg-gray-100 cursor-not-allowed mb-1 px-4 py-2 text-gray-400 text-sm leading-4 rounded">
                    <span v-html="link.label" />
                </div>
                <Link v-else
                      class="border mb-1 px-4 py-2 text-sm leading-4 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors"
                      :class="link.active ? 'bg-gray-500 text-white border-gray-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-300 hover:text-gray-900'"
                      :href="link.url"
                      v-html="link.label" />
            </template>
        </nav>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
    components: {
        Link,
    },
    props: {
        paginator: {
            type: Object,
            default: () => []

        }
    },
    computed: {
        linksArray() {
            return Object.values(this.paginator)
        }
    }
}
</script>
