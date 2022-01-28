<template>
    <form class @submit.prevent="submitProduct" enctype="multipart/form-data">
        <div class="mx-auto my-5">
            <h5>Name</h5>

            <InputText class="w-full" id="name" type="text" v-model="product.name" />
        </div>

        <div class="mx-auto my-5">
            <h5>Category</h5>

            <Dropdown
                v-model="product.category_id"
                class="w-full"
                :options="categories.value"
                optionLabel="name"
                optionValue="id"
                placeholder="Select a City"
            />
        </div>

        <div class="mx-auto my-5">
            <h5>SKU</h5>
            <Editor v-model="product.SKU" editorStyle="height: 100px" />
        </div>

        <div class="mx-auto my-5">
            <h5>Description</h5>
            <Editor v-model="product.desc" editorStyle="height: 100px" />
        </div>

        <div class="mx-auto my-5">
            <h5>Upload</h5>
            <FileUpload name="demo[]" :auto="true" :customUpload="true" @uploader="myUploader">
                <template #empty>
                    <p>Drag and drop files to here to upload.</p>
                </template>
            </FileUpload>
        </div>

        <div class="mx-auto my-5">
            <h5>Price</h5>
            <InputNumber
                class="w-full"
                v-model="product.price"
                mode="currency"
                currency="IDR"
                locale="id-ID"
            />
        </div>

        <div class="mx-auto my-5">
            <h5>Discount</h5>
            <InputNumber
                class="w-full"
                v-model="product.discount"
                mode="decimal"
                :minFractionDigits="2"
            />
        </div>

        <div class="mx-auto my-5">
            <Button type="submit" label="Submit" />
        </div>
    </form>
    {{ product }}
    <!-- {{ JSON.stringify(categories).value }} -->
</template>

<script>
import InputText from "primevue/inputtext";
import Editor from "primevue/editor";
import FileUpload from "primevue/fileupload";
import Button from "primevue/button";
import InputNumber from "primevue/inputnumber";
import Dropdown from 'primevue/dropdown';

import { useForm } from "@inertiajs/inertia-vue3";
import axios from "axios";
import { computed, onBeforeMount, reactive, ref } from "vue";
import { useStore } from 'vuex'

export default {
    components: {
        InputText,
        Editor,
        FileUpload,
        Button,
        InputNumber,
        Dropdown
    },
    setup(props, context) {
        const product = useForm({
            name: null,
            desc: null,
            SKU: null,
            price: 0,
            discount: 0,
            category_id: null,
            fileUpload: [],
        });
        const store = useStore();
        const categories = ref([]);

        const fetchDataCategories = () => {
            store.dispatch('getCategoryItems');
            categories.value = computed(() => store.getters.categoryItems)
        }

        onBeforeMount(() => {
            fetchDataCategories()
        })

        //  const category = reactive({
        //     name: null,
        //     desc: null,
        //     fileUpload: []
        // })

        const myUploader = (event) => {
            product.fileUpload = [...event.files];
            // console.log(event);
        };

        const submitProduct = () => {
            const formData = new FormData();

            formData.append("fileUpload", product.fileUpload);

            const data = {
                ...formData,
                name: product.name,
                desc: product.desc,
                SKU: product.SKU,
                price: product.price,
                discount: product.discount,
                category_id: product.category_id,
            }
            //     axios.post('/api/categories', formData, {
            //         headers: {
            //   "Content-Type": "multipart/form-data"
            // }
            //     }).then(res => console.log(res)).catch(err => console.log(err))
            product.post(route("products.store"), data);

            context.emit("closeModal");
        };

        return {
            myUploader,
            product,
            submitProduct,
            categories
        };
    },
};
</script>s
