<template>
    <jet-dialog-modal :show="show" @close="$emit('close')">
        <template #title>
            {{ title }} Employee
        </template>

        <template #content>
            <jet-input type="text" class="block w-full mt-1" placeholder="Employee Name"
                        ref="name"
                        v-model="form.name" />

            <jet-input-error :message="form.errors.name" class="mt-2" />

            <hr class="my-6" />

            <div>
                <div v-if="form.dependents.length" v-for="(dependent, index) in form.dependents" :key="index" class="flex items-center mb-2 space-x-3">
                    <div class="flex-1">
                        <jet-input type="text" class="w-full" placeholder="Dependent Name"
                            v-model="dependent.name" />

                        <jet-input-error :message="form.errors.name" class="mt-2" />
                    </div>

                    <div>
                        <select v-model="dependent.relation" class="transition border-gray-300 rounded-md shadow-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:ring-offset-white">
                            <option value="" selected disabled>Relation</option>
                            <option value="spouse">Spouse</option>
                            <option value="child">Child</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <button class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300" @click="form.dependents.splice(index, 1)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <jet-button @click="addDependent" :class="{ 'mt-2': form.dependents.length, 'opacity-25': form.processing }" :disabled="form.processing">
                    Add Dependent
                </jet-button>
            </div>
        </template>

        <template #footer>
            <jet-secondary-button class="focus:ring-offset-gray-100" @click="$emit('close')">
                Close
            </jet-secondary-button>

            <jet-button class="ml-2 focus:ring-offset-gray-100" @click="addOrUpdateEmployee" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ title }}
            </jet-button>
        </template>
    </jet-dialog-modal>
</template>

<script>
    import JetButton from '@/Jetstream/Button'
    import JetDialogModal from '@/Jetstream/DialogModal'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import { useForm } from '@inertiajs/inertia-vue3'
    import { ref, watchEffect } from '@vue/runtime-core'

    export default {
        props: ['employee', 'show'],
        emits: ['close'],

        components: {
            JetButton,
            JetDialogModal,
            JetInput,
            JetInputError,
            JetSecondaryButton,
        },

        setup(props) {
            const action = ref('Add')
            const title = ref('Add')
            const path = ref(route('employee.store'))
            const name = ref(null)

            const form = useForm({
                name: props.employee?.name || null,
                dependents: props.employee?.dependents || [],
            })

            const addOrUpdateEmployee = () => form[action.value](path.value, {
                preserveState: false,
                onSuccess: () => form.reset(),
            })

            const addDependent = () => form.dependents.push({ name: '', relation: '' })

            watchEffect(() => props.show && setTimeout(() => name.value?.focus(), 100))
            watchEffect(() => {
                action.value = props.employee ? 'patch' : 'post'
                title.value = props.employee ? 'Update' : 'Add'
                path.value = props.employee ? route('employee.update', props.employee.id) : route('employee.store')
                form.name = props.employee?.name || null
                form.dependents = props.employee?.dependents || []
            })

            return { path, form, name, addOrUpdateEmployee, addDependent, title }
        },
    }
</script>
