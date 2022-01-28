<template>
    <navbar />

    <div
        class="mt-20 max-w-full h-screen sm:px-6 lg:px-8 my-5 flex gap-4 justify-between bg-gray-50 border-4 py-5"
    >
        <div class="flex-1">
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
                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="name" header="Product"></Column>
                <Column field="pivot.price" header="Unit Price">
                    <template #body="slotProps">{{ rupiahFormat(slotProps.data.pivot.price) }}</template>
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

        <div>
            <Card class="max-w-md">
                <template #title>Order Summary</template>
                <template #content>
                    <div v-for="item in selectedItems" :key="item.id" class="h-32 my-5">
                        <div
                            class="h-10 lg:h-auto lg:w-48 flex-none bg-cover rounded-md text-center overflow-hidden"
                            style="background-image: url('https://images.unsplash.com/photo-1572635196237-14b3f281503f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=880&q=80')"
                            title="Woman holding a mug"
                        ></div>
                        <div class="rounded-md p-4 flex flex-col justify-between leading-normal">
                            <div class="mb-8">
                                <div class="text-gray-900 font-bold text-xl mb-2">{{ item.name }}</div>
                            </div>
                            <div class="flex items-center">
                                <div class="text-sm flex justify-center items-center">
                                    <p
                                        class="text-gray-900 font-semibold leading-none"
                                    >{{ rupiahFormat(item.pivot.price) }}</p>
                                    <span class="text-gray-500">
                                        <svg
                                            class="w-4 fill-current"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"
                                            />
                                        </svg>
                                    </span>
                                    <p
                                        class="text-gray-900 font-semibold leading-none"
                                    >{{ item.pivot.quantity }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <Divider />

                    <div class="flex justify-end">
                        <div class="flex justify-end gap-4 w-full">
                            <span>Total Harga:</span>
                            <span>{{ rupiahFormat(totalSelectedItems) }}</span>
                        </div>
                    </div>
                </template>
            </Card>
        </div>
        <!-- {{ selectedItems }}
        {{ totalSelectedItems }}-->
    </div>
    <!-- <div class="container mx-auto mt-10">
        <div class="flex shadow-md my-10">
            <div class="w-3/4 bg-white px-10 py-10">
                <div class="flex justify-between border-b pb-8">
                    <h1 class="font-semibold text-2xl">Shopping Cart</h1>
                    <h2 class="font-semibold text-2xl">{{ countCartItems }} Items</h2>
                </div>
                <div class="flex mt-10 mb-5">
                    <h3 class="font-semibold text-gray-600 text-xs uppercase w-2/5">Product Details</h3>
                    <h3
                        class="font-semibold text-gray-600 text-xs uppercase w-1/5 text-center"
                    >Quantity</h3>
                    <h3
                        class="font-semibold text-gray-600 text-xs uppercase w-1/5 text-center"
                    >Price</h3>
                    <h3
                        class="font-semibold text-gray-600 text-xs uppercase w-1/5 text-center"
                    >Total</h3>
                </div>


                <product
                    v-for="item in cartItems"
                    :key="item.id"
                    :id="item.id"
                    :name="item.name"
                    :price="item.price"
                    :quantity="item.pivot.quantity"
                />

                <a href="#" class="flex font-semibold text-indigo-600 text-sm mt-10">
                    <svg class="fill-current mr-2 text-indigo-600 w-4" viewBox="0 0 448 512">
                        <path
                            d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"
                        />
                    </svg>
                    Continue Shopping
                </a>
            </div>

            <div id="summary" class="w-1/4 px-8 py-10">
                <h1 class="font-semibold text-2xl border-b pb-8">Order Summary</h1>
                <div class="flex justify-between mt-10 mb-5">
                    <span class="font-semibold text-sm uppercase">Items {{ countCartItems }}</span>
                    <span class="font-semibold text-sm">{{ rupiahFormat(totalCartItems) ?? "590$" }}</span>
                </div>
                <div>
                    <label class="font-medium inline-block mb-3 text-sm uppercase">Alamat</label>
                    <Editor v-model="value" editorStyle="height: 320px" />
                </div>
                <div>
                    <label class="font-medium inline-block mb-3 text-sm uppercase">Shipping</label>
                    <select class="block p-2 text-gray-600 w-full text-sm">
                        <option
                            v-for="exp in expeditionItems"
                            :key="exp.id"
                            :value="exp.id"
                        >{{ exp.name }}</option>
                    </select>
                </div>
                <div class="py-10">
                    <label
                        for="promo"
                        class="font-semibold inline-block mb-3 text-sm uppercase"
                    >Promo Code</label>
                    <input
                        type="text"
                        id="promo"
                        placeholder="Enter your code"
                        class="p-2 text-sm w-full"
                    />
                </div>
                <button
                    class="bg-red-500 hover:bg-red-600 px-5 py-2 text-sm text-white uppercase"
                >Apply</button>
                <div class="border-t mt-8">
                    <div class="flex font-semibold justify-between py-6 text-sm uppercase">
                        <span>Sub total</span>
                        <span>{{ rupiahFormat(totalCartItems) }}</span>
                    </div>
                    <button
                        class="bg-indigo-500 font-semibold hover:bg-indigo-600 py-3 text-sm text-white uppercase w-full"
                    >Checkout</button>
                </div>
            </div>
        </div>
    </div>-->
    <!-- {{ cartItems.map(value => value.pivot.quantity) }} -->
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
        Divider
    },
    setup() {
        const store = useStore();
        const page = usePage();

        const selectedProduct = ref([]);

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

        const rowUnselectAll = (event) => {
            store.dispatch("removeAllSelectedItems", event.data);
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



        // lifecycle
        onBeforeMount(() => {
            fetchExpedition()
            fetchCartItems()
            fetchUserAddress()
        })

        return {
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
        }
    },
}
</script>

<style scoped>
</style>
