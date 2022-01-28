import axios from "axios";

const state = {
    productItems: [],
};

const mutations = {
    UPDATE_PRODUCT_ITEMS(state, payload) {
        state.productItems = payload;
    },
};

const actions = {
    getProductItems({ commit }) {
        axios
            .get(route("api.products.index"))
            .then((res) => {
                console.log(res);
                commit("UPDATE_PRODUCT_ITEMS", res.data);
            })
            .catch((err) => console.log(err));
    },
};

const getters = {
    productItems: (state) => state.productItems,
};

const productModule = {
    state,
    mutations,
    actions,
    getters,
};

export default productModule;
