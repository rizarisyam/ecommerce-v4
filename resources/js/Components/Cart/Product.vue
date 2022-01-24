<template>
    <div
        class="flex items-center hover:bg-gray-100 -mx-8 px-6 py-5"

    >
        <div class="flex w-2/5">
            <!-- product -->
            <div class="w-20">
                <img
                    class="h-24"
                    src="https://drive.google.com/uc?id=18KkAVkGFvaGNqPy2DIvTqmUH_nk39o3z"
                    alt
                />
            </div>
            <div class="flex flex-col justify-between ml-4 flex-grow">
                <span class="font-bold text-sm">{{name }}</span>
                <span class="text-red-500 text-xs">Apple</span>
                <a href="javascript:void()" class="font-semibold hover:text-red-500 text-gray-500 text-xs" @click.prevent="deleteCartByItem">Remove</a>
            </div>
        </div>
        <div class="flex items-center justify-center w-1/5">
  <div class="inline-flex shadow-md hover:shadow-lg focus:shadow-lg" role="group">
    <button @click="decQty" :disabled="product.quantity <= 1" :class="{'bg-red-800': product.quantity <= 1}" type="button" class="inline-block px-2 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase focus:bg-blue-700 focus:outline-none focus:ring-0 active:bg-blue-800 transition duration-150 ease-in-out">
        <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" ><path d="M5 11h14v2H5z"></path></svg>
    </button>
   <input class="mx-2 border text-center w-12" type="text" v-model="product.quantity" />
    <button @click="incQty" type="button" class="inline-block px-2 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase focus:bg-blue-700 focus:outline-none focus:ring-0 active:bg-blue-800 transition duration-150 ease-in-out">
        <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" ><path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"></path></svg>
        </button>
  </div>
</div>

        <span
            class="text-center w-1/5 font-semibold text-sm"
        >{{ rupiahFormat(price) }}</span>
        <span class="text-center w-1/5 font-semibold text-sm">{{rupiahFormat(totalCartItem) }}</span>
    </div>
    <!-- {{product}} -->
</template>

<script>
import rupiahFormat from '@/Helper/rupiahFormat'
import { computed, reactive } from 'vue'
import {useStore} from 'vuex'
import {usePage} from '@inertiajs/inertia-vue3'

export default {
    props: ['id','name', 'price', 'quantity'],
    setup(props) {

        const page = usePage()
        const store = useStore()

        const product = reactive({
            id: props.id,
            name: props.name,
            price: props.price,
            quantity: props.quantity
        })
 const user = page.props.value.auth.user;
//     console.log(page.props.value.auth.user.id)
// store.dispatch('getCartItems', user_id);
        const deleteCartByItem = () => {
            // console.log(product.id);
            // alert(product.id)
            const productData = {
                userId: user.id,
                productId: product.id
            }
            store.dispatch('deleteCartByItem', productData);
             store.dispatch('getCartItems', user.id);
        }

        const incQty = () => {
            product.quantity += 1;
            const user_id = page.props.value.auth.user.id;
            const cartItem = {
                user_id,
                product_id: product.id,
                quantity: product.quantity,
                price: product.price
            }
            store.dispatch('addCartItem', cartItem)
            store.dispatch('getCartItems', user_id);
        }

        const decQty = () => {
            product.quantity -= 1;
            const user_id = page.props.value.auth.user.id;
            const cartItem = {
                user_id,
                product_id: product.id,
                quantity: product.quantity,
                price: product.price
            }
            store.dispatch('addCartItem', cartItem)
            store.dispatch('getCartItems', user_id);
        }

        const totalCartItem = computed(() => {
            return product.price * product.quantity
        })
        return {
            rupiahFormat,
            totalCartItem,
            product,
            incQty,
            decQty,
            deleteCartByItem
        }
    },
}
</script>
