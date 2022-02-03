<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
        <div class="max-w-3xl mx-auto">
            <!-- Content goes here -->
            <Card>
                <template #title>Create Category</template>
                <template #content>
                    <form-add />
                </template>
            </Card>
        </div>
    </div>
</template>

<script>
import Layout from '@/Pages/Dashboard.vue'
import FormAdd from '@/Components/Category/Form.vue'
import Card from 'primevue/card';
export default {
    layout: Layout,
    components: {
        FormAdd,
        Card
    },
    setup() {

    },
}
</script>pjs

