<template>
    <div v-if="links.length > 3">
        <div class="flex items-center justify-between md:justify-center">
            <template v-for="(link, key) in links" :key="key">
                <div :class="{'hidden md:block': key !== 0 && key !== links.length - 1}">
                    <div v-if="!link.url" class="inline-flex items-center justify-center h-10 min-w-[2.5rem] mb-1 mr-1 text-sm leading-4 text-gray-300 rounded-lg" v-html="link.label" />
                    <inertia-link v-else class="inline-flex items-center justify-center h-10 min-w-[2.5rem] mb-1 mr-1 text-sm leading-4 rounded-lg text-gray-500 hover:bg-white focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-orange-300" :class="[link.active && 'shadow bg-white']" :href="link.url" v-html="link.label" />
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['links'],
    }
</script>
