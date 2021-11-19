<template>
  <v-app>
    <nav
      class="
        navbar navbar-expand-lg navbar-dark
        bg-dark
        sticky-top
        d-flex
        justify-content-end
        py-4
      "
    >
      <div id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <v-btn text dark @click="$router.push({ name: 'login' })"
              >Sign In</v-btn
            >
          </li>
          <li class="nav-item">
            <v-btn href="./caia.apk" color="#ab97f3" class="ml-3" dark
              >Download
              <v-icon>mdi-download</v-icon>
            </v-btn>
          </li>
        </ul>
      </div>
    </nav>

    <v-card
      flat
      min-height="470px"
      class="
        position-relative
        overflow-hidden
        text-center
        bg-light
        d-flex
        flex-column
        justify-center
      "
    >
      <div class="col-md-7 p-lg-5 mx-auto">
        <h1 class="display-1 font-weight-normal">
          ANDROID BASED ORDERING AND BILLING SYSTEM WITH QR CODE
        </h1>
        <p class="lead font-weight-normal">
          A web and Android application that enables trouble-free transactions
          between consumers and restaurant owners.
        </p>
        <a class="btn btn-outline-secondary" href="./caia.apk">Try it now!</a>
      </div>
      <div class="product-device box-shadow d-none d-md-block"></div>
      <div
        class="product-device product-device-2 box-shadow d-none d-md-block"
      ></div>
    </v-card>

    <div class="row m-4">
      <div class="col-sm-6 bg-dark text-center text-white overflow-hidden">
        <div class="my-3 py-3">
          <h2 class="display-5">Web Application</h2>
          <p class="lead">The Maintenance of the application.</p>
        </div>
        <div
          class="box-shadow mx-auto overflow-hidden reveal1"
          :style="{ 'background-image': 'url(' + web + ')' }"
          style="
            width: 80%;
            height: 300px;
            border-radius: 21px 21px 0 0;
            background-size: cover;
            background-position: left center;
          "
        >
          <!--                    <img :src="web" style="background-size: cover; background-position: center; width: auto; height: 100%" alt="">-->
        </div>
      </div>
      <div class="col-sm-6 text-center overflow-hidden">
        <div class="my-3 py-3">
          <h2 class="display-5">Android Application</h2>
          <p class="lead">The device that interacts with customers.</p>
        </div>
        <div
          class="bg-dark box-shadow mx-auto overflow-hidden reveal1"
          :style="{ 'background-image': 'url(' + androidImg + ')' }"
          style="
            width: 80%;
            height: 300px;
            border-radius: 21px 21px 0 0;
            background-size: cover;
            background-position: center;
          "
        ></div>
      </div>
    </div>

    <v-divider class="mx-15 my-5" />

    <v-card flat min-height="450px" class="row d-flex align-center pa-8">
      <div class="col-sm-7 px-md-12">
        <h2 class="display-3 font-weight-black">Our focus</h2>
        <p style="font-size: 1.2rem">
          The goal of this project is to offer the food industry a food ordering
          system using a tablet and a billing system using QR codes to solve the
          high chance of human error when a restaurant's workers negotiate with
          a huge number of customers in the conventional manner.
        </p>
        <v-btn large href="#footer" color="primary">
          <v-icon left>mdi-phone</v-icon>
          Contact us
        </v-btn>
      </div>
      <div class="col-sm-5 pa-5">
        <img
          class="border-bottom border-warning rounded-lg elevation-2 reveal2"
          :src="chartImg"
          style="
            background-size: cover;
            background-position: center;
            width: auto;
            height: 550px;
          "
          alt=""
        />
      </div>
    </v-card>

    <v-card flat min-height="450px" class="row d-flex align-center pa-8">
      <div class="col-sm-5 pa-5 reveal3">
        <img
          class="border-bottom border-warning rounded-lg elevation-2 leftImage"
          :src="web"
          alt=""
        />
      </div>
      <div class="col-sm-7 px-md-12">
        <h2 class="display-3 font-weight-black">The Benefits</h2>
        <p style="font-size: 1.2rem">
          The Android-based Ordering and Billing System with QR Code enhances
          the customer experience significantly. Furthermore, the restaurant's
          quality in food service, daily operations, and customer interaction
          would be enhanced.
        </p>
        <v-btn href="#" large color="primary">
          <v-icon left>mdi-set-center</v-icon>
          Join us
        </v-btn>
      </div>
    </v-card>

    <v-divider class="mx-15 my-5" />

    <div class="container">
      <div class="row">
        <div class="col-md-7 d-flex align-start flex-column justify-center">
          <h2 class="">
            What we’re about.
            <span class="text-muted">For your information.</span>
          </h2>
          <p class="lead">
            The Android-based Ordering and Billing System with QR Code is a web
            and Android application that enables trouble-free transactions
            between consumers and restaurant owners. It will enable customers to
            easily choose items from the menu and pay their bills using the
            tablet on their table. Along with the fact that customers would not
            need to wait for waiters to take their orders, it makes it an
            excellent option for people with little free time.
          </p>
        </div>
        <div class="col-md-5">
          <div
            class="reveal2 rounded-lg elevation-2"
            style="height: 500px; width: 500px; overflow: hidden"
          >
            <img
              style="
                height: 100%;
                width: auto;
                background-size: contain;
                background-position: center;
              "
              :src="web"
            />
          </div>
        </div>
      </div>
    </div>

    <v-divider class="mx-15" />

    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
      <h1 class="display-2 font-weight-black">Our Team</h1>
      <p class="lead" style="max-width: 620px">
        "Teamwork begins by building trust. And the only way to do that is to
        overcome our need for invulnerability." – Patrick Lencioni
      </p>
    </div>

    <div class="container" id="ourTeam">
      <div class="row">
        <div class="col-sm-3 reveal4">
          <v-img
            src="https://joeschmoe.io/api/v1/female/ivy"
            :lazy-src="`https://joeschmoe.io/api/v1/random`"
            aspect-ratio="1"
            class="grey lighten-2"
            max-width="300px"
          />
          <h5>Ivy Recinte</h5>
          <p class="text-muted">Analyst</p>
        </div>
        <div class="col-sm-3 reveal4">
          <v-img
            src="https://joeschmoe.io/api/v1/female/Aira"
            :lazy-src="`https://joeschmoe.io/api/v1/random`"
            aspect-ratio="1"
            class="grey lighten-2"
            max-width="300px"
          />
          <h5>Michaela Aira Salenga</h5>
          <p class="text-muted">Programmer</p>
        </div>
        <div class="col-sm-3 reveal4">
          <v-img
            src="https://joeschmoe.io/api/v1/male/ace"
            :lazy-src="`https://joeschmoe.io/api/v1/random`"
            aspect-ratio="1"
            class="grey lighten-2"
            max-width="300px"
          />
          <h5>Ace Sangalang</h5>
          <p class="text-muted">Tester & Documentation</p>
        </div>
        <div class="col-sm-3 reveal4">
          <v-img
            src="https://joeschmoe.io/api/v1/female/cathlyn"
            :lazy-src="`https://joeschmoe.io/api/v1/random`"
            aspect-ratio="1"
            class="grey lighten-2"
            max-width="300px"
          />
          <h5>Cathlyn Serrano</h5>
          <p class="text-muted">Designer</p>
        </div>
      </div>
    </div>

    <div class="container">
      <footer
        class="
          d-flex
          flex-wrap
          justify-content-between
          align-items-center
          py-3
          my-4
          border-top
        "
      >
        <div class="col-md-4 d-flex align-items-center">
          <a
            href="/"
            class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1"
          >
            <svg class="bi" width="30" height="24">
              <use xlink:href="#bootstrap" />
            </svg>
          </a>
          <span class="text-muted">&copy; 2021-2022 CAIA</span>
        </div>

        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
          <li class="ms-3">
            <v-icon @click="goToFacebook">mdi-facebook</v-icon>
          </li>
          <li class="ms-3">
            <v-icon @click="goToTwitter">mdi-twitter</v-icon>
          </li>
        </ul>
      </footer>

      <p class="text-right" id="footer">
        <v-icon small>mdi-phone</v-icon> Contact us:
        <strong>091-0015-9347</strong>
      </p>
    </div>
  </v-app>
</template>

<script>
import web from "../assets/web.png";
import chartImg from "../assets/chart2.png";
import chartImg2 from "../assets/chart.png";
import welcomeImg from "../assets/Welcome.png";
import androidImg from "../assets/Android.png";

export default {
  name: "About",
  data: () => ({
    web,
    chartImg,
    chartImg2,
    welcomeImg,
    androidImg,
  }),
  methods: {
    goToTwitter() {
      location.replace("https://www.twitter.com/restauowner");
    },
    goToFacebook() {
      location.replace("https://www.facebook.com/restauowner");
    },
  },
  mounted() {
    ScrollReveal({ duration: 1500, reset: false });
    ScrollReveal().reveal(".reveal3", {
      delay: 500,
      interval: 700,
      distance: "200px",
      origin: "left",
    });
    ScrollReveal().reveal(".reveal1", {
      delay: 500,
      interval: 700,
      distance: "100px",
    });
    ScrollReveal().reveal(".reveal2", {
      delay: 500,
      interval: 700,
      distance: "200px",
      origin: "right",
    });

    ScrollReveal().reveal(".reveal4", {
      delay: 500,
      interval: 700,
      distance: "200px",
      origin: "top",
    });
  },
};
</script>

<style>
html {
  scroll-behavior: smooth;
}
body {
  overflow-x: hidden;
}
.reveal1,
.reveal2,
.reveal3,
.reveal4 {
  /*opacity: 0;*/
  visibility: hidden;
}
</style>

<style scoped>
a {
  text-decoration: none;
}
ul {
  padding-left: 0;
}

#ourTeam h5 {
  margin-bottom: 0;
  margin-top: 0.6rem;
  font-size: 1rem;
}
#ourTeam p {
  font-size: 0.8rem;
}

.leftImage {
  background-size: cover;
  background-position: center;
  width: auto;
  height: 550px;
  position: relative;
  left: -120vw;
}

@media screen and (max-width: 494px) {
  .leftImage {
    left: -127vw;
  }
}

@media screen and (min-width: 578px) {
  .leftImage {
    left: -150vw;
  }
}

@media screen and (min-width: 650px) {
  .leftImage {
    left: -130vw;
  }
}

@media screen and (min-width: 720px) {
  .leftImage {
    left: -120vw;
  }
}

@media screen and (min-width: 728px) {
  .leftImage {
    left: -110vw;
  }
}

@media screen and (min-width: 828px) {
  .leftImage {
    left: -90vw;
  }
}

@media screen and (min-width: 1094px) {
  .leftImage {
    left: -650px;
  }
}

@media screen and (min-width: 1410px) {
  .leftImage {
    left: -500px;
  }
}

@media screen and (max-width: 565px) {
  #ourTeam .row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
