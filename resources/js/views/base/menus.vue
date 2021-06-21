<template>
  <div>
    <div
      class="myCards"
      v-for="menu in menus"
      :key="menu.id"
      :class="{ 'justify-content-center': menu.length === 2 }"
    >
      <div
        class="myCard"
        v-for="item in menu"
        :key="item.id"
        :class="{ 'mx-12': menu.length === 2 }"
      >
        <img
          @click="handleEditDialog(item)"
          :src="item.image_path"
          alt="food_image"
        />
        <h3 class="font-weight-bold">{{ item.title }}</h3>
        <p>
          {{ item.ingredients }}
        </p>
        <span>P{{ item.price }}</span>
        <v-icon
          large
          class="removeMenu"
          color="white"
          @click="removeMenu(item.id)"
          >mdi-minus</v-icon
        >
      </div>
    </div>
    <v-dialog v-model="dialog" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Menu Details</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  :error-messages="form.errors.get('title')"
                  v-model="form.title"
                  label="Title*"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  :error-messages="form.errors.get('price')"
                  v-model="form.price"
                  label="Price*"
                  step="0.01"
                  type="number"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-textarea
                  :error-messages="form.errors.get('ingredients')"
                  outlined
                  v-model="form.ingredients"
                  name="input-7-4"
                  label="Ingredients"
                ></v-textarea>
              </v-col>
            </v-row>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialog = false">
            Close
          </v-btn>
          <v-btn color="blue darken-1" text @click="handleSave"> Save </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import axios from "axios";
import token from "../../dev/token";
import Form from "../../plugins/Form";

export default {
  props: {
    menus: {
      type: Array,
    },
    reloadMenus: {
      type: Function,
    },
  },
  data: () => ({
    rows: 0,
    dialog: false,
    form: new Form({
      title: "",
      price: 0,
      ingredients: "",
      id: 0,
    }),
  }),
  methods: {
    handleEditDialog(menu) {
      this.form.title = menu.title;
      this.form.price = menu.price;
      this.form.ingredients = menu.ingredients;
      this.form.id = menu.id;
      this.dialog = true;
    },
    async handleSave() {
      try {
        const request = await this.form.patch(`/api/menu/${this.form.id}`);
        Swal.fire({
          timer: 1000,
          position: "top-end",
          title: request.message,
          showConfirmButton: false,
          icon: "success",
        }).then((r) => location.reload());
        this.dialog = false;
      } catch (error) {
        this.form.errors.set(error.errors);
      }
    },
    removeMenu(menu_id) {
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
            .delete(`/api/menu/${menu_id}`, token())
            .then((r) => {
              this.reloadMenus();
              Swal.fire(
                "Deleted!",
                "Menu has been deleted successfully",
                "success"
              );
            })
            .catch((err) => {
              Swal.fire("Failed!", err.response.data.message, "error");
            });
        }
      });
    },
  },
};
</script>

<style scoped>
.myCard {
  border: 1px solid #a5a5a5;
  width: 100%;
  max-width: 270px;
  padding: 120px 30px 30px 30px;
  position: relative;
  border-radius: 45px;
}

.myCards {
  display: flex;
  justify-content: space-between;
  margin-bottom: 80px;
}

.myCard:hover {
  background: #efefef;
}

.myCard p {
  font-size: 12px;
  margin-top: 7px;
  font-weight: bold;
  margin-bottom: 55px;
  word-wrap: break-word;
  width: 100%;
}

.myCard h3 {
  font-size: 1.2rem;
}

.myCard span {
  font-weight: bold;
  font-size: 1.4rem;
  position: absolute;
  bottom: 30px;
}

.myCard img {
  border-radius: 50%;
  height: 132px;
  width: 132px;
  background-position: 50% 50%;
  background-repeat: no-repeat;
  object-fit: cover;
  background-size: cover;
  background: gray;
  position: absolute;
  top: 0;
  transform: translate(-50%, -40%);
  left: 50%;
  cursor: pointer;
}

.removeMenu {
  border-radius: 55%;
  background: #30334f;
  position: absolute;
  bottom: 20px;
  right: 20px;
  width: 50px;
  height: 50px;
  cursor: pointer;
}

.removeMenu:hover {
  background: red;
}
</style>
