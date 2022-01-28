import axios from "axios";

const state = {
    userAddress: [],
};

const mutations = {
    UPDATE_USER_ADDRESS(state, payload) {
        state.userAddress = payload;
    },
};

const actions = {
    getUserAddress({ commit }, payload) {
        console.log(payload);
        axios
            .get(route("api.user-address.index", payload))
            .then((res) => {
                console.log(res);
                commit("UPDATE_USER_ADDRESS", res.data);
            })
            .catch((err) => console.log(err));
    },
};

const getters = {
    userAddress: (state) => state.userAddress,
};

const userAddress = {
    state,
    mutations,
    actions,
    getters,
};

export default userAddress;
