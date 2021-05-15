<template>
  <div>
    <div class="d-flex">
      <v-spacer />
      <button class="myBtn" @click="dialog = true">
        New category
        <v-icon class="btnIcon ml-2" large>mdi-plus</v-icon>
      </button>
    </div>
    <ul class="slider mt-5">
      <li
        :key="category.id"
        v-for="category in categories"
        class="category"
        @click="toggleCategory(category)"
      >
        <img :src="category.icon" alt="icon" />
        <span v-text="category.title"></span>
        <v-icon
          class="removeCategory"
          @click="deleteCategory(category)"
          color="error"
          >mdi-minus
        </v-icon>
      </li>
    </ul>
    <v-dialog
      v-model="dialog"
      max-width="600"
      transition="dialog-bottom-transition"
    >
      <template>
        <v-card>
          <v-toolbar color="primary" dark>New Category </v-toolbar>
          <v-card-text class="py-5">
            <v-file-input
              v-model="form.image"
              accept="image/*"
              label="Icon"
            ></v-file-input>
            <v-text-field
              v-model="form.title"
              :rules="rules"
              label="Title"
            ></v-text-field>
          </v-card-text>
          <v-card-actions class="justify-end">
            <v-btn text @click="dialog = false">Close </v-btn>
            <v-btn color="success" text @click="submit" :disabled="process"
              >Submit
            </v-btn>
          </v-card-actions>
        </v-card>
      </template>
    </v-dialog>
  </div>
</template>

<script>
import iconAll from "../../assets/icons/iconAll.svg";
import Form from "../../plugins/Form";
import axios from "axios";
import token from "../../dev/token";

export default {
  props: {
    removable: { type: Boolean, default: true },
    handler: { type: Function },
  },
  data() {
    return {
      form: new Form({
        title: "",
        image: null,
      }),
      process: false,
      rules: [
        (value) => !!value || "Required.",
        (value) => (value && value.length <= 16) || "Max 16 characters",
      ],
      dialog: false,
      categories: [],
    };
  },
  watch: {
    categories() {
      this.$loadScript(
        "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
      ).then(() => {
        this.$loadScript(
          "https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"
        ).then(() => {
          $(".slider").slick({
            infinite: false,
            slidesToShow:
              this.categories.length < this.categories.length
                ? this.categories.length
                : 6,
            slidesToScroll: 5,
            dots: true,
          });
        });
      });
    },
  },
  methods: {
    deleteCategory(category) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete(`/api/categories/${category.value}`, token())
            .then(() => {
              Swal.fire(
                "Deleted!",
                "The category has been deleted.",
                "success"
              ).then(() => location.reload());
            })
            .catch((err) =>
              Swal.fire("Failed!", err.response.data.message, "error")
            );
        }
      });
    },
    toggleCategory(category) {
      this.handler(category);
    },
    async submit() {
      this.process = true;
      const formData = new FormData();
      formData.append("image", this.form.image);
      formData.append("title", this.form.title);
      await axios
        .post("/api/categories", formData, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
            // 'content-type': 'multipart/form-data'
          },
        })
        .then((r) => {
          this.form.title = "";
          this.form.image = null;
          this.dialog = false;
          Swal.fire({
            icon: "success",
            text: "New category has been added successfully.",
            title: "Awesome!",
          }).then((r) => location.reload());
        })
        .catch((err) => {
          this.form.errors.set(err.response.data.errors);
          const message =
            this.form.errors.get("title") ?? this.form.errors.get("image");
          Swal.fire({
            icon: "error",
            text: message,
            title: "Oops...",
          });
        });
      this.process = false;
    },
    async fetchCategories() {
      await axios.get("/api/categories").then((r) => {
        this.categories = r.data.data;
        this.categories.unshift({
          icon: iconAll,
          title: "All",
          value: "All",
          active: false,
        });
      });
    },
  },
  created() {
    this.fetchCategories();
  },
  mounted() {},
};
</script>

<style>
.slick-track {
  display: flex !important;
  margin-left: 0 !important;
  margin-right: 0 !important;
}
</style>
<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css");
@import url("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css");

.category {
  display: flex;
  flex-direction: column;
  background: #f5f6f7;
  min-width: fit-content;
  align-items: center;
  height: 164px;
  justify-content: center;
  border-radius: 45px;
  position: relative;
  margin: 0 15px;
  padding: 10px 20px;
  cursor: pointer;
  max-height: 180px;
}

.myBtn,
.btnIcon {
  font-size: 1.4rem;
  font-weight: bold;
  color: #353853;
  outline: none;
  border: none;
}

.categories {
  display: flex;
  margin-top: 35px;
}

.category img {
  width: 60px;
}

.category span {
  font-size: 14px;
  font-weight: bold;
  margin-top: 10px;
  /*word-wrap: break-word;*/
}

ul {
  padding: 0;
}

.removeCategory {
  position: absolute;
  top: 10px;
  right: 0px;
  /*border: 1px solid red;*/
  cursor: pointer;
}

.removeCategory:hover::before {
  background: red;
  color: #fff;
}

.activeCategory {
  background: #ffffff;
  box-shadow: 0 27px 30px rgba(0, 0, 0, 0.1);
}

.nextCategory {
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
}
</style>
