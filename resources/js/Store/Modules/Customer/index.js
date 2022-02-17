import { Inertia } from "@inertiajs/inertia";
import axios from "axios";

const state = {
    dataCustomers: [],
    customer: {},
    editMode: false,
    isLoading: false,
};

const mutations = {
    UPDATE_CUSTOMERS(state, payload) {
        state.dataCustomers = payload;
    },
    UPDATE_CUSTOMER(state, payload) {
        state.customer = payload;
    },
    TOGGLE_EDITMODE(state, payload) {
        state.editMode = payload;
    },
    TOGLE_ISLOADING(state, payload) {
        state.isLoading = payload;
    },
};

const actions = {
    async fetchCustomer(context) {
        try {
            const response = await axios.get(route("api.customer.index"));
            console.log("fetch", response);
            context.commit("UPDATE_CUSTOMERS", response.data);
        } catch (error) {
            console.error(error.message);
        }
    },
    async addCustomer(_, payload) {
        try {
            const response = await axios.post(
                route("api.customer.store"),
                payload,
                {
                    headers: {
                        "Content-Type": "multipart/form",
                    },
                }
            );
            console.log(response);
            // if (response.status === 200) {
            //     context.commit("UPDATE_CUSTOMERS", response.data);
            // }
        } catch (error) {
            console.error(error.message);
        }
    },

    async updateCustomer(_, payload) {
        // console.log(payload.get("id"));
        console.log(payload);
        try {
            const response = await axios.patch(
                route("api.customer.update", { id: payload.id }),
                {
                    username: payload.username,
                    user_id: payload.user_id,
                    name: payload.name,
                    phone_number: payload.phone_number,
                    gender: payload.gender,
                    birthday: payload.birthday,
                    avatar: payload.avatar,
                },
                {
                    headers: {
                        "Content-Type": "multipart/form",
                    },
                }
            );
            console.log(response);
            // Inertia.patch(
            //     route("api.customer.update", { id: payload.get("id") }),
            //     payload
            // );

            // if (response.status === 200) {
            //     context.commit("UPDATE_CUSTOMERS", response.data);
            // }
        } catch (error) {
            console.error(error.message);
        }
    },

    async getCustomerById(context, payload) {
        context.commit("TOGLE_ISLOADING", true);
        try {
            const response = await axios.get(
                route("api.customer.show", payload)
            );

            if (response.status === 200) {
                // context.commit("")
                context.commit("UPDATE_CUSTOMER", response.data);
                context.commit("TOGLE_ISLOADING", false);
            }
        } catch (error) {
            console.error(error.message);
        }
    },
};

const getters = {
    getCustomers: (state) => {
        return state.dataCustomers;
    },
    getCustomer: (state) => {
        return state.customer;
    },
    getEditMode: (state) => {
        return state.editMode;
    },
    getIsLoading: (state) => {
        return state.isLoading;
    },
};

const customerModule = {
    state,
    mutations,
    actions,
    getters,
};

export default customerModule;
