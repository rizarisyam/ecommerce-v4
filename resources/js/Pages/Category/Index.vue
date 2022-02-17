<template>
    <div class="h-screen">
        <DataTable
            :value="categories"
            :paginator="true"
            :rows="10"
            class="p-datatable-sm"
            headerClass="text-red-500"
        >
            <template #header>
                <div class="table-header flex justify-between items-center">
                    Categories
                    <Link :href="route('categories.create')">
                        <button
                            type="button"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <!-- Heroicon name: solid/mail -->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            Add Category
                        </button>
                    </Link>
                    <!-- <Button label="Tambah" icon="pi pi-check" @click.prevent="toggleModal" /> -->
                </div>
            </template>
            <!-- <Column field="vin" header="Vin"></Column> -->
            <Column field header="Images">
                <template #body="props">
                    <img class="h-20" :src="'storage/' + props.data.image_path" />
                </template>
            </Column>
            <Column field="name" header="Category"></Column>
            <Column field="desc" header="Description" style="width:300px">
                <template #body="props">{{ props.data.desc.slice(0, 100) }}...</template>
            </Column>
            <Column field="id" header="Actions">
                <template #body="props">
                    <div class="flex justify-end">
                        <span class="p-buttonset">
                            <Button label="Save" icon="pi pi-check" />
                            <Link :href="route('categories.edit', props.data.id)">
                                <Button label="Edit" icon="pi pi-times" />
                            </Link>
                            <Button
                                @click="confirmDelete(props.data.id)"
                                label="Delete"
                                icon="pi pi-trash"
                                class="p-button-danger"
                            />
                        </span>
                    </div>
                </template>
            </Column>
        </DataTable>

        <!-- Modal -->
        <Dialog
            :header="headerModal"
            v-model:visible="hasModalOpen"
            :breakpoints="{ '960px': '75vw' }"
            :style="{ width: '50vw' }"
        >
            <FormCreate v-if="!mode.edit" @onCloseModal="closeModal" />
            <!-- <FormEdit v-if="mode.edit" /> -->
        </Dialog>
        <ConfirmDialog></ConfirmDialog>
        <Toast />
    </div>
</template>

<script>

import { onMounted, computed, ref, defineAsyncComponent } from 'vue'

// UI components
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import ConfirmDialog from 'primevue/confirmdialog';
import Toast from 'primevue/toast';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

// category component
const FormCreate = defineAsyncComponent(() => import('@/Components/Category/Form.vue'))
const FormEdit = defineAsyncComponent(() => import("@/Components/Category/FormEdit.vue"))

// store
import { useStore } from 'vuex'
// base layout
import Layout from '@/Pages/Dashboard.vue'
import { Link } from '@inertiajs/inertia-vue3'
import { useCategories } from '@/Composables/categories';

export default {
    layout: Layout,
    components: {
        DataTable,
        Column,
        Button,
        Dialog,
        FormCreate,
        FormEdit,
        ConfirmDialog,
        Toast,
        Link
    },
    setup() {
        const store = useStore()
        // const { categories, fetchCategories } = useCategories();
        // console.log(categories.value)
        const categories = computed(() => store.getters.categoryItems)
        const confirm = useConfirm()
        const toast = useToast();
        // modal state
        const hasModalOpen = ref(false)

        // crud mode
        const mode = ref({
            show: false,
            edit: false
        })

        const headerModal = computed(() => {
            if (mode.value.edit) {
                return "Edit Category"
            } else if (mode.value.show) {
                return "Category Detail"
            } else {
                return "Create Category"
            }
        })

        // methods
        const fetchCategories = () => {
            store.dispatch("getCategoryItems")
        }

        const openModalCreate = () => {
            toggleModal()
        }

        const openModalEdit = async (id) => {
            mode.value.edit = true;
            try {
                await store.dispatch("getCategoryById", id)
                toggleModal()
            } catch (error) {

            }

        }

        const confirmDelete = (id) => {
            confirm.require({
                message: 'Do you want to delete this record?',
                header: 'Delete Confirmation',
                icon: 'pi pi-info-circle',
                acceptClass: 'p-button-danger',
                accept: async () => {
                    await store.dispatch("destroyCategory", id);
                    await store.dispatch("getCategoryItems")
                    toast.add({ severity: 'info', summary: 'Confirmed', detail: 'Record deleted', life: 3000 });
                },
                reject: () => {
                    toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
                }
            });
        }

        const toggleModal = () => {
            hasModalOpen.value = !hasModalOpen.value;
            // mode.value.edit = false;
        }

        const closeModal = () => {
            hasModalOpen.value = false
            mode.value.edit = false;
        }

        const readMoreDesc = (str) => {
            // alert('test')
            str.slice(0, str.length)
        }


        // lifecycle hooks
        onMounted(() => {
            fetchCategories()
        })
        return {
            categories,
            readMoreDesc,
            hasModalOpen,
            toggleModal,
            mode,
            headerModal,
            closeModal,
            openModalEdit,
            confirmDelete
        }
    }
}
</script>
