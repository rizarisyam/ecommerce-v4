<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <Disclosure as="nav" class="bg-white shadow" v-slot="{ open }">
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 fixed top-0 z-20 bg-white border-b-2">
            <div class="flex justify-between items-center h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img
                            class="block lg:hidden h-8 w-auto"
                            src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg"
                            alt="Workflow"
                        />
                        <img
                            class="hidden lg:block h-8 w-auto"
                            src="https://tailwindui.com/img/logos/workflow-logo-indigo-500-mark-white-text.svg"
                            alt="Workflow"
                        />
                    </div>
                </div>

                <div class="flex-1">
                    <div v-if="!isCheckout" class="mt-1 relative rounded-md shadow-sm">
                        <input
                            type="text"
                            id
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md"
                            placeholder="Search"
                        />
                        <div
                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                        >
                            <SearchIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                        </div>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center gap-4">
                    <button
                        class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative"
                        @click="goToCarts"
                    >
                        <span class="sr-only">View notifications</span>

                        <ShoppingCartIcon class="h-6 w-6" aria-hidden="true" />
                        <span
                            class="absolute top-0 -right-2 bg-red-600 rounded-full text-white px-1 text-xs"
                        >{{ countCartItems }}</span>
                    </button>

                    <!-- Profile dropdown -->
                    <Menu v-if="user" as="div" class="ml-3 relative">
                        <div>
                            <MenuButton
                                class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <span class="sr-only">Open user menu</span>
                                <img
                                    class="h-8 w-8 rounded-full"
                                    src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                    alt
                                />
                            </MenuButton>
                        </div>
                        <transition
                            enter-active-class="transition ease-out duration-200"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <MenuItems
                                class="z-10 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                            >
                                <MenuItem v-slot="{ active }">
                                    <Link
                                        :href="route('account.index')"
                                        class="w-full flex"
                                        :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']"
                                        method="get"
                                        as="button"
                                        type="button"
                                    >My Account</Link>
                                </MenuItem>

                                <MenuItem v-slot="{ active }">
                                    <Link
                                        :href="route('logout')"
                                        class="w-full flex"
                                        :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']"
                                        method="post"
                                        as="button"
                                        type="button"
                                    >Logout</Link>
                                </MenuItem>
                            </MenuItems>
                        </transition>
                    </Menu>

                    <!-- login & register -->
                    <div v-if="!user" class="flex gap-3">
                        <Link :href="route('login')">
                            <Button label="Login" class="p-button-outlined" />
                        </Link>
                        <Link :href="route('register')">
                            <Button label="Register" />
                        </Link>
                    </div>
                </div>
                <div class="-mr-2 flex items-center sm:hidden">
                    <!-- Mobile menu button -->
                    <DisclosureButton
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                    >
                        <span class="sr-only">Open main menu</span>
                        <MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                        <XIcon v-else class="block h-6 w-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>
            </div>
        </div>

        <DisclosurePanel class="sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <!-- Current: "bg-indigo-50 border-indigo-500 text-indigo-700", Default: "border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700" -->
                <a
                    href="#"
                    class="bg-indigo-50 border-indigo-500 text-indigo-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                >Dashboard</a>
                <a
                    href="#"
                    class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                >Team</a>
                <a
                    href="#"
                    class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                >Projects</a>
                <a
                    href="#"
                    class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                >Calendar</a>
            </div>

            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <img
                            class="h-10 w-10 rounded-full"
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                            alt
                        />
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800">Tom Cook</div>
                        <div class="text-sm font-medium text-gray-500">tom@example.com</div>
                    </div>
                    <button
                        class="ml-auto flex-shrink-0 bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <span class="sr-only">View notifications</span>
                        <BellIcon class="h-6 w-6" aria-hidden="true" />
                    </button>
                </div>
                <div class="mt-3 space-y-1">
                    <a
                        href="#"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                    >Your Profile</a>
                    <a
                        href="#"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                    >Settings</a>
                    <a
                        href="#"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                    >Sign out</a>
                </div>
            </div>
        </DisclosurePanel>
    </Disclosure>
</template>

<script>
import { computed, onBeforeMount, ref } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { BellIcon, MenuIcon, XIcon } from '@heroicons/vue/outline'
import { QuestionMarkCircleIcon, ShoppingCartIcon, SearchIcon } from '@heroicons/vue/solid'

import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Badge from 'primevue/badge';

import { useStore } from 'vuex'
import { usePage } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
export default {
    components: {
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
        Menu,
        MenuButton,
        MenuItem,
        MenuItems,
        BellIcon,
        MenuIcon,
        XIcon,
        Link,
        Button,
        InputText,
        QuestionMarkCircleIcon,
        ShoppingCartIcon,
        SearchIcon,
        Badge
    },
    props: {
        isCheckout: Boolean
    },
    setup() {
        const open = ref(false)
        const store = useStore();
        const page = usePage();

        const user = page.props.value.auth.user;
        const fetchCart = () => {
            if (user) {
                store.dispatch('getCartItems', user.id ?? null)
            }
        }

        const goToCarts = () => {
            Inertia.visit(route('carts.index'))
        }

        // lifecycle
        onBeforeMount(() => {
            fetchCart()
        })
        return {
            open,
            countCartItems: computed(() => store.getters.countCartItems),
            goToCarts,
            user
        }
    },
}
</script>
