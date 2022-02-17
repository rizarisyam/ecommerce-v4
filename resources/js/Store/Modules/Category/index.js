import axios from "axios";

const state = {
    categoryItems: [],
    categoryItem: {},
    isModalOpen: false,
};

const mutations = {
    UPDATE_CATEGORY_ITEMS(state, payload) {
        state.categoryItems = payload;
    },
    UPDATE_CATEGORY_ITEM(state, payload) {
        state.categoryItem = payload;
    },
    TOGGLE_MODAL(state, payload) {
        state.isModalOpen = payload;
    },
};

const actions = {
    getCategoryItems(context) {
        axios
            .get(route("api.categories.index"))
            .then((res) => {
                // console.log(res);
                context.commit("UPDATE_CATEGORY_ITEMS", res.data);
            })
            .catch((err) => console.log(err));
    },
    async storeCategory(_, payload) {
        console.log(payload);
        try {
            await axios.post(route("api.categories.store"), payload, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        } catch (error) {
            console.log(error.message);
        }
    },
    getCategoryById(context, payload) {
        axios
            .get(route("api.categories.show", payload))
            .then((res) => {
                console.log("getCategoryById", res);
                context.commit("UPDATE_CATEGORY_ITEM", res.data);
            })
            .catch((err) => console.log(err));
    },
    async destroyCategory(_, payload) {
        try {
            await axios.delete(route("api.categories.destroy", payload));
        } catch (error) {
            console.log(error.message);
        }
    },
};

const getters = {
    categoryItems: (state) => {
        return state.categoryItems;
    },
    categoryItem: (state) => {
        return state.categoryItem;
    },
    isModalOpen: (state) => state.isModalOpen,
};

const categoryModule = {
    state,
    mutations,
    actions,
    getters,
};

export default categoryModule;
