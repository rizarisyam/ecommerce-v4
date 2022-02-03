<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
        <div class="max-w-3xl mx-auto">
            <!-- Content goes here -->
            <Card>
                <template #title>Create Category</template>
                <template #content>
                    <form @submit.prevent="updateCategory" enctype="multipart/form-data">
                        <div class="mx-auto my-5">
                            <label for="name">Name</label>
                            <InputText
                                class="p-inputtext-sm w-full"
                                id="name"
                                type="text"
                                v-model="categoryForm.name"
                            />
                        </div>

                        <div class="mx-auto my-5">
                            <h5>Description</h5>
                            <Editor v-model="categoryForm.desc" editorStyle="height: 100px" />
                        </div>
                        <div class="mx-auto my-5">
                            <h5>Images</h5>
                            <!-- <Image
                                v-for="item in imageHandler"
                                :key="item"
                                :src="`/storage/${item}`"
                                alt="Image Text"
                                imageClass="w-40 border-2 shadow rounded"
                                preview
                            />-->
                            <FileUpload :auto="true" :customUpload="true" @uploader="myUploader">
                                <template #empty>
                                    <p>Drag and drop files to here to upload.</p>
                                </template>
                            </FileUpload>
                        </div>

                        <div class="mx-auto my-5">
                            <Button type="submit" label="Submit" />
                        </div>
                    </form>
                </template>
            </Card>
        </div>
    </div>
    <!-- {{ JSON.parse(category.image_path) }} -->
</template>

<script>
import Layout from '@/Pages/Dashboard.vue'
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import Editor from 'primevue/editor';
import Button from 'primevue/button';
import Image from 'primevue/image';
import FileUpload from 'primevue/fileupload';

import { useForm } from '@inertiajs/inertia-vue3'
import { ref, computed } from 'vue';
export default {
    layout: Layout,
    props: {
        category: Object
    },
    components: {
        Card,
        InputText,
        Editor,
        Button,
        Image,
        FileUpload
    },
    setup(props) {
        const category = ref(props.category);
        const imageHandler = computed(() => {
            try {
                return JSON.parse(category.value.image_path);
            } catch (error) {
                console.log(error)
            }
        })

        const categoryForm = useForm({
            name: props.category.name,
            desc: props.category.desc,
            fileUpload: []
        })

        const myUploader = (event) => {
            categoryForm.fileUpload = [...event.files];
        }

        const updateCategory = () => {
            categoryForm.patch(route('categories.update', props.category.id))
        }

        return {
            category,
            categoryForm,
            imageHandler,
            myUploader,
            updateCategory
        }
    },
}
</script>pjs

