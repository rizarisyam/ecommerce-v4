import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import { ref } from "vue";

export function useProduct() {
    const products = ref([]);

    const getProducts = async () => {
        try {
            const response = await axios.get(route("api.products.index"));
            console.log(response);
            products.value = response.data;
        } catch (error) {
            console.error(error.message);
        }
    };

    const storeProduct = (payload) => {
        Inertia.post(route("api"));
    };

    return {
        products,
        getProducts,
    };
}
