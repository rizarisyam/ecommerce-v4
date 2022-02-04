<template>
    <Navbar />

    <div class="max-w-full px-4 sm:px-6 lg:px-8 mt-20 mx-32">
        <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
        <div class="max-w-full mx-auto flex">
            <section class="w-96 px-4">
                <ul class="divide-y divide-gray-200">
                    <li v-for="person in people" :key="person.email" class="py-4 flex">
                        <img class="h-10 w-10 rounded-full" :src="person.image" alt />
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ person.name }}</p>
                            <p class="text-sm text-gray-500">{{ person.email }}</p>
                        </div>
                    </li>
                </ul>

                <ul class="border-2">
                    <li class="flex gap-4 items-center">
                        <span class="text-blue-700">
                            <svg
                                class="fill-current"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M7.5 6.5C7.5 8.981 9.519 11 12 11s4.5-2.019 4.5-4.5S14.481 2 12 2 7.5 4.019 7.5 6.5zM20 21h1v-1c0-3.859-3.141-7-7-7h-4c-3.86 0-7 3.141-7 7v1h17z"
                                />
                            </svg>
                        </span>
                        <h4>Akun Saya</h4>
                    </li>

                    <li class="flex gap-4 items-center my-2">
                        <h4 class="ml-10">Profil</h4>
                    </li>
                    <li class="flex gap-4 items-center my-2">
                        <h4 class="ml-10">Alamat</h4>
                    </li>
                </ul>
            </section>
            <section class="flex-1">
                <Card>
                    <template #title>
                        <div>
                            <h4>Profil Saya</h4>
                            <p
                                class="text-base font-normal"
                            >Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
                            <div class="bg-gray-500 border"></div>
                        </div>
                    </template>
                    <template #content>
                        <main>
                            <section class="border-r">
                                <form action>
                                    <div class="flex">
                                        <label for class="w-32">Username</label>
                                        <p>{{ user.name }}</p>
                                    </div>
                                </form>
                            </section>
                        </main>
                    </template>
                </Card>
            </section>
            <!-- Content goes here -->
        </div>
    </div>
</template>

<script>
import Navbar from "@/Components/Navbar.vue"
import Card from 'primevue/card';

const people = [
    {
        name: 'Calvin Hawkins',
        email: 'calvin.hawkins@example.com',
        image:
            'https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    }
]
import { usePage } from '@inertiajs/inertia-vue3'
export default {
    components: {
        Navbar,
        Card
    },
    props: {
        page: Object
    },
    setup(props) {
        const page = usePage();
        const user = page.props.value.auth.user;
        console.log(user)
        return {
            people,
            user
        }
    },
}
</script>
