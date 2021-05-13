<template>
    <div class="mainApp">
        <h1>Welcome to <br> <b>CAIA</b> Application</h1>
        <p class="py-10">Please sign in to begin your session on ordering and billing system.</p>
        <form>
            <v-text-field label="Username"></v-text-field>
            <v-text-field class="pt-5" label="Password" type="password"></v-text-field>
            <v-btn type="submit" large class="mt-2" color="primary">Sign in</v-btn>
        </form>
    </div>
</template>

<script>

export default {
    name: "AndroidLogin",
}
</script>

<style scoped>
@font-face {
    font-family: "Bergen Sans";
    src: local("Bergen Sans"),
    url("../../assets/fonts/BergenSans.ttf") format("truetype");
}

* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    text-decoration: none;
    font-family: "Bergen Sans",sans-serif;
}
.mainApp{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 70px 0;
}
h1 {
    text-align: center;
    font-weight: 300;
}
form{
    width: 80%;
}
</style>
