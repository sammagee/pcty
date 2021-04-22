<template>
    <button :type="type" class="inline-flex items-center px-6 py-3 text-sm font-semibold tracking-wide text-gray-700 uppercase transition bg-white border border-gray-300 rounded-md shadow-sm hover:text-gray-500 focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-300 focus:ring-offset-2 focus:ring-offset-white active:text-gray-800 active:bg-gray-50 disabled:opacity-25">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: 'button',
            },
        }
    }
</script>
