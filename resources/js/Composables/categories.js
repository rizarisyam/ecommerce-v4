import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import { ref, readonly } from "vue";
export function useCategories() {
    const categories = ref([]);
    const errors = ref(null);

    const fetchCategories = async () => {
        try {
            const response = await axios.get(route("api.categories.index"));
            console.log(response);
            categories.value = response.data;
            console.log("categories", categories.value);
        } catch (error) {
            errors.value = error.message;
        }
    };

    // const storeCustomer = async () => {
    //     try {
    //         await axios.post();
    //     } catch (error) {}
    // };

    const updateCategory = (payload) => {
        Inertia.post(route("categories.update", { id: payload.id }), payload, {
            forceFormData: true,
        });
    };
    return {
        categories,
        errors,
        fetchCategories,
        updateCategory,
    };
}
