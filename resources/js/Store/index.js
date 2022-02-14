import { createStore } from "vuex";
import Product from "./Modules/Product";
import Cart from "./Modules/Cart";
import Category from "@/Store/Modules/Category";
import Expedition from "@/Store/Modules/Expedition";
import userAddress from "./Modules/UserAddress";
import customer from "./Modules/Customer";

export default createStore({
    modules: {
        Product,
        Cart,
        Category,
        Expedition,
        userAddress,
        customer,
    },
});
