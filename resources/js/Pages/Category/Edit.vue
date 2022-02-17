<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
        <div class="max-w-3xl mx-auto">
            <!-- Content goes here -->
            <Card>
                <template #title>Edit Category</template>
                <template #content>
                    <FormEdit :category="category" />
                    {{ categoryEdit }}
                </template>
            </Card>
        </div>
    </div>
</template>

<script>
import Layout from '@/Pages/Dashboard.vue'
import FormEdit from '@/Components/Category/FormEdit.vue';
import Card from 'primevue/card';
import { reactive } from 'vue';
export default {
    layout: Layout,
    components: {
        FormEdit,
        Card
    },
    props: {
        category: Object
    },
    setup(props) {



        return {

        }

    },
}
</script>

