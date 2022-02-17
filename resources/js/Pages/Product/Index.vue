<template>
    <div class="h-screen">
        <Message v-if="message" class="p-messages" severity="success">{{ message }}</Message>
        <DataTable :value="products" responsiveLayout="scroll">
            <template #header>
                <div class="flex items-center justify-between">
                    <div>
                        <h3>Products</h3>
                    </div>
                    <Link :href="route('products.create')">
                        <Button label="Create Product" class="p-button-success" />
                    </Link>
                </div>
            </template>
            <Column field header="Images">
                <template #body="props">
                    <div v-if="props.data.image_path !== null">
                        <div class="flex">
                            <img
                                v-for="image in JSON.parse(props.data.image_path)"
                                :key="image"
                                class="h-16 object-cover"
                                :src="'storage/' + image"
                                alt="not found"
                            />
                        </div>
                    </div>
                    <div v-else>N/A</div>
                </template>
            </Column>
            <Column field="name" header="Product">
                <template #body="props">
                    <div class="flex flex-col">
                        {{ props.data.name }}
                        <p class="text-gray-400 text-sm">{{ props.data.category.name }}</p>
                    </div>
                </template>
            </Column>
            <Column field="desc" header="Description" style="width:300px">
                <template #body="props">
                    <div>
                        <span>{{ props.data.desc.slice(0, 100) + '...' }}</span>
                    </div>
                </template>
            </Column>
            <Column field="quantity" header="Quantity"></Column>
            <Column field="price" header="Price">
                <template #body="props">
                    <div>
                        <span>{{ rupiahFormat(props.data.price) }}</span>
                    </div>
                </template>
            </Column>
            <Column field header="Actions">
                <template #body="props">
                    <div class="flex justify-end">
                        <span class="p-buttonset">
                            <Button label="Save" icon="pi pi-check" />
                            <Link :href="route('products.edit', props.data.id)">
                                <Button label="Edit" icon="pi pi-times" />
                            </Link>

                            <Button
                                label="Delete"
                                icon="pi pi-trash"
                                class="p-button-danger"
                                @click="deleteProduct(props.data.id)"
                            />
                        </span>
                    </div>
                </template>
            </Column>
        </DataTable>
        <ConfirmDialog
            :breakpoints="{ '960px': '75vw', '640px': '100vw' }"
            :style="{ width: '50vw' }"
        ></ConfirmDialog>
        <Toast />
    </div>
</template>

<script>
import BaseLayout from '@/Pages/Dashboard.vue'
import { onMounted, computed, ref, defineAsyncComponent } from 'vue'

import { useProduct } from '@/Composables/product'
import rupiahFormat from '@/Helper/rupiahFormat';

// UI components
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Message from 'primevue/message';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from "primevue/useconfirm";
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";
import { Link, usePage } from '@inertiajs/inertia-vue3'
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';

export default {
    components: {
        DataTable,
        Column,
        Button,
        Link,
        Message,
        ConfirmDialog,
        Toast
    },
    layout: BaseLayout,
    setup(props) {
        const page = usePage();
        const confirm = useConfirm();
        const toast = useToast();
        const message = page.props.value.flash.message;
        const { products, getProducts } = useProduct()

        const deleteProduct = (id) => {
            confirm.require({
                message: 'Do you want to delete this record?',
                header: 'Delete Confirmation',
                icon: 'pi pi-info-circle',
                acceptClass: 'p-button-danger',
                accept: async () => {
                    // await axios.delete('api.products.destroy', id);
                    Inertia.delete(route('products.destroy', id));
                    getProducts()
                    toast.add({ severity: 'success', summary: 'Success Message', detail: 'Message Content', life: 3000 });
                },
                reject: () => {
                    toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
                }
            });
        }



        onMounted(() => {
            getProducts()
            // console.log(page)
        })
        return {
            products,
            rupiahFormat,
            message,
            deleteProduct
        }
    }
}
</script>

<style scoped>
.p-messages {
    /* padding: 0.5rem 0.75rem; */
    height: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
</style>
