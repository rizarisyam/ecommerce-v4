<template>
    <form class @submit.prevent="submitCategory" enctype="multipart/form-data">
        <div class="mx-auto my-5">
            <h5>Name</h5>

            <span class="p-float-label">
                <InputText class="w-full" id="name" type="text" v-model="category.name" />
                <label for="name">Name</label>
            </span>
        </div>

        <div class="mx-auto my-5">
            <h5>Description</h5>
            <Editor v-model="category.desc" editorStyle="height: 100px" />
        </div>
        <div class="mx-auto my-5">
            <!-- {{ typeof JSON.parse(rowData.image_path) }} -->
            <!-- <Image
                v-for="item in showImage"
                :key="item"
                :src="'/storage/' + item"
                alt="Image Text"
                imageClass="w-32"
            />-->
            <h5>Description</h5>
            <FileUpload name="demo[]" :auto="true" :customUpload="true" @uploader="myUploader">
                <template #empty>
                    <p>Drag and drop files to here to upload.</p>
                </template>
            </FileUpload>
        </div>

        <div class="mx-auto my-5">
            <Button type="submit" label="Submit" />
        </div>
    </form>
    {{ category }}
</template>

<script>
import InputText from 'primevue/inputtext';
import Editor from 'primevue/editor';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';
import Image from 'primevue/image';

import { useForm } from '@inertiajs/inertia-vue3'
import axios from 'axios';
import { computed, reactive, ref } from 'vue'
import { useStore } from 'vuex'
import { Inertia } from '@inertiajs/inertia';

export default {
    props: {
        rowDataEdit: Object
    },
    components: {
        InputText, Editor, FileUpload, Button, Image,
    },
    setup(props, context) {
        const rowData = ref(props.rowDataEdit);

        console.log(rowData);

        const store = useStore();
        // const category = useForm({
        //     id: rowData.value.id,
        //     name: rowData.value.name,
        //     desc: rowData.value.desc,
        //     fileUpload: []
        // })

        const category = reactive({
            id: props.rowDataEdit.id,
            name: props.rowDataEdit.name,
            desc: props.rowDataEdit.desc,
            fileUpload: []
        })

        const showImage = computed(() => {
            try {
                const stringToArray = JSON.parse(rowData.value.image_path);
                return stringToArray;
            } catch (error) {
                console.log(error)
            }
        })

        //  const category = reactive({
        //     name: null,
        //     desc: null,
        //     fileUpload: []
        // })

        const myUploader = (event) => {

            category.fileUpload = [...event.files];

            // console.log(event);
        }

        const submitCategory = () => {
            const formData = new FormData();

            formData.append('fileUpload', category.fileUpload);
            formData.append('name', category.name);
            formData.append('desc', category.desc);
            console.log(category);

            //     axios.post('/api/categories', formData, {
            //         headers: {
            //   "Content-Type": "multipart/form-data"
            // }
            //     }).then(res => console.log(res)).catch(err => console.log(err))
            // Inertia.patch(route('api.categories.update', rowData.value.id), {
            //     fileUpload: category.fileUpload
            // })
            Inertia.patch(route('api.categories.update', category.id), {
                formData
            })
            // category.patch(route('api.categories.update', rowData.value.id))

            context.emit('closeModal');
        }

        return {
            myUploader,
            category,
            submitCategory,
            rowData,
            showImage,

        }
    },
}
</script>s
