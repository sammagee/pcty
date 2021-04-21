<template>
    <div v-if="links.length > 3">
        <div class="flex items-center justify-center">
            <template v-for="(link, key) in links" :key="key">
                <div v-if="!link.url" class="inline-flex items-center justify-center h-10 min-w-[2.5rem] mb-1 mr-1 text-sm leading-4 text-gray-300 rounded-lg" v-html="link.label" />
                <inertia-link v-else class="inline-flex items-center justify-center h-10 min-w-[2.5rem] mb-1 mr-1 text-sm leading-4 rounded-lg text-gray-500 hover:bg-white focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-300" :class="[link.active && 'shadow bg-white']" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['links'],
    }
</script>
