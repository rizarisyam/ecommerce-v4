<template>
    <Head title="Welcome" />
    <navbar />
    <div
        class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0"
    >
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
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
        </div>

        <div class="grid grid-cols-4 gap-4">
            <!-- component -->
            <product
                v-for="product in productItems"
                :key="product.id"
                :id="product.id"
                :name="product.name"
                :desc="product.desc"
                :price="product.price"
            />
        </div>
    </div>
    <!-- {{ products }} -->
</template>


<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import Product from '@/Components/Product/Item.vue';
import Navbar from '@/Components/Navbar.vue';
import { useStore } from 'vuex'
import { computed } from 'vue'

export default {
    components: {
        Head,
        Link,
        Product,
        Navbar
    },
    props: {
        // canLogin: Boolean,
        // canRegister: Boolean,
        // laravelVersion: String,
        // phpVersion: String,

        products: Object

    },
    setup(props) {
        const store = useStore();

        store.dispatch('getProductItems');
        // store.dispatch('getProductItems', props.products)
        // console.log(store);

        return {
            productItems: computed(() => store.getters.productItems),
        }
    }
}
</script>
