<template>
    <div v-if="linksArray.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in linksArray" :key="key">
                <div v-if="link.url === null" class="border border-orange-200 mb-1 mr-1 px-4 py-3 text-gray-400 text-sm leading-4  rounded" v-html="link.label" />
                <Link v-else class="border border-orange-300  mb-1 mr-1 px-4 py-3 focus:text-gray-700 text-sm leading-4 hover:bg-orange-300  focus:border-orange-500 rounded"
                      :class="{ 'bg-white': link.active }"
                      :href="link.url"
                      v-html="link.label" />
            </template>
        </div>
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
