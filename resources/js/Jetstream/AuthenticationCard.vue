<template>
    <div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0">
        <div class="w-full sm:max-w-md">
            <header class="px-6">
                <slot name="header" />
            </header>

            <div class="w-full px-6 py-4 mt-4 overflow-hidden bg-white shadow-md sm:rounded-lg">
                <slot />
            </div>

            <footer class="px-6">
                <slot name="footer" />
            </footer>
        </div>
    </div>
</template>
