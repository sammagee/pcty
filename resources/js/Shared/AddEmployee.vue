<template>
    <jet-dialog-modal :show="show" @close="$emit('close')">
        <template #title>
            Add Employee
        </template>

        <template #content>
            <jet-input type="text" class="block w-full mt-1" placeholder="Employee Name"
                        ref="name"
                        v-model="form.name" />

            <jet-input-error :message="form.errors.name" class="mt-2" />

            <hr class="my-6" />

            <div>
                <div v-if="form.dependents.length" v-for="dependent in form.dependents" class="flex items-center mb-2 space-x-3">
                    <div class="flex-1">
                        <jet-input type="text" class="w-full" placeholder="Dependent Name"
                            ref="name"
                            v-model="dependent.name" />

                        <jet-input-error :message="form.errors.name" class="mt-2" />
                    </div>

                    <div>
                        <select v-model="dependent.relation" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="" selected disabled>Relation</option>
                            <option value="spouse">Spouse</option>
                            <option value="child">Child</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <jet-button @click="addDependent" :class="['mt-2', { 'opacity-25': form.processing }]" :disabled="form.processing">
                    Add Dependent
                </jet-button>
            </div>
        </template>

        <template #footer>
            <jet-secondary-button class="focus:ring-offset-gray-100" @click="$emit('close')">
                Close
            </jet-secondary-button>

            <jet-button class="ml-2 focus:ring-offset-gray-100" @click="addEmployee" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Add
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
        props: ['show'],
        emits: ['close'],
        components: {
            JetButton,
            JetDialogModal,
            JetInput,
            JetInputError,
            JetSecondaryButton,
        },
        setup(props) {
            const name = ref(null)

            const form = useForm({
                name: null,
                dependents: [],
            })

            const addEmployee = () => form.post(route('employee.store'), {
                preserveState: false,
                onSuccess: () => form.reset(),
            })

            const addDependent = () => form.dependents.push({name: '', relation: ''})

            watchEffect(() => props.show && setTimeout(() => name.value?.focus(), 100))

            return { form, name, addEmployee, addDependent }
        },
    }
</script>
