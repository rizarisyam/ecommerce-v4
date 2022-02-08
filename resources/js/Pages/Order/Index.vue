<template>
    <div>
        <Panel header="Header Text" :toggleable="true" :collapsed="false">
            <DataTable
                :value="orders"
                stripedRows
                responsiveLayout="scroll"
                :paginator="true"
                :rows="10"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                :rowsPerPageOptions="[10, 20, 50]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
            >
                <Column field="invoice_number" header="Order Number"></Column>
                <Column field="products" header="Products">
                    <template #body="slotProps">
                        <div
                            v-for="product in slotProps.data.products"
                            :key="product.id"
                        >{{ product.name }}</div>
                    </template>
                </Column>
                <Column field="products" header="Price">
                    <template #body="slotProps">
                        <div
                            v-for="product in slotProps.data.products"
                            :key="product.id"
                        >{{ rupiahFormat(product.pivot.price) }}</div>
                    </template>
                </Column>
                <Column field="products" header="Quantity">
                    <template #body="slotProps">
                        <div
                            v-for="product in slotProps.data.products"
                            :key="product.id"
                        >{{ product.pivot.quantity }} pcs</div>
                    </template>
                </Column>
                <Column field="status" header="Status">
                    <template #body="slotProps">
                        <div>
                            <Tag
                                class="mr-2 p-tag-value"
                                :icon="severityHandle(slotProps.data.status).icon"
                                :value="slotProps.data.status"
                                :severity="severityHandle(slotProps.data.status).severity"
                                rounded
                            ></Tag>
                        </div>
                    </template>
                </Column>
                <Column field="id" header="Status">
                    <template #body="slotProps">
                        <span class="p-buttonset">
                            <Button label="Show" icon="pi pi-eye" @click="onModalOpen" />
                            <Button
                                label="Edit"
                                icon="pi pi-pencil"
                                @click="onModalOpen(slotProps.data.id)"
                            />
                            <Button label="Delete" icon="pi pi-trash" />
                        </span>
                    </template>
                </Column>
            </DataTable>
        </Panel>

        <Dialog
            header="Header"
            v-model:visible="hasModalOpen"
            :breakpoints="{ '960px': '75vw' }"
            :style="{ width: '50vw' }"
        >
            <div>
                <show v-if="mode.show" :orderID="orderId" />
                <form v-if="mode.edit" class="max-w-md flex flex-col gap-4">
                    <div class="flex items-center">
                        <label for class="w-40">Expedition Price</label>
                        <InputNumber
                            class="flex-1"
                            input-style="padding: 5px;"
                            inputClass="px-0"
                            id="currency-us"
                            v-model="orderForm.expeditionPrice"
                            mode="currency"
                            currency="IDR"
                            locale="id-ID"
                        />
                    </div>
                    <div class="flex items-center">
                        <!-- {{ statusOrder }} -->
                        <label for class="w-40">Status</label>
                        <select
                            id="location"
                            name="location"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md flex-1"
                            v-model="orderForm.statusOrder"
                        >
                            <option value>Status Order</option>
                            <option
                                v-for="item in statusOrder"
                                :key="item"
                                :value="item.name"
                            >{{ item.name.toUpperCase() }}</option>

                            <!-- <option>EU</option> -->
                        </select>
                    </div>
                </form>
            </div>
            <template #footer>
                <Button label="Update" icon="pi pi-check" @click="updateOrder" autofocus />
            </template>
        </Dialog>
    </div>
</template>

<script>
import Layout from '@/Pages/Dashboard.vue'

// UI Component
import Panel from 'primevue/panel';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Dropdown from 'primevue/dropdown';

// components
import Show from '@/Components/User/Purchase/Show.vue';

import { ref, onBeforeMount, computed, reactive } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3'
import axios from 'axios';
import rupiahFormat from '@/Helper/rupiahFormat';
export default {
    components: {
        Panel,
        DataTable,
        Column,
        Tag,
        Button,
        Dialog,
        InputText,
        Dropdown,
        InputNumber,
        Show
    },
    layout: Layout,
    setup() {

        const orders = ref([]);
        const orderId = ref(Number);
        const hasModalOpen = ref(false)
        const statusOrder = ref([
            { name: 'unpaid' },
            { name: 'paid' },
            { name: 'packed' },
            { name: 'sent' },
            { name: 'finish' },
            { name: 'cancel' },

        ]);

        const mode = reactive({
            show: false,
            edit: false
        })

        const page = usePage();

        const user = page.props.value.auth.user;

        const orderForm = reactive({
            statusOrder: '',
            expeditionPrice: 0
        })
        // methods
        const onModalOpen = (id) => {
            hasModalOpen.value = !hasModalOpen.value;
            orderId.value = id;
            mode.show = true;
        }

        const updateOrder = () => {
            const data = {
                expeditionPrice: orderForm.expeditionPrice,
                status: orderForm.statusOrder,
                user: user.name
            }

            axios.patch(route('api.orders.update', orderId.value), data)
                .then(res => console.log(res)).catch(err => console.log(err))
            hasModalOpen.value = false;
            console.log(data);
        }

        const fetchOrders = async () => {
            try {

                const response = await axios.get(route('api.orders.index'));
                console.log(response);
                orders.value = response.data
            } catch (error) {
                console.log(error)
            }
        }

        const severityHandle = (value) => {
            switch (value) {
                case "UNPAID":
                    return {
                        icon: 'pi pi-exclamation-triangle',
                        severity: 'warning'
                    };
                case "PAID":
                    return {
                        icon: 'pi pi-check',
                        severity: 'success'
                    };
                // default:
                //     return "success";
            }
        }

        // lifecycle
        onBeforeMount(() => {
            fetchOrders()
        })

        return {
            orders,
            rupiahFormat,
            severityHandle,
            onModalOpen,
            hasModalOpen,
            statusOrder,
            orderForm,
            updateOrder,
            orderId,
            mode
        }

    },
}
</script>

<style scoped>
.p-tag {
    padding: 5px 10px;
}

.p-tag-value {
    letter-spacing: 2px;
}

.p-inputtext {
    padding: 8px;
}

.p-inputnumber-input {
    padding: 2px;
    border: 2px solid red;
}
</style>
