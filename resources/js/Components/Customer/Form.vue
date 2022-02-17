<template>
    <div>
        <form
            class
            enctype="multipart/form-data"
            @submit.prevent="editMode ? updateCustomer() : storeCustomer()"
        >
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
                        <input
                            class
                            id="men"
                            type="radio"
                            value="laki-laki"
                            v-model="customer.gender"
                        />
                        <label for="men">Laki-laki</label>
                    </div>

                    <div class="flex gap-2 items-center">
                        <input
                            class
                            id="girl"
                            type="radio"
                            value="perempuan"
                            v-model="customer.gender"
                        />
                        <label for="girl">Perempuan</label>
                    </div>
                </div>
            </div>

            <div class="flex items-center mt-4">
                <label class="w-40" for>Birthday</label>
                <input
                    class="flex-1"
                    type="date"
                    :value="customer.birthday"
                    @change="onBirthdayChange"
                />
            </div>
            <!-- {{ customer }} -->
            <div class="flex items-center mt-4">
                <label class="w-40" for></label>
                <button
                    v-if="!editMode"
                    type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mb-2"
                >Create</button>
                <button
                    v-if="editMode"
                    type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mb-2"
                >Update</button>
            </div>
            <div></div>
            {{ customer }}
        </form>
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { computed, onBeforeMount, onMounted, reactive, ref } from 'vue'
import { useStore } from 'vuex'
import { useForm } from '@inertiajs/inertia-vue3';
import axios from 'axios';
import { useCustomer } from '@/Composables/customer';
export default {

    setup(_, context) {

        const inputFile = ref(null);
        const fileSelected = ref();
        const imagePreview = ref();
        const { getAllCustomer } = useCustomer()

        const store = useStore();
        // console.log(customer.value)

        const customer = ref({
            username: null,
            name: null,
            phone_number: null,
            gender: null,
            date_of_birth: null
        })

        // computed
        const isLoading = computed(() => store.getters.getIsLoading)
        const editMode = computed(() => store.getters.getEditMode);
        const customerByID = computed(() => store.getters.getCustomer);

        const onFile = () => {
            inputFile.value.click()
        }

        const handlerSubmitData = computed(() => {
            if (editMode.value) {
                console.log('editmode true')
                return updateCustomer()
            } else {
                console.log('editmode false')
                return storeCustomer()
            }
        })

        const onFileChange = (e) => {
            fileSelected.value = e.target.files[0];

            const reader = new FileReader();
            reader.readAsDataURL(e.target.files[0]);

            reader.onload = function () {
                imagePreview.value = reader.result;
            }

            reader.onerror = function () {
                console.log(reader.error)
            }

        }

        const storeCustomer = async () => {
            try {
                const fd = new FormData();

                fd.append('avatar', fileSelected.value);
                fd.append('username', customer.value.username);
                fd.append('name', customer.value.name);
                fd.append('phone_number', customer.value.phone_number);
                fd.append('gender', customer.value.gender);
                fd.append('birthday', customer.value.date_of_birth);

                await store.dispatch("addCustomer", fd);
                await store.dispatch("fetchCustomer");
                context.emit('closeModal');
            } catch (error) {
                console.log(error.message)
            }





            // Inertia.post(route('api.customer.store'), fd, {
            //     forceFormData: true
            // });
            // await getAllCustomer()
            // store.dispatch("fetchCustomer");


        }

        const editCustomer = () => {
            if (store.getters.getEditMode) {
                customer.value = customerByID.value
            }
        }

        const updateCustomer = async () => {
            try {
                const fd = new FormData();
                const id = customer.value.id;

                fd.append('id', customer.value.id);
                fd.append('avatar', fileSelected.value);
                fd.append('username', customer.value.username);
                fd.append('name', customer.value.name);
                fd.append('phone_number', customer.value.phone_number);
                fd.append('gender', customer.value.gender);
                fd.append('birthday', customer.value.birthday);

                const customerForm = useForm({
                    avatar: fileSelected.value,
                    username: customer.value.username,
                    name: customer.value.name,
                    phone_number: customer.value.phone_number,
                    gender: customer.value.gender,
                    birthday: customer.value.birthday
                })

                // customerForm.patch(`/api/customer/${id}`);
                await store.dispatch("updateCustomer", fd)
                await store.dispatch("fetchCustomer");
                context.emit('closeModal');
            } catch (error) {

            }

            // const id = customer.value.id;



            // fd.append('id', customer.value.id);
            // fd.append('avatar', fileSelected.value);
            // fd.append('username', customer.value.username);
            // fd.append('name', customer.value.name);
            // fd.append('phone_number', customer.value.phone_number);
            // fd.append('gender', customer.value.gender);
            // fd.append('birthday', customer.value.date_of_birth);


            // Inertia.patch(route('api.customer.update', id), customerForm)
            // store.dispatch("updateCustomer", data)
            // getAllCustomer()
            //

        }

        onBeforeMount(() => {
            editCustomer()
            customer.value;
        })

        onMounted(() => {
            if (editMode.value) {
                customer.value = { ...store.getters.getCustomer }
            }
        })

        const onBirthdayChange = (e) => {
            customer.value.date_of_birth = e.target.value;
        }
        return {
            handlerSubmitData,
            inputFile,
            onFile,
            onFileChange,
            fileSelected,
            customer,
            onBirthdayChange,
            storeCustomer,
            imagePreview,
            isLoading,
            customerByID,
            updateCustomer,
            editMode
        }

    },
}
</script>
