<template>
<form class="" @submit.prevent="submitCategory" enctype="multipart/form-data">
        <div class="mx-auto my-5">
            <h5>Name</h5>
        <span class="p-float-label">
            <InputText class="w-full" id="name" type="text" v-model="category.name" />
            <label for="name">Name</label>
        </span>
        </div>

        <div class="mx-auto my-5">
            <h5>Description</h5>
             <Editor v-model="category.desc" editorStyle="height: 100px"/>
        </div>
        <div class="mx-auto my-5">
            <h5>Description</h5>
             <FileUpload name="demo[]" :auto="true" :customUpload="true" @uploader="myUploader" >
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

<script>
import InputText from 'primevue/inputtext';
import Editor from 'primevue/editor';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';

import {useForm} from '@inertiajs/inertia-vue3'
import axios from 'axios';
import {reactive} from 'vue'

export default {
    components: {
InputText,Editor,FileUpload,Button
    },
    setup(props, context) {

        const category = useForm({
            name: null,
            desc: null,
            fileUpload: []
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

        //     axios.post('/api/categories', formData, {
        //         headers: {
        //   "Content-Type": "multipart/form-data"
        // }
        //     }).then(res => console.log(res)).catch(err => console.log(err))
            category.post(route('categories.store'), formData)

            context.emit('closeModal');
        }

        return {
            myUploader,
            category,
            submitCategory
        }
    },
}
</script>s
