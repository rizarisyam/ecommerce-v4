<template>
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

        <ul class>
            <li class="flex gap-4 items-center" @click="handleDropdown">
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

            <ul v-show="openDropdown">
                <li class="flex gap-4 items-center my-2">
                    <h4 class="ml-10">Profil</h4>
                </li>
                <li class="flex gap-4 items-center my-2">
                    <h4 class="ml-10">Alamat</h4>
                </li>
            </ul>

            <li
                class="flex gap-4 items-center my-2"
                :class="{ 'text-blue-600': route().current('user.purchase') }"
            >
                <span class="text-blue-700">
                    <svg
                        class="fill-current"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M19 4h-3V2h-2v2h-4V2H8v2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zM5 20V7h14V6l.002 14H5z"
                        />
                        <path d="M7 9h10v2H7zm0 4h5v2H7z" />
                    </svg>
                </span>
                <h4>Pesanan Saya</h4>
            </li>
        </ul>
    </section>
</template>

<script>
const people = [
    {
        name: 'Calvin Hawkins',
        email: 'calvin.hawkins@example.com',
        image:
            'https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
    }
]

import { ref } from 'vue'
export default {
    setup() {

        const openDropdown = ref(false)

        const handleDropdown = () => {
            openDropdown.value = !openDropdown.value;
        }
        return {
            people,
            openDropdown,
            handleDropdown
        }
    },
}
</script>
