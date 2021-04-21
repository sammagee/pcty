<template>
    <button :type="type" class="inline-flex items-center px-6 py-3 text-sm font-semibold tracking-wide text-white uppercase transition bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 focus:ring-offset-white disabled:opacity-25 bg-gradient-to-br from-gray-700 to-gray-900">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: 'submit',
            },
        }
    }
</script>
