<template>
    <div class="bg-gray-50">
        <Head title="Welcome" />

        <navbar />

        <cta-section class="mt-20" />

        <!-- <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="text-sm text-gray-700 underline"
            >Dashboard</Link>

            <template v-else>
                <Link :href="route('login')" class="text-sm text-gray-700 underline">Log in</Link>

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 text-sm text-gray-700 underline"
                >Register</Link>
            </template>
        </div>-->
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 my-4">
            <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
            <div class="max-w-full mx-auto">
                <!-- Content goes here -->

                <div class="my-8">
                    <h4 class="px-14 font-semibold text-xl mb-4">Category</h4>
                    <Carousel :value="categoryItems" :numVisible="8" :numScroll="3">
                        <template #item="slotProps">
                            <div class="w-40">
                                <img
                                    class="rounded"
                                    :src="slotProps.data.image_path"
                                    alt="Sunset in the mountains"
                                />

                                <div class="flex justify-center">
                                    <div
                                        class="text-sm font-light my-2 text-gray-700"
                                    >{{ sliceString(slotProps.data.name) }}</div>
                                </div>
                            </div>
                        </template>
                    </Carousel>
                </div>
            </div>
        </div>

        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 my-4">
            <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
            <div class="max-w-full mx-auto">
                <!-- Content goes here -->

                <Carousel :value="productItems" :numVisible="6" :numScroll="3">
                    <template #item="slotProps">
                        <div class="max-w-sm h-80 rounded overflow-hidden shadow-lg border-2 mr-4">
                            <img
                                class="w-full h-40 object-cover"
                                src="https://images.unsplash.com/photo-1494726161322-5360d4d0eeae?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=334&q=80"
                                alt="Sunset in the mountains"
                            />
                            <div class="px-2 py-4">
                                <div
                                    class="text-gray-900 text-base mb-2 antialiased"
                                >{{ slotProps.data.name }}</div>
                                <p
                                    class="text-gray-700 font-semibold text-base"
                                >{{ rupiahFormat(slotProps.data.price) }}</p>
                            </div>
                            <div class="flex justify-end px-2">
                                <Button
                                    label="Add to cart"
                                    icon="pi pi-shopping-cart"
                                    iconPos="left"
                                    class="p-button-sm"
                                    @click="addToCart({ productId: slotProps.data.id, price: slotProps.data.price })"
                                />
                            </div>
                        </div>
                    </template>
                </Carousel>
            </div>
        </div>

        <!-- {{ products }} -->
    </div>
    <Footer />

    <Toast position="top right" />
</template>


<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import CtaSection from '@/Components/CtaSection.vue';
import { useStore } from 'vuex'
import { computed, onBeforeMount } from 'vue'

import Panel from 'primevue/panel';
import Button from 'primevue/button';
import Divider from 'primevue/divider';
import Card from 'primevue/card';
import Carousel from 'primevue/carousel';
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";

import rupiahFormat from '@/Helper/rupiahFormat';
import { usePage } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia';
export default {
    components: {
        Head,
        Link,
        Navbar,
        Panel,
        Button,
        Divider,
        Card,
        Carousel,
        Footer,
        CtaSection,
        Toast
    },
    props: {
        // canLogin: Boolean,
        // canRegister: Boolean,
        // laravelVersion: String,
        // phpVersion: String,

        // products: Object

    },
    setup(props) {
        const store = useStore();
        const page = usePage()

        const user = page.props.value.auth.user;

        const toast = useToast();

        const fetchDataProduct = () => {
            store.dispatch('getProductItems');
        }

        const fetchDataCategory = () => {
            store.dispatch("getCategoryItems");
        }

        const showImage = () => {
            return "/storage/";
        }



        const addToCart = ({ productId, price }) => {
            if (!user) {
                return Inertia.visit(route('login'))
            }
            const cartItem = {
                user_id: user.id,
                product_id: productId,
                quantity: 1,
                price: price
            }
            store.dispatch('addCartItem', cartItem)
            store.dispatch('getCartItems', user.id);

            toast.add({ severity: 'success', summary: 'Info Message', detail: 'Message Content', life: 3000 });
        }


        const sliceString = (string) => {
            return string.slice(0, 10);
        }

        // lifecycle
        onBeforeMount(() => {
            fetchDataProduct()
            fetchDataCategory()
        })

        return {
            productItems: computed(() => store.getters.productItems),
            categoryItems: computed(() => store.getters.categoryItems),
            showImage,
            sliceString,
            rupiahFormat,
            addToCart
        }
    }
}
</script>

<style>
</style>


