<template>
    <div class="h-screen">
        <DataTable :value="customers" responsiveLayout="scroll">
            <template #header>
                <div class="table-header flex justify-between">
                    Products
                    <Button
                        label="Add Customer"
                        class="p-button-raised p-button-info"
                        @click="onOpenModal"
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
                            <Button
                                label="Edit"
                                icon="pi pi-times"
                                @click.prevent="editHandler(slotProps.data.id)"
                            />
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
            @hide="onCloseModal"
            :breakpoints="{ '960px': '75vw' }"
            :style="{ width: '50vw' }"
        >
            <!-- <form class enctype="multipart/form-data" @submit.prevent="storeCustomer">
                <div class="flex items-center">
                    <label class="w-40" for="username">Username</label>
                    <input id="username" class="flex-1" type="text" v-model="customer.username" />
                </div>

                <div class="flex items-center mt-4">
                    <label class="w-40" for="name">Name</label>
                    <input id="name" class="flex-1" type="text" v-model="customer.name" />
                </div>

                <div class="flex items-center mt-4">
                    <label class="w-40">Avatar</label>
                    <input class="hidden" ref="inputFile" type="file" @change="onFileChange" />
                    <div class="flex-1">
                        <div v-if="mode.edit && customer.avatar">
                            <img class="w-20 object-cover" :src="`storage/${customer.avatar}`" />
                        </div>
                        <button
                            @click.prevent="onFile"
                            type="button"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mb-2"
                        >Upload</button>

                        <div
                            v-if="imagePreview"
                            class="border-2 border-dashed border-gray-600 w-full h-20 px-2 py-3"
                        >

                            <img class="w-20 object-cover" :src="imagePreview" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center mt-4">
                    <label class="w-40" for>Phone Number</label>
                    <input class="flex-1" type="text" v-model="customer.phone_number" />
                </div>

                <div class="flex items-center mt-4">
                    <label class="w-40" for>Gender</label>
                    <div class="flex-1 flex border items-center gap-2">
                        <div class="flex gap-2 items-center">
                            <input
                                class
                                id="men"
                                type="radio"
                                value="laki-laki"
                                v-model="customer.gender"
                            />
                            <label for="men">Laki-laki</label>
                        </div>

                        <div class="flex gap-2 items-center">
                            <input
                                class
                                id="girl"
                                type="radio"
                                value="perempuan"
                                v-model="customer.gender"
                            />
                            <label for="girl">Perempuan</label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center mt-4">
                    <label class="w-40">Birthday</label>
                    <input class="flex-1" type="date" @change="onBirthdayChange" />
                </div>

                <div class="flex items-center mt-4">
                    <label class="w-40"></label>
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mb-2"
                    >Submit</button>
                </div>
                <div></div>

            </form>-->
            <Form @closeModal="onCloseModal"></Form>
            <!-- <create v-if="mode.create" @fetchData="fetchCustomer" @closeModal="onCloseModal" /> -->
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
import Form from '@/Components/Customer/Form.vue';
import ProgressSpinner from 'primevue/progressspinner';



import { computed, onBeforeMount, reactive, ref } from 'vue';
import { useConfirm } from 'primevue/useconfirm'
import { useToast } from 'primevue/usetoast'
import axios from 'axios';
import { useStore } from 'vuex'
import { Inertia } from '@inertiajs/inertia';

import { useCustomer } from '@/Composables/customer'

export default {
    layout: Layout,

    components: {
        DataTable,
        Column,
        Button,
        Dialog,
        Create,
        ConfirmDialog,
        Toast,
        ProgressSpinner,
        Form
    },
    setup() {

        const store = useStore();
        const confirm = useConfirm();
        const toast = useToast();
        const isOpen = ref(false)
        const dataCustomer = ref([]);

        const { getAllCustomer, customers, destroyCustomer } = useCustomer()


        const mode = reactive({
            create: false,
            edit: false,
            show: false
        });


        // methods
        const fetchCustomer = () => {
            store.dispatch("fetchCustomer");
        }

        const confirmDelete = (id) => {
            confirm.require({
                message: 'Do you want to delete this record?',
                header: 'Delete Confirmation',
                icon: 'pi pi-info-circle',
                acceptClass: 'p-button-danger',
                accept: async () => {
                    try {
                        // const response = await axios.delete(route('api.customer.destroy', id))
                        destroyCustomer(id)
                        getAllCustomer()
                        // fetchCustomer()
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


        const editHandler = (id) => {
            onOpenModal();
            store.commit('TOGGLE_EDITMODE', true)
            store.dispatch("getCustomerById", { id })
        }



        const onOpenModal = () => {
            isOpen.value = !isOpen.value;

        }

        const onCloseModal = () => {
            isOpen.value = false;
            store.commit("TOGGLE_EDITMODE", false)
        }

        const onOpenModalCreate = () => {
            onOpenModal();
            store.commit("TOGGLE_EDITMODE", false)
        }

        // computed
        const headerModal = computed(() => {
            let header = "";
            if (store.getters.getEditMode) {
                header = "Edit Customer";
            } else {
                header = "Create Customer";
            }

            return header;
        })

        // lifecycle
        onBeforeMount(() => {
            // fetchCustomer()
            console.log('before mount')
            getAllCustomer()
        })



        return {
            customers,
            isOpen,
            onOpenModal,
            mode,
            headerModal,
            onOpenModalCreate,
            dataCustomer,
            confirmDelete,
            onCloseModal,
            editHandler,
            dataCustomer: computed(() => store.getters.getCustomers)

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

