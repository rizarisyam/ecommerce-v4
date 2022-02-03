import { ref } from "vue";

export default {
    setup(props, context) {
        const display = ref(false);

        const test = (event) => {
            console.log(event);
            // display.value = !display.value;
        };

        return {
            display,
            test,
        };
    },
};
