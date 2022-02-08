<template>
    <div>
        <main>
            <section>Order ID</section>
            <section></section>
        </main>

        <main>
            <section>Expedition</section>
            <section></section>
        </main>

        <main>
            <section>Expedition Price</section>
            <section></section>
        </main>

        <main>
            <section>Expedition Receipt</section>
            <section></section>
        </main>

        <main>
            <section>Status</section>
            <section></section>
        </main>

        <main>
            <section>Attachment</section>
            <section></section>
        </main>
        {{ orderId }}
    </div>
</template>

<script>
import Card from 'primevue/card';
import { ref } from 'vue';
export default {
    props: {
        orderId: Number
    },
    components: {
        Card
    },
    setup(props) {
        const orderId = ref(props.orderId)
        console.log(props.orderId)
        return {
            orderId
        }
    },
}
</script>

