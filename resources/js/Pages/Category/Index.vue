<template>
    <div class="w-full h-full">
        <Panel header="Manage Category" :toggleable="true">
            <Button label="Add Category" @click="openModalCreate" />

            <DataTable
                :value="rowData"
                :paginator="true"
                :rows="10"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[10, 20, 50]"
                responsiveLayout="scroll"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
            >
                <Column field="id" header="ID"></Column>
                <Column field="image_path" header="Images">
                    <template #body="slotProps">
                        <!-- {{slotProps.data.image_path}} -->
                        <!-- {{JSON.parse(slotProps.data.image_path)}} -->
                        <div class="flex">
                            <!-- {{true}} -->
                            <!-- <img :src="slotProps.data.image_path" /> -->
                            <Image
                                imageClass="w-24 object-cover rounded"
                                v-for="img in JSON.parse(slotProps.data.image_path)"
                                :key="img"
                                :src="showImage() + img"
                                alt="Image Text"
                            />
                        </div>
                        <!-- <div v-else>N/A</div> -->
                        <!-- <img v-for="slotProps.data.image_path in img" :key="img" /> -->
                    </template>
                </Column>
                <Column field="name" header="Name"></Column>
                <Column field="desc" header="Category"></Column>
                <Column header="Actions" field="id">
                    <template #body="slotProps">
                        <div class="p-buttonset flex">
                            <Button
                                label="Show"
                                @click="openModalShow(slotProps.data.id)"
                                icon="pi pi-check"
                            />
                            <Button
                                label="Edit"
                                @click="openModalEdit(slotProps.data.id)"
                                icon="pi pi-times"
                            />
                            <Button
                                label="Delete"
                                @click="confirmDeleteData(slotProps.data.id)"
                                icon="pi pi-trash"
                            />
                        </div>
                    </template>
                </Column>

                <!-- <Dialog header="Category" v-model:visible="display" :breakpoints="{'960px': '75vw', '640px': '100vw'}" :style="{width: '50vw'}">
                <Form v-if="mode.create" @closeModal="display = false" />
                <form-edit v-if="mode.edit" :rowDataEdit="rowDataEdit" />
                </Dialog>-->
            </DataTable>
        </Panel>
        <Dialog
            header="Category"
            v-model:visible="display"
            :breakpoints="{ '960px': '75vw', '640px': '100vw' }"
            :style="{ width: '50vw' }"
        >
            <Form v-if="mode.create" @closeModal="display = false" />
            <form-edit v-if="mode.edit" :row-data-edit="rowDataEdit" />
            <show v-if="mode.show" :category="rowDataShow" />
            <!-- <div v-if="mode.show">
                <p>{{ rowDataShow }}</p>
            </div>-->
        </Dialog>

        <ConfirmDialog></ConfirmDialog>

        <Toast />
    </div>
</template>

<script>
import Layout from '@/Pages/Dashboard.vue'

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Card from 'primevue/card';
import Button from 'primevue/button'
import Panel from 'primevue/panel';
import Dialog from 'primevue/dialog';
import Image from 'primevue/image';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';

import Form from '@/Components/Category/Form.vue'
import FormEdit from '@/Components/Category/FormEdit.vue'
import Show from '@/Components/Category/Show.vue'

import { computed, onBeforeMount, reactive, ref } from 'vue';
import axios from 'axios';
import { useStore } from 'vuex'
export default {
    layout: Layout,
    props: {

    },
    components: { DataTable, Column, Card, Button, Panel, Dialog, Form, Image, ConfirmDialog, Toast, FormEdit, Show },
    setup(props) {

        const store = useStore();


        const rowDataEdit = ref([]);
        const rowDataShow = ref(null);

        const mode = reactive({
            create: false,
            edit: false,
            show: false
        });
        const display = ref(false);
        const confirm = useConfirm();
        const toast = useToast();

        const openModalCreate = () => {
            display.value = !display.value;
            mode.create = true;
        }

        const openModalShow = (id) => {
            display.value = !display.value;
            mode.show = true;
            mode.create = false;
            mode.edit = false;

            store.dispatch("getCategoryById", id);
            rowDataShow.value = computed(() => {
                return store.getters.categoryItems;
            })
        }

        const openModalEdit = (id) => {
            display.value = !display.value;
            mode.edit = true;
            store.dispatch("getCategoryById", id);
            // store.dispatch('getCategoryById', id);
            rowDataEdit.value = computed(() => store.getters.categoryItems)
        }
        const showImage = () => {
            return "/storage/";
        }

        const confirmDeleteData = (id) => {
            confirm.require({
                message: 'Do you want to delete this record?',
                header: 'Delete Confirmation',
                icon: 'pi pi-info-circle',
                acceptClass: 'p-button-danger',
                accept: () => {
                    deleteCategory(id)
                },
                reject: () => {
                    toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
                }
            });
        }

        const fetchData = () => {
            store.dispatch('getCategoryItems');
        }

        const deleteCategory = async (id) => {
            try {
                const response = await axios.delete(`api/categories/${id}`);
                if (response) {
                    fetchData()
                    toast.add({ severity: 'info', summary: 'Confirmed', detail: 'Record deleted', life: 3000 });
                }
            } catch (error) {
                console.log(error)
            }
        }


        // lifecycle
        onBeforeMount(() => {
            fetchData()
        })



        return {
            display,

            rowData: computed(() => store.getters.categoryItems),
            showImage,
            mode,
            openModalCreate,
            openModalShow,
            confirmDeleteData,
            openModalEdit,
            rowDataEdit,
            rowDataShow
        }
    },
}
</script>

<style scoped>
.p-float-label {
    color: black;
}
</style>
