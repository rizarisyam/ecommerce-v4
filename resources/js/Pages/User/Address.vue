<template>
    <navbar />

    <div class="max-w-full px-4 sm:px-6 lg:px-8 mt-20 mx-32">
        <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
        <div class="max-w-full mx-auto flex">
            <account-sidebar />

            <Card class="w-full">
                <template #title>
                    <div>
                        <div>
                            <h4>Alamat Saya</h4>
                        </div>
                    </div>
                </template>
                <template #content>
                    <main class="border mx-16">
                        <section>
                            <div class="flex gap-8">
                                <h5 class="text-gray-500 w-32">Nama Lengkap</h5>
                                <h3 class>Riza Nurfat Risyam</h3>
                            </div>
                            <div class="flex gap-8 my-4">
                                <h5 class="text-gray-500 w-32">Telepon</h5>
                                <h3 class>(+62) 82284908748</h3>
                            </div>
                            <div class="flex gap-8 my-4">
                                <h5 class="text-gray-500 w-32">Alamat</h5>
                                <h3 class="w-72">
                                    Jl. Ujung tanah RT. 03/09 No. 32 Kel. Lubuk Begalung Kec. Lubuk Begalung Padang
                                    KOTA PADANG - LUBUK BEGALUNG
                                    SUMATERA BARAT
                                    ID 25221
                                </h3>
                            </div>
                        </section>
                    </main>
                </template>
            </Card>
            <!-- Content goes here -->
        </div>
        <Toast />
    </div>
</template>

<script>
import Navbar from "@/Components/Navbar.vue"
import AccountSidebar from "@/Components/User/AccountSidebar.vue"
import Card from 'primevue/card';
export default {
    components: {
        Navbar,
        AccountSidebar,
        Card
    },
    setup() {

    },
}
</script>
