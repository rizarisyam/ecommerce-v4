import axios from "axios";
import { ref } from "vue";

export function useCustomer() {
    const customers = ref([]);
    const customer = ref({});
    const errors = ref(null);

    const getAllCustomer = async () => {
        try {
            const response = await axios.get(route("api.customer.index"));
            // console.log(response);
            customers.value = response.data.data;
        } catch (err) {
            errors.value = err.message;
        }
    };

    const getCustomerById = async (id) => {
        try {
            const response = await axios.get(route("api.customer.show", id));
            customer.value = response.data.data;
        } catch (err) {
            errors.value = err.message;
        }
    };

    const destroyCustomer = async (id) => {
        await axios.delete(route("api.customer.destroy", id));
    };

    return {
        customers,
        customer,
        errors,
        getAllCustomer,
        getCustomerById,
        destroyCustomer,
    };
}
