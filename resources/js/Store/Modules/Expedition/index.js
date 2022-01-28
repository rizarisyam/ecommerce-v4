import axios from "axios";

const state = {
    expeditionItems: [],
};

const mutations = {
    UPDATE_EXPEDITION_ITEMS(state, payload) {
        state.expeditionItems = payload;
    },
};

const actions = {
    getExpeditionItems(context) {
        axios
            .get(route("api.expeditions.index"))
            .then((res) => {
                context.commit("UPDATE_EXPEDITION_ITEMS", res.data);
            })
            .catch((err) => {
                console.log(err);
            });
    },
};

const getters = {
    expeditionItems: (state) => {
        return state.expeditionItems;
    },
};

const expeditionModule = {
    state,
    mutations,
    actions,
    getters,
};

export default expeditionModule;
