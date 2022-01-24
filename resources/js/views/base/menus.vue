<template>
    <div>
        <div
            v-for="menu in menus"
            :key="menu.id + 'menu'"
            :class="{ 'justify-content-center': menu.length === 2 }"
            class="myCards"
        >
            <div
                v-for="item in menu"
                :key="item.id + 'item'"
                :class="{ 'mx-12': menu.length === 2 }"
                class="myCard"
            >
                <img
                    :src="item.crop_path"
                    alt="food_image"
                    @click="handleEditDialog(item)"
                />
                <v-chip
                    :color="item.is_available ? 'success' : 'error'"
                    class="mb-2"
                    x-small
                    dark
                    @click="toggleAvailability(item.id)"
                    v-text="item.is_available ? 'Available' : 'Unavailable'"
                >
                </v-chip>

                <v-chip color="black" outlined class="mb-2" x-small>
                    <v-icon left small>mdi-clock</v-icon>
                    {{ item.preparation_time }} minute/s
                </v-chip>

                <h3 class="font-weight-bold">{{ item.title }}</h3>
                <small
                    >Stocks: <b>{{ item.quantity }}</b></small
                >
                <p>
                    {{ item.ingredients }}
                </p>
                <span class="item-price">P{{ item.price }}</span>
                <v-icon
                    class="removeMenu"
                    color="white"
                    large
                    @click="removeMenu(item.id)"
                    >mdi-minus
                </v-icon>
                <v-icon
                    class="stockMenu"
                    color="white"
                    x-small
                    @click="addStock(item.id)"
                    >mdi-truck
                </v-icon>
            </div>
        </div>

        <v-dialog v-model="stockDialog" max-width="600px" persistent>
            <v-card>
                <v-card-title>
                    <span class="text-h5">UPDATE INVENTORY</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="stockForm.quantity"
                                    :error-messages="
                                        stockForm.errors.get('quantity')
                                    "
                                    hint="Add '-' or negative value to deduct from stocks."
                                    label="Quantity"
                                    persistent-hint
                                    type="number"
                                />
                                <v-textarea
                                    v-model="stockForm.remarks"
                                    :error-messages="
                                        stockForm.errors.get('remarks')
                                    "
                                    label="Remarks"
                                    rows="3"
                                />
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="stockDialog = false"
                    >
                        Close
                    </v-btn>
                    <v-btn
                        color="blue darken-1"
                        :loading="loading"
                        text
                        @click="saveStock"
                    >
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialog" max-width="600px" persistent>
            <v-card>
                <v-card-title>
                    <span class="text-h5">Menu Details</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="form.title"
                                    :error-messages="form.errors.get('title')"
                                    label="Title*"
                                    required
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="form.price"
                                    :error-messages="form.errors.get('price')"
                                    label="Price*"
                                    required
                                    step="0.01"
                                    type="number"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="form.preparation_time"
                                    :error-messages="
                                        form.errors.get('preparation_time')
                                    "
                                    label="Preparation Time (AVG in minutes)*"
                                    required
                                    type="number"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-textarea
                                    v-model="form.ingredients"
                                    :error-messages="
                                        form.errors.get('ingredients')
                                    "
                                    label="Ingredients"
                                    name="input-7-4"
                                    outlined
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
                    <v-btn
                        :loading="loading"
                        color="blue darken-1"
                        text
                        @click="handleSave"
                    >
                        Save</v-btn
                    >
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
            type: Array
        },
        reloadMenus: {
            type: Function
        }
    },
    data: () => ({
        rows: 0,
        stockDialog: false,
        loading: false,
        dialog: false,
        form: new Form({
            title: "",
            price: 0,
            ingredients: "",
            preparation_time: "",
            id: 0
        }),
        stockForm: new Form({
            quantity: "",
            remarks: "",
            menu_id: ""
        })
    }),
    methods: {
        addStock(menu) {
            this.stockForm.menu_id = menu;
            this.stockDialog = true;
        },
        toggleAvailability(id) {
            axios
                .post(`/api/menus/${id}/toggle-availability`, {}, token())
                .then(response => {
                    this.reloadMenus();
                })
                .catch(error => {
                    console.log(error);
                });
        },
        saveStock() {
            this.stockForm.errors.clear();
            this.loading = true;
            this.stockForm
                .put("/api/inventory")
                .then(res => {
                    Swal.fire({
                        timer: 1000,
                        position: "top-end",
                        title: res.message,
                        showConfirmButton: false,
                        icon: "success"
                    });
                    this.stockDialog = false;
                    this.reloadMenus();
                })
                .catch(err => {
                    this.stockForm.errors.set(err.errors);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        handleEditDialog(menu) {
            this.form.title = menu.title;
            this.form.preparation_time = menu.preparation_time;
            this.form.price = menu.price;
            this.form.ingredients = menu.ingredients;
            this.form.id = menu.id;
            this.dialog = true;
        },
        async handleSave() {
            try {
                const request = await this.form.patch(
                    `/api/menu/${this.form.id}`
                );
                Swal.fire({
                    timer: 1000,
                    position: "top-end",
                    title: request.message,
                    showConfirmButton: false,
                    icon: "success"
                }).then(r => this.reloadMenus());
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
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.isConfirmed) {
                    axios
                        .delete(`/api/menu/${menu_id}`, token())
                        .then(r => {
                            this.reloadMenus();
                            Swal.fire(
                                "Deleted!",
                                "Menu has been deleted successfully",
                                "success"
                            );
                        })
                        .catch(err => {
                            Swal.fire(
                                "Failed!",
                                err.response.data.message,
                                "error"
                            );
                        });
                }
            });
        }
    }
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

.myCard .item-price {
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

.stockMenu {
    border-radius: 55%;
    background: #30334f;
    position: absolute;
    bottom: 80px;
    right: 20px;
    width: 30px;
    height: 30px;
    cursor: pointer;
}

.stockMenu:hover {
    background: red;
}
</style>
