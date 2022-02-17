<template>
    <form class="space-y-8 divide-y divide-gray-200" @submit.prevent="onUpdateCategory">
        <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
            <div>
                <div class="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
                    <div
                        class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="category"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                        >Category</label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="max-w-lg flex rounded-md shadow-sm">
                                <input
                                    v-model="form.name"
                                    type="text"
                                    name="username"
                                    id="category"
                                    autocomplete="category"
                                    class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded-none rounded-r-md sm:text-sm border-gray-300"
                                />
                            </div>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="desc"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                        >Description</label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <textarea
                                v-model="form.desc"
                                id="desc"
                                name="about"
                                rows="3"
                                class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md"
                            ></textarea>
                            <p
                                class="mt-2 text-sm text-gray-500"
                            >Write a few sentences about yourself.</p>
                        </div>
                    </div>

                    <div
                        class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5"
                    >
                        <label
                            for="cover_photo"
                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                        >Images</label>

                        <img
                            v-if="category.image_path"
                            :src="'/storage/' + category.image_path"
                            alt
                            class="h-20"
                        />
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div
                                class="max-w-lg flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md"
                            >
                                <div class="space-y-1 text-center">
                                    <svg
                                        class="mx-auto h-12 w-12 text-gray-400"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 48 48"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label
                                            for="file-upload"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500"
                                        >
                                            <span>Upload a file</span>
                                            <input
                                                id="file-upload"
                                                name="file-upload"
                                                type="file"
                                                @change="selectFile"
                                                class="sr-only"
                                            />
                                            <progress
                                                v-if="form.progress"
                                                :value="form.progress.percentage"
                                                max="100"
                                            >{{ form.progress.percentage }}%</progress>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-5">
            <div class="flex justify-end">
                <button
                    type="submit"
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >Save</button>
            </div>
        </div>
        {{ form }}
    </form>
</template>

<script>
import { Inertia } from "@inertiajs/inertia"
import { useForm, usePage } from "@inertiajs/inertia-vue3"
import axios from "axios";
import { reactive, ref } from "vue"
import { useCategories } from '@/Composables/categories'


export default {
    props: {
        category: Object
    },
    setup() {
        const page = usePage();
        const { updateCategory } = useCategories()
        const form = reactive({
            id: null,
            name: null,
            desc: null,
            image: null,
            // _token: page.props.value.csrf_token,
            _method: 'PUT'
        })

        // retrieve category props
        const { id, name, desc } = page.props.value.category;
        form.id = id;
        form.name = name;
        form.desc = desc;

        const selectFile = (event) => {
            form.image = event.target.files[0];
        }

        const onUpdateCategory = () => {
            // await axios.patch(route('categories.update', { 'id': id }), form, {
            //     headers: {
            //         'Content-Type': 'multipart/form-data'
            //     }
            // })
            updateCategory(form)
            // Inertia.post(route('categories.update', { 'id': id }), form, {
            //     forceFormData: true
            // });
        }

        return {
            form,
            onUpdateCategory,
            selectFile
        }

    }
}
</script>
