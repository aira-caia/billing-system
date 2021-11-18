<template>
    <div class="d-flex">
        <div class="leftPanel">
            <div class="title">
                <h3 class="logo">{{company_name}}</h3>
                <sub>{{slogan}}</sub>
            </div>
            <img :src="sally" alt="Girl With Phone"/>
        </div>
        <div class="rightPanel" style="position: relative">
            <div class="signInBox">
                <v-btn @click="$router.push({name: 'about'})" style="position: absolute; top: -45px; left: 40px">About</v-btn>
                <v-btn color="#ab97f3" dark style="position: absolute; top: -45px; left: 150px">Download <v-icon>mdi-download</v-icon></v-btn>
                <img class="handIcon" :src="sally8" alt="Hand"/>
                <img class="phoneIcon" :src="sally12" alt="Hand"/>
                <div class="title">
                    <h2>Sign in</h2>
                    <sub>Sign in now to manage your restaurant!</sub>
                </div>
                <form @submit.prevent="submit">
                    <div class="inputGroup">
                        <label for="username">USERNAME</label>
                        <input
                            type="text"
                            v-model="form.username"
                            id="username"
                            placeholder="..."
                        />
                    </div>
                    <div class="inputGroup">
                        <label for="password">PASSWORD</label>
                        <input
                            type="password"
                            v-model="form.password"
                            id="password"
                            placeholder="..."
                        />
                    </div>
                    <button type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import sally from "../assets/icons/Saly-14.svg";
import sally8 from "../assets/icons/Saly-8.svg";
import sally12 from "../assets/icons/Saly-12.svg";
import Form from "../plugins/Form";
import axios from 'axios'

export default {
    name: "Login",
    data: () => ({
        sally,
        sally8,
        sally12,
        form: new Form({
            username: "",
            password: "",
        }),
        company_name: '',
        slogan: ''
    }),
    created() {
        axios.get("/api/company").then(res => {
            this.company_name = res.data.company_name
            this.slogan = res.data.slogan
            console.log(res)
        })
    },
    methods: {
        async submit() {
            await this.form
                .post("/api/login")
                .then((r) => {
                    localStorage.setItem("token", r.token)
                    this.$router.push({name: "home"})
                })
                .catch((err) => {
                    this.form.errors.set(err.errors);
                    let message = "";
                    if (this.form.errors.has("username")) {
                        message = this.form.errors.get("username");
                    } else if (this.form.errors.has("password")) {
                        message = this.form.errors.get("password");
                    } else {
                        message = err.message;
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: message,
                    });
                });
        },
    },
};
</script>

<style scoped>
@font-face {
    font-family: "Bergen Sans";
    src: local("Bergen Sans"),
    url("../assets/fonts/BergenSans.ttf") format("truetype");
}

* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    text-decoration: none;
    font-family: "Bergen Sans";
}

@media screen and (min-width: 720px) {
    .leftPanel {
        width: 50%;
        height: 100vh;
        background-color: #e6ebfe;
        overflow: hidden;
    }

    .logo {
        font-weight: bold;
        font-size: 55px;
        font-family: "Bergen Sans", sans-serif;
        color: #ab97f3;
    }

    .leftPanel .title sub {
        font-family: "Bergen Sans", sans-serif;
        font-size: 1.2rem;
    }

    .leftPanel .title {
        padding: 40px 50px;
    }

    .leftPanel img {
        margin: 0 50px;
    }

    .rightPanel {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 50%;
    }

    .signInBox {
        background: #ab97f3;
        min-width: 562px;
        color: #fff;
        padding: 50px 40px;
        border-radius: 30px;
        position: relative;
    }

    .signInBox .title {
        margin-bottom: 90px;
    }

    .rightPanel .inputGroup {
        display: flex;
        flex-direction: column;
        margin: 10px 0 25px 0;
    }

    .rightPanel .inputGroup input {
        font-size: 24px;
        color: #fff;
        margin-top: 7px;
        outline: none;
        border: none;
        background: none;
    }

    .rightPanel .inputGroup input:focus {
        border-bottom: 1.5px solid #fff;
    }

    .rightPanel form button {
        width: 165px;
        background: #f87193;
        height: 45px;
        border-radius: 55px;
        outline: none;
    }

    .rightPanel .handIcon {
        position: absolute;
        bottom: 0;
        right: 0;
    }

    .rightPanel .phoneIcon {
        position: absolute;
        top: -80px;
        right: 0;
    }
}
</style>
