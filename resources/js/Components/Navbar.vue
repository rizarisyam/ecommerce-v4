<template>
    <!-- component -->
    <nav class="bg-white shadow">
        <div class="container mx-auto px-6 py-3 md:flex md:justify-between md:items-center">
            <div class="flex justify-between items-center">
                <div>
                    <a
                        class="text-gray-800 text-xl font-bold md:text-2xl hover:text-gray-700"
                        href="#"
                    >Brand</a>
                </div>

                <!-- Mobile menu button -->
                <div class="flex md:hidden">
                    <button
                        type="button"
                        class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600"
                        aria-label="toggle menu"
                    >
                        <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current">
                            <path
                                fill-rule="evenodd"
                                d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
            <div class="md:flex items-center">
                <div class="flex flex-col md:flex-row md:mx-6">
                    <a
                        class="my-1 text-sm text-gray-700 font-medium hover:text-indigo-500 md:mx-4 md:my-0"
                        href="#"
                    >Home</a>
                    <a
                        class="my-1 text-sm text-gray-700 font-medium hover:text-indigo-500 md:mx-4 md:my-0"
                        href="#"
                    >Shop</a>
                    <a
                        class="my-1 text-sm text-gray-700 font-medium hover:text-indigo-500 md:mx-4 md:my-0"
                        href="#"
                    >Contact</a>
                    <a
                        class="my-1 text-sm text-gray-700 font-medium hover:text-indigo-500 md:mx-4 md:my-0"
                        href="#"
                    >About</a>
                </div>

                <div class="flex justify-center md:block">
                    <a
                        class="relative text-gray-700 hover:text-gray-600"
                        :href="route('carts.index')"
                    >
                        <svg
                            class
                            xmlns="http://www.w3.org/2000/svg"
                            width="40"
                            height="40"
                            viewBox="0 0 24 24"

                        >
                            <path
                                d="M21.822 7.431A1 1 0 0 0 21 7H7.333L6.179 4.23A1.994 1.994 0 0 0 4.333 3H2v2h2.333l4.744 11.385A1 1 0 0 0 10 17h8c.417 0 .79-.259.937-.648l3-8a1 1 0 0 0-.115-.921zM17.307 15h-6.64l-2.5-6h11.39l-2.25 6z"
                            />
                            <circle cx="10.5" cy="19.5" r="1.5" />
                            <circle cx="17.5" cy="19.5" r="1.5" />
                        </svg>

                        <span
                            class="absolute top-0 left-5 rounded-full bg-indigo-500 text-white p-1 text-xs"
                        >{{ countCartItems }}</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>

import { computed, onMounted } from 'vue';
import { useStore } from 'vuex'
import { usePage } from '@inertiajs/inertia-vue3'
export default {
    setup() {
        const store = useStore();
        const page = usePage();
        if(!page.props.value.auth ) {
            const userId = page.props.value.auth.user.id;
            store.dispatch('getCartItems', userId);
        }
        console.log(page.props.value.auth)
        // if(userId) {
        //     window.location('/login')
        // }





        return {
            qtyCart: computed(() => store.getters.qtyCart),
            countCartItems: computed(() => store.getters.countCartItems)
        }
    }
}
</script>
