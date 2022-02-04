
<template>
    <div class="w-full h-full">
        <Panel :toggleable="true">
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
                <Column field="name" header="Name"></Column>
                <Column header="Actions" field="id">
                    <template #body="slotProps">
                        <div class="p-buttonset flex">
                            <Button label="Show" icon="pi pi-check" />
                            <Button
                                label="Edit"
                                @click="editModal(slotProps.data.id)"
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
            </DataTable>
        </Panel>
        <Dialog
            :header="editMode ? 'Expedition Edit' : 'Create Expedition'"
            v-model:visible="display"
            :breakpoints="{ '960px': '75vw', '640px': '100vw' }"
            :style="{ width: '50vw' }"
            @hide="closeModal"
        >
            <form class @submit.prevent="editMode ? updateExpedition() : createExpedition()">
                <div class="mx-auto my-5">
                    <label for="name">Name</label>
                    <InputText
                        class="p-inputtext-sm w-full"
                        id="name"
                        type="text"
                        v-model="expedition.name"
                    />
                </div>

                <div class="mx-auto my-5">
                    <Button v-if="!editMode" type="submit" label="Submit" />
                    <Button v-if="editMode" type="submit" label="Update" />
                </div>
            </form>
            <!-- <Form v-if="mode.create" @closeModal="display = false" />
            <form-edit v-if="mode.edit" :row-data-edit="rowDataEdit" />-->
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
import InputText from 'primevue/inputtext';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';

import Form from '@/Components/Category/Form.vue'
import FormEdit from '@/Components/Category/FormEdit.vue'

import { computed, onBeforeMount, ref } from 'vue';


import axios from 'axios';
import { useStore } from 'vuex'
export default {
    layout: Layout,
    props: {

    },
    components: { DataTable, Column, Card, Button, Panel, Dialog, Form, Image, ConfirmDialog, Toast, FormEdit, InputText },
    setup(props) {

        const store = useStore();

        const editMode = ref(false);

        const expedition = ref({
            name: null
        })

        const display = ref(false);
        const confirm = useConfirm();
        const toast = useToast();

        const openModalCreate = () => {
            display.value = !display.value;
            expedition.value = {}
        }

        const editModal = (payload) => {
            editMode.value = true;
            display.value = true;
            axios.get(`/api/expeditions/${payload}`).then(res => {
                console.log(res)
                expedition.value = { ...res.data }
            }).catch(err => {
                console.log(err)
            })
        }

        const updateExpedition = () => {
            const data = {
                name: expedition.value.name
            }
            axios.put(`/api/expeditions/${expedition.value.id}`, data)
                .then(res => {
                    console.log(res)
                    fetchData()
                    display.value = false
                    editMode.value = false;
                })
                .catch(err => {
                    console.log(err)
                })
        }

        const closeModal = () => {
            editMode.value = false;
        }

        const createExpedition = () => {
            const data = {
                name: expedition.value.name
            }
            axios.post('/api/expeditions', data)
                .then(res => {
                    console.log(res)
                    display.value = false;
                    fetchData()
                })
                .catch(err => {
                    console.log(err)
                })
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
                    deleteExpedition(id)
                },
                reject: () => {
                    toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
                }
            });
        }

        const fetchData = () => {
            store.dispatch('getExpeditionItems');
        }

        const deleteExpedition = async (id) => {
            try {
                const response = await axios.delete(`/api/expeditions/${id}`);
                console.log(response)
                if (response) {
                    fetchData()

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
            closeModal,
            editModal,
            expedition,
            rowData: computed(() => store.getters.expeditionItems),
            showImage,
            editMode,
            openModalCreate,
            confirmDeleteData,
            updateExpedition,
            createExpedition
        }
    },
}
</script>

<style scoped>
.p-float-label {
    color: black;
}
</style>
