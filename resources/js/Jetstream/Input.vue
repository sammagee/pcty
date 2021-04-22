<template>
    <input class="transition border-gray-300 rounded-md shadow-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-300 focus:ring-offset-2 focus:ring-offset-white" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input">
</template>

<script>
    export default {
        props: ['modelValue'],

        emits: ['update:modelValue'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>

