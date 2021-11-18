<template>
    <div id="main">
        <sidebar/>
        <form class="myContainer" @submit.prevent="submit">
            <welcome :src="iconBlush" title="My profile Settings"/>
            <!-- <img :src="manIcon" class="manIcon" alt="manIcon" /> -->
            <!-- <img :src="welcomeIcon" class="welcomeIcon" alt="" /> -->
            <div class="mt-10">
                <div class="myInputGroup">
                    <h3>Company Information</h3>
                    <v-text-field v-model="form.company_name" :error-messages="form.errors.get('company_name')" label="Name"></v-text-field>
                    <v-text-field v-model="form.slogan" :error-messages="form.errors.get('slogan')" label="Slogan"></v-text-field>
                </div>
            </div>
            <hr class="my-4">
            <div class="mt-5">
                <div class="myInputGroup">
                    <h3>User Credential</h3>
                    <v-text-field v-model="form.username" :error-messages="form.errors.get('username')" label="Your username"></v-text-field>
                </div>
            </div>
            <hr class="my-4">
            <div class="mt-5">
                <div class="myInputGroup">
                    <h3>Password Settings</h3>
                    <v-text-field v-model="form.password" :error-messages="form.errors.get('password')" label="New Password" type="password"></v-text-field>
                    <v-text-field v-model="form.password_confirmation" label="Confirm password"
                                  type="password"></v-text-field>
                    <v-btn block color="success" type="submit">
                        Update Profile
                    </v-btn>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import Sidebar from "./base/sidebar.vue";
import iconBlush from "../assets/icons/iconBlush.svg";
import Welcome from "./base/welcome";
import axios from "axios";
import token from "../dev/token";
import Form from "../plugins/Form";

export default {
    components: {Welcome, Sidebar},
    data: () => ({
        iconBlush,
        form: new Form({
            username: "",
            password: "",
            company_name: "",
            slogan: "",
            password_confirmation: "",
            id: 0
        })
    }),
    created() {
        axios.get("/api/user", token()).then(r => {
            this.form.username = r.data.username
            this.form.id = r.data.id
        });

        axios.get("/api/company", token()).then(r => {
            this.form.company_name = r.data.company_name
            this.form.slogan = r.data.slogan
        });
    },
    methods: {
        submit() {
            this.form.errors.set({});
            axios.patch(`/api/user/${this.form.id}`, {
                username: this.form.username,
                password: this.form.password,
                password_confirmation: this.form.password_confirmation,
                company_name: this.form.company_name,
                slogan: this.form.slogan,
            }, token()).then(r => {
                Swal.fire({
                    icon: 'success',
                    title: 'Awesome!',
                    text: "Profile updated successfully.",
                })
            }).catch(err => {
                this.form.errors.set(err.response.data.errors)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: this.form.errors.has("username") ? this.form.errors.get("username") : this.form.errors.get("password"),
                })
            })
        }
    }
};
</script>

<style scoped>
.myInputGroup {
    max-width: 480px;
}
</style>
