<template>
    <form class enctype="multipart/form-data" @submit.prevent="storeCustomer">
        <div class="flex items-center">
            <label class="w-40" for>Username</label>
            <input class="flex-1" type="text" v-model="customer.username" />
        </div>

        <div class="flex items-center mt-4">
            <label class="w-40" for>Name</label>
            <input class="flex-1" type="text" v-model="customer.name" />
        </div>

        <div class="flex items-center mt-4">
            <label class="w-40" for>Avatar</label>
            <input class="hidden" ref="inputFile" type="file" @change="onFileChange" />
            <div class="flex-1">
                <button
                    @click.prevent="onFile"
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mb-2"
                >Upload</button>
                <!-- <button @click.prevent="onFile">Upload</button> -->
                <div
                    v-if="fileSelected"
                    class="border-2 border-dashed border-gray-600 w-full h-20 px-2 py-3"
                >
                    <!-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p> -->
                    <img class="w-20 object-cover" :src="imagePreview" />
                </div>
            </div>
        </div>

        <div class="flex items-center mt-4">
            <label class="w-40" for>Phone Number</label>
            <input class="flex-1" type="text" v-model="customer.phone_number" />
        </div>

        <div class="flex items-center mt-4">
            <label class="w-40" for>Gender</label>
            <div class="flex-1 flex border items-center gap-2">
                <div class="flex gap-2 items-center">
                    <input class id="men" type="radio" value="laki-laki" v-model="customer.gender" />
                    <label for="men">Laki-laki</label>
                </div>

                <div class="flex gap-2 items-center">
                    <input class id="girl" type="radio" value="perempuan" v-model="customer.gender" />
                    <label for="girl">Perempuan</label>
                </div>
            </div>
        </div>

        <div class="flex items-center mt-4">
            <label class="w-40" for>Birthday</label>
            <input class="flex-1" type="date" @change="onBirthdayChange" />
        </div>
        <!-- {{ customer }} -->
        <div class="flex items-center mt-4">
            <label class="w-40" for></label>
            <button
                type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mb-2"
            >Submit</button>
        </div>
        <div></div>
    </form>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { computed, reactive, ref } from 'vue'
import { useStore } from 'vuex'
export default {

    setup(_, context) {

        const inputFile = ref(null);
        const fileSelected = ref();
        const imagePreview = ref();
        const loaded = ref(0);

        const store = useStore();


        const customer = reactive({
            username: null,
            name: null,
            phone_number: null,
            gender: null,
            date_of_birth: null
        })

        const onFile = () => {
            console.log(inputFile.value.click())
        }

        const onFileChange = (e) => {
            fileSelected.value = e.target.files[0];
            // console.log(e.target.files);
            const reader = new FileReader();
            reader.readAsDataURL(e.target.files[0]);

            reader.onload = function () {
                console.log(reader.result)
                loaded.value += 1;
                imagePreview.value = reader.result;
            }

            reader.onerror = function () {
                console.log(reader.error)
            }

            reader.onprogress = function (data) {
                if (data.lengthComputable) {
                    const progress = Number((data.loaded / data.total) * 100)
                    console.log(progress)
                }
                console.log(data)
            }

            // console.log(reader)
        }



        const storeCustomer = () => {
            const fd = new FormData();

            fd.append('avatar', fileSelected.value);
            fd.append('username', customer.username);
            fd.append('name', customer.name);
            fd.append('phone_number', customer.phone_number);
            fd.append('gender', customer.gender);
            fd.append('birthday', customer.date_of_birth);

            store.dispatch("addCustomer", fd);
            store.dispatch("fetchCustomer");


            // Inertia.post(route('api.customer.store'), fd, {
            //     forceFormData: true
            // });

            context.emit('closeModal');
            // context.emit('fetchData');



        }

        const onBirthdayChange = (e) => {
            customer.date_of_birth = e.target.value;
        }
        return {
            inputFile,
            onFile,
            onFileChange,
            fileSelected,
            customer,
            onBirthdayChange,
            storeCustomer,
            imagePreview
        }

    },
}
</script>
