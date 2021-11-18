<template>
<div>
    <v-alert
        v-for="(item,i) in items"
        :key="item.id"
        type="error"
        class="alertBox"
        dismissible
        max-width="520px"
        :style="{'bottom' : '0'}"
    >
        {{item.title}} has {{item.quantity}} piece/s left.
    </v-alert>
</div>
</template>

<script>
import axios from "axios";

export default {
    name: "Notification",
    data: () => ({
        items: []
    }),
    created(){
        axios.get('/api/notify')
        .then(res => {
            this.items = res.data
        })
    }
}
</script>

<style scoped>

</style>
