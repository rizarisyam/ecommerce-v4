<template>
    <Navbar />

    <div class="max-w-full px-4 sm:px-6 lg:px-8 mt-20 mx-32">
        <!-- We've used 3xl here, but feel free to try other max-widths based on your needs -->
        <div class="max-w-full mx-auto flex">
            <account-sidebar />
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
                        <main class="flex">
                            <section class="border-r flex-1">
                                <form action>
                                    <div class="flex">
                                        <label for class="w-32">Username</label>
                                        <p>{{ user.customer.username }}</p>
                                    </div>
                                    <div class="flex my-4 items-center">
                                        <label for class="w-32">Name</label>
                                        <InputText
                                            type="text"
                                            class="p-inputtext p-inputtext-sm"
                                            v-model="user.name"
                                        />
                                    </div>

                                    <div class="flex my-4 items-center">
                                        <label for class="w-32">Email</label>
                                        <InputText
                                            type="text"
                                            class="p-inputtext p-inputtext-sm"
                                            v-model="user.email"
                                        />
                                    </div>

                                    <div class="flex my-4 items-center">
                                        <label for class="w-32">Nomer Telepon</label>
                                        <InputText
                                            type="text"
                                            class="p-inputtext p-inputtext-sm"
                                            v-model="user.customer.phone_number"
                                        />
                                    </div>
                                    <div class="flex my-4 items-center gap-4">
                                        <label for class="w-32">Jenis Kelamin</label>
                                        <div class="-ml-4 flex gap-4 items-center">
                                            <div class="field-radiobutton flex items-center">
                                                <RadioButton
                                                    id="city1"
                                                    name="city"
                                                    value="laki-laki"
                                                    v-model="account.gender"
                                                    class="mr-2"
                                                />
                                                <label for="city1">Laki-laki</label>
                                            </div>

                                            <div class="field-radiobutton flex items-center">
                                                <RadioButton
                                                    id="city1"
                                                    name="city"
                                                    value="perempuan"
                                                    v-model="account.gender"
                                                    class="mr-2"
                                                />
                                                <label for="city1">Perempuan</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex my-4 items-center">
                                        <label for class="w-32">Tanggal Lahir</label>
                                        <Calendar
                                            id="dateformat"
                                            v-model="account.dateOfBirth"
                                            dateFormat="yy-mm-dd"
                                        />
                                    </div>

                                    <div class="ml-32">
                                        <Button class label="Submit" />
                                    </div>
                                </form>
                            </section>
                            <section class="mx-8">
                                <div class="flex flex-col items-center gap-4">
                                    <Image
                                        :src="account.avatar"
                                        alt="Image"
                                        width="250"
                                        imageClass="w-40 "
                                        preview
                                    />
                                    <FileUpload
                                        mode="basic"
                                        url="./upload.php"
                                        accept="image/*"
                                        :customUpload="true"
                                        :multiple="true"
                                        @uploader="onUpload"
                                    />
                                </div>
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
import AccountSidebar from "@/Components/User/AccountSidebar.vue";


import { usePage } from '@inertiajs/inertia-vue3'
import InputText from 'primevue/inputtext';
import RadioButton from 'primevue/radiobutton';
import Calendar from 'primevue/calendar';
import Image from 'primevue/image';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';
import { reactive } from "vue";

export default {
    components: {
        Navbar,
        Card,
        InputText,
        RadioButton,
        Calendar,
        Image,
        FileUpload,
        Button,
        AccountSidebar
    },
    props: {
        page: Object
    },
    setup(props) {
        const page = usePage();
        const user = page.props.value.auth.user;
        console.log(user)
        const account = reactive({
            gender: user.customer.gender,
            dateOfBirth: user.customer.date_of_birth,
            avatar: user.customer.avatar,
        });

        const onUpload = (event) => {
            console.log(event.files)
        }
        return {

            user,
            account,
            onUpload
        }
    },
}
</script>

<style scoped>
.p-inputtext {
    padding: 7px;
    font-size: 1.1rem;
}
</style>
