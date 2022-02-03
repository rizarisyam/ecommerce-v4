<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
        <div class="max-w-3xl mx-auto">
            <!-- Content goes here -->
            <Card>
                <template #title>Detail Category</template>
                <template #content>
                    <form>
                        <div class="mx-auto my-5">
                            <label for="name">Name</label>
                            <InputText
                                class=".p-inputtext p-inputtext-sm w-full"
                                id="name"
                                type="text"
                                v-model="category.name"
                            />
                        </div>

                        <div class="mx-auto my-5">
                            <h5>Description</h5>
                            <Editor v-model="category.desc" editorStyle="height: 100px" />
                        </div>
                        <div class="mx-auto my-5">
                            <h5>Images</h5>
                            <Image
                                v-for="item in imageHandler"
                                :key="item"
                                :src="`/storage/${item}`"
                                alt="Image Text"
                                imageClass="w-40 border-2 shadow rounded"
                                preview
                            />
                        </div>
                    </form>
                </template>
            </Card>
        </div>
    </div>
</template>

<script>
import Layout from '@/Pages/Dashboard.vue'
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import Editor from 'primevue/editor';
import Button from 'primevue/button';
import Image from 'primevue/image';
import { computed, ref } from 'vue';
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
        Image
    },
    setup(props) {
        const category = ref(props.category)
        const imageHandler = computed(() => {
            try {
                return JSON.parse(category.value.image_path);
            } catch (error) {
                console.log(error)
            }
        })

        return {
            category,
            imageHandler
        }

    },
}
</script>

<style scoped>
.p-inputtext {
    padding: 10px;
}
</style>le
