<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
        <div class="max-w-3xl mx-auto">
            <!-- Content goes here -->
            <Card>
                <template #title>Create Product</template>
                <template #content>
                    <form class="space-y-8 divide-y divide-gray-200" @submit.prevent="storeProduct">
                        <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                            <div>
                                <div class="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
                                    <div
                                        class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:pt-5"
                                    >
                                        <label
                                            for="category"
                                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                        >Product</label>
                                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                                            <div class="max-w-lg flex rounded-md shadow-sm">
                                                <input
                                                    v-model="form.name"
                                                    type="text"
                                                    id="product"
                                                    class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 border-gray-400 rounded sm:text-sm"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:pt-5"
                                    >
                                        <label
                                            for="category"
                                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                        >Category</label>
                                        <select
                                            id
                                            name
                                            v-model="form.category_id"
                                            class="w-full mt-1 block sm:col-span-2 pl-3 pr-10 py-2 text-base border-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        >
                                            <option>Select category</option>
                                            <option
                                                v-for="category in categories"
                                                :key="category.id"
                                                :value="category.id"
                                            >{{ category.name }}</option>
                                        </select>
                                    </div>

                                    <div
                                        class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:pt-5"
                                    >
                                        <label
                                            for="desc"
                                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                        >Description</label>
                                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                                            <textarea
                                                v-model="form.desc"
                                                id="desc"
                                                rows="3"
                                                class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-400 rounded"
                                            ></textarea>
                                        </div>
                                    </div>

                                    <div
                                        class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:pt-5"
                                    >
                                        <label
                                            for="sku"
                                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                        >SKU</label>
                                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                                            <textarea
                                                v-model="form.SKU"
                                                id="sku"
                                                rows="3"
                                                class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-400 rounded"
                                            ></textarea>
                                        </div>
                                    </div>

                                    <div
                                        class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:pt-5"
                                    >
                                        <label
                                            for="price"
                                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                        >Price</label>
                                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                                            <div class="max-w-lg flex">
                                                <InputNumber
                                                    v-model="form.price"
                                                    mode="currency"
                                                    currency="IDR"
                                                    class="w-full h-10 border-gray-300 rounded p-inputnumber"
                                                    locale="id-ID"
                                                    inputClass="bg-green-500"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:pt-5"
                                    >
                                        <label
                                            for="quantity"
                                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                        >Quantity</label>
                                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                                            <div class="max-w-lg flex">
                                                <InputNumber
                                                    v-model="form.quantity"
                                                    class="w-full h-10"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:pt-5"
                                    >
                                        <label
                                            for="price"
                                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                        >Discount</label>
                                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                                            <div class="max-w-lg flex">
                                                <InputNumber
                                                    v-model="form.discount"
                                                    class="w-full h-10"
                                                    mode="decimal"
                                                    :minFractionDigits="2"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:pt-5"
                                    >
                                        <label
                                            for="cover_photo"
                                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                        >Images</label>
                                        <div class="sm:col-span-2">
                                            <FileUpload
                                                :showUploadButton="false"
                                                :showCancelButton="false"
                                                @select="fileSelected"
                                            >
                                                <template #empty>
                                                    <p>Drag and drop files to here to upload.</p>
                                                </template>
                                            </FileUpload>
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
                        <!-- {{ form }} -->
                    </form>
                </template>
            </Card>
        </div>
    </div>
</template>

<script>
import Layout from '@/Pages/Dashboard.vue'

import Card from 'primevue/card';
import InputNumber from 'primevue/inputnumber';
import FileUpload from 'primevue/fileupload';
import Dropdown from 'primevue/dropdown';
import { useForm } from '@inertiajs/inertia-vue3';

import { usePage } from '@inertiajs/inertia-vue3'
import { onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
export default {
    layout: Layout,
    components: {
        InputNumber,
        Card,
        FileUpload,
        Dropdown
    },
    setup() {
        const page = usePage()
        const categories = page.props.value.categories;
        const form = useForm({
            name: null,
            desc: null,
            SKU: null,
            quantity: null,
            price: null,
            discount: null,
            image: null,
            category_id: null
        })
        const fileSelected = (e) => {
            console.log(e)
            // form.image = e.files[0]
            form.image = [...e.files]

            console.log(form.image)
        }

        const storeProduct = () => {
            Inertia.post(route('products.store'), form, {
                forceFormData: true
            })
        }

        onMounted(() => {
            console.log(page.props.value.categories)
        })

        return {
            fileSelected,
            form,
            categories,
            storeProduct
        }
    },
}
</script>

<style scoped>
.p-inputtext {
    padding: 0.5rem 0.75rem;
}
</style>

