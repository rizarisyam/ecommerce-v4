<template>
    <div>
        <di>
            <label for>Name</label>
            <h3>{{ category[0] }}</h3>
            <!-- {{ category }} -->
        </di>
    </div>
</template>

<script>
import { reactive, ref } from "vue"

export default {
    props: ['category'],
    setup(props) {
        const category = ref(props.category)

        console.log(category.value)

    },
}
</script>
