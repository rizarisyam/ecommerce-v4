import axios from "axios";

const state = {
    categoryItems: [],
    categoryItem: {},
};

const mutations = {
    UPDATE_CATEGORY_ITEMS(state, payload) {
        state.categoryItems = payload;
    },
    UPDATE_CATEGORY_ITEM(state, payload) {
        state.categoryItem = payload;
    },
};

const actions = {
    getCategoryItems(context) {
        axios
            .get(route("api.categories.index"))
            .then((res) => {
                console.log(res);
                context.commit("UPDATE_CATEGORY_ITEMS", res.data);
            })
            .catch((err) => console.log(err));
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
};

const getters = {
    categoryItems: (state) => {
        return state.categoryItems;
    },
    categoryItem: (state) => {
        return state.categoryItem;
    },
};

const categoryModule = {
    state,
    mutations,
    actions,
    getters,
};

export default categoryModule;
