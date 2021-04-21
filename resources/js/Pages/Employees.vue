<template>
    <app-layout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Employees
                </h2>

                <jet-button @click="showingEmployeeModal = !showingEmployeeModal">Add</jet-button>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid items-end grid-cols-2 gap-6 px-4 lg:grid-cols-4 sm:px-0">
                    <div class="relative flex items-center col-span-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute w-4 h-4 -mt-px text-gray-400 left-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>

                        <input class="w-full h-16 pr-16 placeholder-gray-400 transition border border-white rounded-lg shadow focus:border-blue-400 pl-14 focus:ring-2 focus:ring-offset-gray-100 focus:ring-offset-2 focus:outline-none focus:ring-blue-300" type="text" placeholder="Search" ref="searchInput" :value="query" @input="[query = $event.target.value, search()]" />

                        <span class="absolute inline-flex items-center justify-center w-6 h-6 text-xs text-gray-500 border border-gray-100 rounded right-6 bg-gray-50">/</span>
                    </div>

                    <div class="flex flex-col justify-center h-16 px-6 bg-white rounded-lg shadow md:items-center md:space-x-3 md:justify-between md:flex-row">
                        <span class="text-xs font-semibold tracking-wide text-black uppercase">
                            Average<br />
                        </span>
                        <h3 class="relative text-xl font-semibold tracking-wide text-black truncate top-0.5 lg:text-2xl">
                            ${{ (average / 100).toFixed(2) }}<span class="text-[0.5rem]">/yr</span>
                        </h3>
                    </div>

                    <div class="flex flex-col justify-center h-16 px-6 rounded-lg shadow md:items-center md:space-x-3 md:justify-between md:flex-row bg-gradient-to-br from-gray-700 to-gray-900">
                        <span class="text-xs font-semibold tracking-wide text-white uppercase">
                            Total<br />
                        </span>
                        <h3 class="text-xl font-semibold tracking-wide text-white truncate top-0.5 relative lg:text-2xl">
                            ${{ (total / 100).toFixed(2) }}<span class="text-[0.5rem]">/yr</span>
                        </h3>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden bg-white shadow sm:rounded-lg">
                    <div v-if="employees.data.length">
                        <div class="grid grid-cols-4 px-6 space-x-2 border-b-2 border-gray-100 md:grid-cols-5">
                            <div class="py-4 pl-[3.25rem] text-xs font-bold tracking-wide text-gray-500 uppercase">Name</div>
                            <div class="py-4 text-xs font-bold tracking-wide text-gray-500 uppercase">Benefit Cost</div>
                            <div class="hidden py-4 text-xs font-bold tracking-wide text-gray-500 uppercase md:block">Dependents</div>
                        </div>

                        <div class="divide-y divide-gray-100">
                            <div class="grid items-center grid-cols-4 px-6 py-4 space-x-2 md:grid-cols-5" v-for="employee in employees.data" :key="employee.id">
                                <div class="flex items-center space-x-3">
                                    <img class="object-cover w-10 h-10 rounded-full" :src="employee.profile_photo_url" :alt="employee.name" />

                                    <span class="font-semibold break-words">{{ employee.name }}</span>
                                </div>

                                <div class="col-span-2 md:col-span-1">${{ (employee.benefit_cost / 100).toFixed(2) }}</div>

                                <div class="hidden md:block md:col-span-2">{{ employee.dependents.length }}</div>

                                <div class="flex items-center justify-end space-x-3">
                                    <button class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300" @click="[selectedEmployee = employee, showingEmployeeModal = true]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    <button class="text-red-500 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-300" @click="deleteEmployee(employee)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="flex items-center justify-center p-8">
                        <p class="text-sm">No Employees</p>
                    </div>
                </div>

                <pagination class="mt-6" :links="employees.links" />
            </div>
        </div>

        <employee-modal :employee="selectedEmployee" :show="showingEmployeeModal" @close="[showingEmployeeModal = false, selectedEmployee = null]" />
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import EmployeeModal from '@/Shared/EmployeeModal'
    import JetButton from '@/Jetstream/Button'
    import Pagination from '@/Shared/Pagination'
    import { ref } from '@vue/reactivity'
    import { Inertia } from '@inertiajs/inertia'
    import { onMounted, onUnmounted } from '@vue/runtime-core'
    import hotkeys from 'hotkeys-js'
    import throttle from 'lodash/throttle'

    export default {
        props: ['employees', 'average', 'total', 'search'],

        components: {
            AppLayout,
            EmployeeModal,
            JetButton,
            Pagination,
        },

        setup(props) {
            const query = ref(props.search)
            const searchInput = ref(null)
            const showingEmployeeModal = ref(false)
            const selectedEmployee = ref(null)

            const deleteEmployee = employee => {
                if (confirm('Are you sure you want to delete this employee?')) Inertia.delete(route('employee.destroy', employee.id))
            }

            const search = throttle(() => {
                Inertia.get(
                    route('employee.index', query.value ? { query: query.value } : {}),
                    {},
                    { preserveState: true }
                )
            }, 150);

            onMounted(() => {
                hotkeys('/', event => {
                    searchInput.value?.focus()
                    event.preventDefault()
                })
            })

            onUnmounted(() => {
                hotkeys.unbind('/')
            })

            return { query, search, searchInput, showingEmployeeModal, deleteEmployee, selectedEmployee }
        }
    }
</script>
