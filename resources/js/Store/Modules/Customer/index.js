import axios from "axios";

const state = {
    customers: [],
};

const mutations = {
    SET_CUSTOMER(state, payload) {
        state.customers = payload;
    },
    ADD_CUSTOMER(state, payload) {
        state.customers.unshift(payload);
    },
};

const actions = {
    async fetchCustomer(context) {
        try {
            const response = await axios.get(route("api.customer.index"));
            console.log(response);
            context.commit("SET_CUSTOMER", response.data);
        } catch (error) {
            console.log(error.message);
        }
    },
    async addCustomer(context, payload) {
        try {
            const response = await axios.post(
                route("api.customer.store"),
                payload
            );
            console.log(response);
            context.commit("ADD_CUSTOMER", response.data);
        } catch (error) {
            console.log(error.message);
        }
    },
};

const getters = {
    getCustomers: (state) => {
        // const { data } = state.customers;
        return state.customers;
    },
};

const customerModule = {
    state,
    mutations,
    actions,
    getters,
};

export default customerModule;
