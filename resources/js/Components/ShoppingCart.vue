<template>
    <navbar />

    <div class="mt-20 max-w-4xl px-4 mx-32 sm:px-6 lg:px-8">
        <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
        <div class="max-w-full">
            <!-- Content goes here -->
            <h5 class="text-xl mx-4 font-semibold">Keranjang</h5>
            <DataTable
                :value="cartItems"
                v-model:selection="selectedProduct"
                selectionMode="multiple"
                data-key="id"
                responsiveLayout="scroll"
                @row-select-all="rowSelectAll"
                @row-unselect-all="rowUnselectAll"
                @row-select="rowSelect"
                @row-unselect="rowUnselect"
            >
                <!-- <template #header>
                    <div class="flex justify-content-center align-items-center">
                        <h5 class="text-xl">Keranjang</h5>
                    </div>
                </template>-->
                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="name" header="Product"></Column>
                <Column field="pivot.price" header="Unit Price">
                    <template #body="slotProps">
                        <span v-if="!loading">{{ rupiahFormat(slotProps.data.pivot.price) }}</span>
                    </template>
                </Column>
                <Column field="pivot.quantity" header="Quantity">
                    <template #body="slotProps">
                        <!-- {{ slotProps }} -->
                        <div
                            class="inline-flex shadow-md hover:shadow-lg focus:shadow-lg"
                            role="group"
                        >
                            <button
                                @click="decreaseQty(slotProps.data)"
                                type="button"
                                :disabled="slotProps.data.pivot.quantity <= 1"
                                class="inline-block px-2 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase focus:bg-blue-700 focus:outline-none focus:ring-0 active:bg-blue-800 transition duration-150 ease-in-out"
                                :class="{ 'bg-gray-500': slotProps.data.pivot.quantity <= 1 }"
                            >
                                <svg
                                    class="fill-current"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                >
                                    <path d="M5 11h14v2H5z" />
                                </svg>
                            </button>
                            <input
                                class="mx-2 border text-center w-12"
                                type="text"
                                v-model="slotProps.data.pivot.quantity"
                            />
                            <button
                                @click="incQty(slotProps.data)"
                                type="button"
                                class="inline-block px-2 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase focus:bg-blue-700 focus:outline-none focus:ring-0 active:bg-blue-800 transition duration-150 ease-in-out"
                            >
                                <svg
                                    class="fill-current text-white"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                >
                                    <path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </Column>
                <Column field="pivot.price" header="Total Price">
                    <template #body="slotprops">{{ rupiahFormat(totalPrice(slotprops.data)) }}</template>
                </Column>
                <Column field="id" header="Actions">
                    <template #body="slotProps">
                        <button
                            type="button"
                            class="inline-block px-2 py-2.5 bg-blue-600 text-white font-medium text-sm leading-4 antialiased tracking-wide focus:bg-blue-700 focus:outline-none focus:ring-0 active:bg-blue-800 transition duration-150 ease-in-out"
                            @click="deleteItemFromCart(slotProps.data)"
                        >Delete</button>
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>

    <div class="w-96 fixed top-20 right-36 shadow-lg border-2">
        <div class="max-w-sm rounded overflow-hidden">
            <div class="px-6 py-4">
                <div class="font-bold text-lg mb-2">Ringkasan Belanja</div>
                <div class="flex justify-between my-4">
                    <p class="text-gray-700 text-base">Total harga ({{ countSelectedItems }} barang)</p>
                    <span>{{ rupiahFormat(totalSelectedItems) }}</span>
                </div>
            </div>

            <div class="border-2 w-80 mx-auto"></div>

            <div class="px-6 py-2 flex justify-between my-4">
                <p class="text-black text-lg font-bold">Total harga</p>
                <span>{{ rupiahFormat(totalSelectedItems) }}</span>
            </div>

            <div class="px-2 py-4">
                <Button
                    @click="submitCheckout"
                    :label="`beli (${countSelectedItems})`"
                    class="p-button-label w-full text-2xl font-bold"
                />
            </div>
        </div>
    </div>
</template>

<script>

import Navbar from "./Navbar.vue"
import { useStore } from 'vuex'
import { computed, onBeforeMount, ref } from "vue";
import rupiahFormat from '@/Helper/rupiahFormat'
import Product from '@/Components/Cart/Product.vue'
import Editor from 'primevue/editor';

import { usePage } from '@inertiajs/inertia-vue3'


import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Card from 'primevue/card';
import InputNumber from 'primevue/inputnumber';
import Dropdown from 'primevue/dropdown';
import Divider from 'primevue/divider';
import Button from 'primevue/button';
import axios from "axios";
import { Inertia } from '@inertiajs/inertia';
import Skeleton from 'primevue/skeleton';



export default {
    components: {
        Navbar,
        Product,
        Editor,
        DataTable,
        Column,
        Card,
        InputNumber,
        Dropdown,
        Divider,
        Button,
        Skeleton
    },
    setup() {
        const store = useStore();
        const page = usePage();

        const selectedProduct = ref([]);
        // const loading = computed(() => store.getters.loading)
        const loading = ref(true);
        const user = page.props.value.auth.user;

        const fetchCartItems = () => {

            store.dispatch('getCartItems', user.id);

        }

        const fetchExpedition = () => {

            store.dispatch("getExpeditionItems");

        }

        const fetchUserAddress = () => {
            store.dispatch("getUserAddress", { user_id: user.id })
        }

        const rowSelect = (event) => {
            console.log(event);
            store.dispatch("getSelectedItems", selectedProduct.value);

        }

        const rowUnselect = (event) => {
            // console.log(event);
            store.dispatch("removeSelectedItems", selectedProduct.value);
        }

        const rowSelectAll = (event) => {
            console.log(event);
            store.dispatch("getSelectedItems", event.data);
        }

        const rowUnselectAll = () => {

            store.dispatch("removeAllSelectedItems", []);
        }

        const incQty = (payload) => {
            console.log(payload);
            payload.pivot.quantity += 1;
            const data = {
                user_id: user.id,
                product_id: payload.id,
                quantity: payload.pivot.quantity,
                price: payload.pivot.price
            }
            console.log(data);

            // store.dispatch('addCartItem', data)
            // store.dispatch('getCartItems', user.id);
        }

        const decreaseQty = (payload) => {
            console.log(payload);
            payload.pivot.quantity -= 1;
            const data = {
                user_id: user.id,
                product_id: payload.id,
                quantity: payload.pivot.quantity,
                price: payload.pivot.price
            }
            console.log(data);

            // store.dispatch('addCartItem', data)
            // store.dispatch('getCartItems', user.id);
        }

        const deleteItemFromCart = (payload) => {
            const data = { ...user, payload }
            console.log(data);
            store.dispatch("deleteCartByItem", { userId: data.id, productId: payload.id })
            fetchCartItems()
            selectedProduct.value = []
            store.dispatch("removeSelectedItems", selectedProduct.value)
        }

        const totalPrice = (payload) => {
            return payload.pivot.price * payload.pivot.quantity;
        }

        const submitCheckout = () => {
            const productId = [];
            for (let i in selectedProduct.value) {
                console.log(selectedProduct.value[i].id)
                const data = {
                    user_id: user.id,
                    product_id: selectedProduct.value[i].id,
                    quantity: selectedProduct.value[i].pivot.quantity,
                    price: selectedProduct.value[i].pivot.price,
                };

                console.log(data);
                axios.post("api/carts/checkout", data)
                    .then(res => {
                        console.log(res)
                    })
                    .catch(err => console.log(err))
                Inertia.visit(route('carts.shipment', user.id))
                // productId.push()
            }

        }



        // lifecycle
        onBeforeMount(() => {
            fetchExpedition()
            fetchCartItems()
            fetchUserAddress()
        })

        return {
            loading,
            rupiahFormat,
            cartItems: computed(() => store.getters.cartItems),
            countCartItems: computed(() => store.getters.countCartItems),
            totalCartItems: computed(() => store.getters.totalCartItems),
            expeditionItems: computed(() => store.getters.expeditionItems),
            selectedProduct,
            rowSelect,
            rowUnselect,
            rowSelectAll,
            rowUnselectAll,
            selectedItems: computed(() => store.getters.selectedItems),
            totalSelectedItems: computed(() => store.getters.totalSelectedItems),
            incQty,
            decreaseQty,
            deleteItemFromCart,
            totalPrice,
            userAddress: computed(() => store.getters.userAddress),
            countSelectedItems: computed(() => store.getters.countSelectedItems),
            submitCheckout
        }
    },
}
</script>

<style scoped>
.p-button-label {
    font-size: 1.3rem;
    text-transform: capitalize;
}
</style>
