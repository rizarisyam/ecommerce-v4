import { createStore } from "vuex";
import Product from "./Modules/Product";
import Cart from "./Modules/Cart";

export default createStore({
    modules: {
        Product,
        Cart,
    },
});
