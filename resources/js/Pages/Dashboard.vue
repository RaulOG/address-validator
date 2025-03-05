<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3'

defineProps({ errors: Object })

const form = useForm({
    file: null,
    mappings: null,
})

function submit() {
    form.post('/user/upload')
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div>
                                <label for="file">Select CSV file to upload</label>
                                <input id="file" type="file" @change="form.file = $event.target.files[0]" accept=".csv" required />
                                <div v-if="form.errors.file">{{ form.errors.file }}</div>
                            </div>

                            <div>
                                <label for="mappings">Mappings</label>
                                <input type="text" v-model="form.mappings" required />
                                <div v-if="form.errors.mappings">{{ form.errors.mappings }}</div>
                            </div>

                            <button type="submit">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
