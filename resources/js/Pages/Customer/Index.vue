<template>
    <div class="h-screen">
        <DataTable :value="dataCustomer.data" responsiveLayout="scroll">
            <template #header>
                <div class="table-header flex justify-between">
                    Products
                    <Button
                        label="Add Customer"
                        class="p-button-raised p-button-info"
                        @click="onOpenModalCreate"
                    />
                </div>
            </template>
            <Column field="username" header="Username"></Column>
            <Column field="avatar" header="Avatar">
                <template #body="slotProps">
                    <div v-if="slotProps.data.avatar">
                        <img class="w-20" :src="'/storage/' + slotProps.data.avatar" />
                    </div>
                    <div v-else>N/A</div>
                </template>
            </Column>
            <Column field="name" header="Name"></Column>
            <Column field="phone_number" header="Phone"></Column>
            <Column field="gender" header="Gender"></Column>
            <Column field="birthday" header="Birthday"></Column>
            <Column field="id" header="Actions">
                <template #body="slotProps">
                    <div class="flex justify-end">
                        <span class="p-buttonset">
                            <Button label="Show" icon="pi pi-check" />
                            <Button label="Edit" icon="pi pi-times" />
                            <Button
                                class="p-button-danger"
                                label="Delete"
                                icon="pi pi-trash"
                                @click="confirmDelete(slotProps.data.id)"
                            />
                        </span>
                    </div>
                </template>
            </Column>
        </DataTable>

        <Dialog
            :header="headerModal"
            v-model:visible="isOpen"
            :breakpoints="{ '960px': '75vw' }"
            :style="{ width: '50vw' }"
        >
            <create v-if="mode.create" @fetchData="fetchCustomer" @closeModal="onCloseModal" />
            <template #footer>
                <Button
                    label="No"
                    icon="pi pi-times"
                    @click="closeResponsive"
                    class="p-button-text"
                />
                <Button label="Yes" icon="pi pi-check" @click="closeResponsive" autofocus />
            </template>
        </Dialog>
        <ConfirmDialog></ConfirmDialog>
        <Toast />
    </div>
</template>

<script>
import Layout from '@/Pages/Dashboard.vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button'
import Dialog from 'primevue/dialog';
import ConfirmDialog from 'primevue/confirmdialog';
import Toast from 'primevue/toast'

// component UI Component
import Create from '@/Components/Customer/Create.vue';
import { computed, onBeforeMount, reactive, ref } from 'vue';
import { useConfirm } from 'primevue/useconfirm'
import { useToast } from 'primevue/usetoast'
import axios from 'axios';
import { useStore } from 'vuex'


export default {
    layout: Layout,

    components: {
        DataTable,
        Column,
        Button,
        Dialog,
        Create,
        ConfirmDialog,
        Toast
    },
    setup() {
        const isOpen = ref(false)

        const mode = reactive({
            create: false,
            edit: false,
            show: false
        });

        const store = useStore();

        const confirm = useConfirm();
        const toast = useToast();

        // methods
        const fetchCustomer = () => {
            store.dispatch("fetchCustomer");
            // try {
            //     const response = await axios.get('/api/customer');
            //     console.log(response);
            //     dataCustomer.value = response.data.data;
            // } catch (error) {
            //     console.log(error.message)
            // }
        }
        const confirmDelete = (id) => {
            confirm.require({
                message: 'Do you want to delete this record?',
                header: 'Delete Confirmation',
                icon: 'pi pi-info-circle',
                acceptClass: 'p-button-danger',
                accept: async () => {
                    try {
                        const response = await axios.delete(route('api.customer.destroy', id))

                        fetchCustomer()
                        toast.add({ severity: 'success', summary: 'Confirmed', detail: 'Record deleted', life: 3000 });

                        console.log(response);
                    } catch (error) {
                        console.log(error.message)
                    }
                },
                reject: () => {
                    toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
                }
            });
        }
        const onOpenModal = () => {
            isOpen.value = !isOpen.value;
        }

        const onCloseModal = () => {
            isOpen.value = false;
        }

        const onOpenModalCreate = () => {
            onOpenModal();
            mode.create = true;
        }

        // computed
        const headerModal = computed(() => {
            let header = "";
            if (mode.create) {
                header = "Create Customer";
            } else if (mode.edit) {
                header = "Edit Customer";
            } else if (mode.show) {
                header = "Show Customer";
            } else {
                header = "Modal Example";
            }

            return header;
        })

        // lifecycle
        onBeforeMount(() => {
            fetchCustomer()
        })

        const dataCustomer = computed(() => {
            // const { data } = store.getters.getCustomers;
            // return data;
            return store.getters.getCustomers
        })

        return {
            isOpen,
            onOpenModal,
            mode,
            headerModal,
            onOpenModalCreate,
            dataCustomer,
            confirmDelete,
            onCloseModal
        }
    },
}
</script>

<style scoped>
.p-button-info {
    /* background-color: red; */
    padding: 5px 8px;
    /* color: black; */
}
</style>

