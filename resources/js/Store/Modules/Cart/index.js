import axios from "axios";

const state = {
    cartItems: [],
    selectedItems: [],
};

const mutations = {
    UPDATE_CART_ITEMS(state, paylaod) {
        state.cartItems = paylaod;
    },
    UPDATE_SELECTED_ITEMS(state, payload) {
        state.selectedItems = payload;
    },
};

const actions = {
    getCartItems({ commit }, payload) {
        axios.get(`/api/carts?user_id=${payload}`).then((res) => {
            commit("UPDATE_CART_ITEMS", res.data);
        });
    },
    addCartItem({ commit }, paylaod) {
        axios.post("/api/carts", paylaod).then((res) => {
            commit("UPDATE_CART_ITEMS", res.data);
        });
    },
    deleteCartByItem({ commit }, payload) {
        console.log(payload);
        axios
            .delete(
                `api/carts/user/${payload.userId}?product_id=${payload.productId}`
            )
            .then((res) => {
                commit("UPDATE_CART_ITEMS", res.data);
            });
    },

    // selected items
    getSelectedItems(context, payload) {
        // console.log(context);
        // console.log(payload);
        context.commit("UPDATE_SELECTED_ITEMS", payload);
    },

    removeSelectedItems(context, payload) {
        context.commit("UPDATE_SELECTED_ITEMS", payload);
        // context.state.selectedItems.filter((value) => value.id !== payload.id);
    },

    removeAllSelectedItems(context, payload) {
        context.commit("UPDATE_SELECTED_ITEMS", payload);
    },
};

const getters = {
    cartItems: (state) => {
        return state.cartItems;
    },
    countCartItems: (state) => {
        return state.cartItems.length;
    },
    // qtyCart: (state) => {
    //     return state.cartItems.reduce((acc, item) => {
    //         return acc + item.pivot.quantity;
    //     }, 0);
    // },
    totalCartItems: (state) => {
        return state.cartItems.reduce((acc, item) => {
            return acc + item.pivot.quantity * item.pivot.price;
        }, 0);
    },
    selectedItems: (state) => {
        return state.selectedItems;
    },
    totalSelectedItems: (state) => {
        return state.selectedItems.reduce((acc, item) => {
            return acc + item.pivot.quantity * item.pivot.price;
        }, 0);
    },
    // subTotalSelectedItems: (state) => {
    //     return state.selectedItems.reduce(())
    // }
};

const cartModule = {
    state,
    mutations,
    actions,
    getters,
};

export default cartModule;
