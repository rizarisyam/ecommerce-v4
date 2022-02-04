

<template>
    <navbar :is-checkout="true" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-28">
        <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
        <div class="max-w-6xl mx-auto flex flex-col gap-8">
            <Card>
                <template #title>
                    <div class="flex items-center gap-2">
                        <span>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 14c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4zm0-6c1.103 0 2 .897 2 2s-.897 2-2 2-2-.897-2-2 .897-2 2-2z"
                                />
                                <path
                                    d="M11.42 21.814a.998.998 0 0 0 1.16 0C12.884 21.599 20.029 16.44 20 10c0-4.411-3.589-8-8-8S4 5.589 4 9.995c-.029 6.445 7.116 11.604 7.42 11.819zM12 4c3.309 0 6 2.691 6 6.005.021 4.438-4.388 8.423-6 9.73-1.611-1.308-6.021-5.294-6-9.735 0-3.309 2.691-6 6-6z"
                                />
                            </svg>
                        </span>
                        <h3 class="text-lg">Alamat Pengiriman</h3>
                    </div>
                </template>
                <template #content>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore sed consequuntur error repudiandae numquam deserunt
                        quisquam repellat libero asperiores earum nam nobis, culpa ratione quam perferendis esse, cupiditate neque quas!
                    </p>
                </template>
            </Card>

            <Card>
                <template #content>
                    <header class="flex justify-between mb-6">
                        <div class="flex-1 text-lg font-semibold">Produk Dipesan</div>
                        <div class="flex-1 text-gray-500">Harga Satuan</div>
                        <div class="flex-1 text-gray-500">Jumlah</div>
                        <div class="flex-1 text-gray-500">Subtotal Produk</div>
                    </header>
                    <section v-for="item in order" :key="item.id">
                        <section
                            v-for="product in item.products"
                            :key="product.id"
                            class="flex justify-between"
                        >
                            <div class="flex-1 flex gap-4 items-center">
                                <Image
                                    image-class="w-20 object-cover"
                                    src="https://images.unsplash.com/photo-1600185365926-3a2ce3cdb9eb?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1025&q=80"
                                    alt="Image"
                                    width="250"
                                    preview
                                />
                                <h4>{{ product.name }}</h4>
                            </div>
                            <div
                                class="flex-1 flex items-center"
                            >{{ rupiahFormat(product.pivot.price) }}</div>
                            <div class="flex-1 flex items-center">{{ product.pivot.quantity }}</div>
                            <div
                                class="flex-1 flex items-center"
                            >{{ product.pivot.price * product.pivot.quantity }}</div>
                        </section>
                    </section>

                    <div class="w-40 mt-8">
                        <label
                            for="location"
                            class="block text-sm font-medium text-gray-700"
                        >Expedisi</label>
                        <select
                            id="location"
                            name="location"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            v-model="expeditionId"
                        >
                            <option
                                v-for="item in expeditions"
                                :key="item.id"
                                :value="item.id"
                            >{{ item.name }}</option>
                        </select>
                    </div>
                </template>
                <template #footer>
                    <div class="flex justify-end p-2">
                        <span
                            class="text-lg font-semibold"
                        >Total pesanan({{ countShipment }}) : {{ rupiahFormat(subTotal(order)) }}</span>
                    </div>
                    <div class="flex justify-end mt-4">
                        <Button @click="updateShipment" label="Pesan" class="w-80" />
                    </div>
                </template>
            </Card>
        </div>
    </div>
</template>

<script>
import { computed, onBeforeMount, ref } from "vue"

import { usePage } from '@inertiajs/inertia-vue3'
import { useStore } from 'vuex'
import Image from 'primevue/image';
import rupiahFormat from "@/Helper/rupiahFormat";

import Navbar from "@/Components/Navbar.vue"
import Card from 'primevue/card';
import Button from 'primevue/button';
import axios from 'axios';

export default {
    name: "Shipment",
    components: {
        Navbar,
        Card,
        Image,
        Button
    },
    setup(props) {
        // const order = ref(props.order);
        const page = usePage();
        const store = useStore();

        const user = page.props.value.auth.user;
        const expeditionId = ref();

        const fetchDataOrder = () => {
            store.dispatch('getShipmentItems', { user_id: user.id });
        }

        const updateShipment = () => {
            const data = {
                user_id: user.id,
                expedition_id: expeditionId.value
            }
            axios.put(`/api/carts/shipment`, data)
                .then(res => console.log(res))
                .catch(err => console.log(err))
        }

        const fetchDatExpedition = () => {
            store.dispatch("getExpeditionItems");
        }

        onBeforeMount(() => {
            fetchDataOrder()
            fetchDatExpedition()
        })

        const order = computed(() => store.getters.shipmentItems)
        const expeditions = computed(() => store.getters.expeditionItems)

        const subTotal = (arr) => {
            return arr.map((value) => {
                return value.products.reduce((acc, item) => {
                    return acc + item.pivot.price * item.pivot.quantity
                }, 0);
            }).reduce((acc, item) => {
                return acc + item
            }, 0)

        }


        return {
            order,
            subTotal,
            rupiahFormat,
            expeditions,
            expeditionId,
            updateShipment,
            countShipment: computed(() => store.getters.countShipment)
        }
    }
}
</script>




