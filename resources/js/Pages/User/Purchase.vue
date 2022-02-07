<template>
    <Navbar />

    <div class="max-w-full px-4 sm:px-6 lg:px-8 mt-20 mx-32">
        <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
        <div class="max-w-full mx-auto flex">
            <account-sidebar />
            <section class="flex-1">
                <div class="bg-white flex justify-between h-12">
                    <div
                        class="flex-1 flex justify-center items-center hover:text-blue-500 border-b-2 border-blue-700"
                    >Semua</div>
                    <div
                        class="flex-1 flex justify-center items-center hover:text-blue-500"
                    >Belum Bayar</div>
                    <div class="flex-1 flex justify-center items-center hover:text-blue-500">Dikemas</div>
                    <div class="flex-1 flex justify-center items-center hover:text-blue-500">Dikirim</div>
                    <div class="flex-1 flex justify-center items-center hover:text-blue-500">Selesai</div>
                    <div
                        class="flex-1 flex justify-center items-center hover:text-blue-500"
                    >Dibatalkan</div>
                </div>

                <Card class="py-0 my-4">
                    <template #title>
                        <div v-if="orders.attachment">
                            <Tag
                                class="mr-2 p-tag"
                                icon="pi pi-check"
                                severity="success"
                                value="Proses Verifikasi Pembayaran"
                            ></Tag>
                        </div>
                    </template>
                    <template #content>
                        <main
                            v-for="order in rowOrder"
                            :key="order.id"
                            class="flex justify-between"
                        >
                            <section class="flex gap-4 my-4">
                                <img
                                    class="w-24"
                                    src="https://images.unsplash.com/photo-1600185365926-3a2ce3cdb9eb?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1025&q=80"
                                    alt
                                />
                                <div>
                                    <h4>{{ order.name }}</h4>

                                    <div class="flex">
                                        <span class="text-xs text-black">
                                            <svg
                                                class="fill-current"
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
                                        <h1>{{ order.pivot.quantity }}</h1>
                                    </div>
                                </div>
                            </section>
                            <section>
                                <h4 class>{{ rupiahFormat(order.pivot.price) }}</h4>
                            </section>
                        </main>
                        <!-- <div class="border border-gray-700 my-2"></div> -->
                        <Divider />

                        <div class="flex justify-end items-center">
                            <h3 class="font-semibold">Total Pesanan : {{ rupiahFormat(subTotal) }}</h3>
                        </div>
                        <div class="my-4" v-if="!orders.attachment">
                            <form
                                class="flex flex-col gap-4"
                                @submit.prevent="submitAttachment(orderId)"
                                enctype="multipart/form-data"
                            >
                                <input
                                    type="file"
                                    @input="order.attachment = $event.target.files[0]"
                                />
                                <!-- <FileUpload
                                class="my-4"
                                mode="advanced"
                                accept="image/*"
                                chooseLabel="Upload Bukti Bayar"
                                :customUpload="true"
                                @uploader="onUpload($event)"
                                />-->
                                <Button class="w-fit" type="submit" label="Bayar" />
                            </form>
                        </div>
                    </template>
                </Card>
            </section>
            <!-- Content goes here -->
        </div>
    </div>
</template>

<script>

import Navbar from "@/Components/Navbar.vue"
import AccountSidebar from "@/Components/User/AccountSidebar.vue";
import Card from 'primevue/card';
import rupiahFormat from "@/Helper/rupiahFormat";
import Divider from 'primevue/divider';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';
import { computed, ErrorCodes, ref } from "vue";
import axios from "axios";
import Tag from 'primevue/tag';
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from "@inertiajs/inertia";
export default {
    components: {
        Navbar,
        AccountSidebar,
        Card,
        Divider,
        FileUpload,
        Button,
        Tag
    },
    props: {
        orderProducts: Object,
        orderId: Number,
        orders: Object
    },
    setup(props) {
        const rowOrder = ref(props.orderProducts);
        const orderId = ref(props.orderId);
        const orders = ref(props.orders)


        let attachment = ref("");
        const order = useForm({
            attachment: null
        })

        const onUpload = (event) => {


            order.attachment = event.target.files[0]

            // attachment.value = event.target.files[0];
        }

        const submitAttachment = (id) => {
            console.log(order)
            const fd = new FormData();
            fd.append('fileUpload', order.attachment)
            console.log(fd.getAll('fileUpload'))
            // order.patch(route('api.checkout.pay', id))

            Inertia.post(route('api.checkout.pay', id), {
                _method: 'patch',
                fileUpload: order.attachment
            })
            // axios.patch(route('api.checkout.pay', id), fd, {
            //     headers: {
            //         'content-type': 'multipart/form-data'
            //     }
            // })
            // Inertia.put(route('api.checkout.pay', id), fd)

        }

        const subTotal = computed(() => {
            return rowOrder.value.reduce((acc, curr) => {
                return acc + curr.pivot.quantity * curr.pivot.price
            }, 0)
        })
        return {
            rowOrder,
            rupiahFormat,
            subTotal,
            onUpload,
            orderId,
            order,
            submitAttachment,
            attachment,
            orders

        }
    },
}
</script>

<style scoped>
.p-card {
    padding: 10px 0;
}

.p-tag {
    padding: 10px;
}
</style>

