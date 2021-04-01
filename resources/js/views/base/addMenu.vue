<template>
    <div class="mainWrapper">
        <div class="main">
            <div class="imageCircleContainer">
                <label class="choosefile">
                    <img
                        class="imageCircle"
                        id="avatar"
                        :src="imageFile"
                        alt="Choose Image"
                    />
                    <input
                        type="file"
                        class="unploadInput sr-only"
                        id="input"
                        name="image"
                        accept="image/*"
                        placeholder="Add image"
                    />
                </label>
            </div>
            <form class="myForms" @submit.prevent="submit">
                <h4 class="pl-3">{{ category.title }}</h4>
                <div class="input">
                    <v-icon x-large>mdi-pizza</v-icon>
                    <input v-model="title" type="text" placeholder="Title"/>
                </div>
                <div class="input">
                    <v-icon x-large>mdi-currency-php</v-icon>
                    <input v-model="price" type="number" step="0.01" placeholder="Price"/>
                </div>
                <div class="inputArea">
                    <label for="ingredientInput">Ingredients</label>
                    <div class="myWrapperArea">
            <textarea
                v-model="ingredients"
                id="ingredientInput"
                placeholder="Write the main ingredients"
            ></textarea>
                    </div>
                </div>
                <div class="myButtons mt-8">
                    <v-btn color="error" @click="setMenuDialog(false)">Cancel</v-btn>
                    <v-btn color="success" type="submit">Save</v-btn>
                </div>
            </form>
        </div>

        <div
            class="modal fade"
            id="modal"
            tabindex="-1"
            role="dialog"
            aria-hidden="true"
            data-backdrop="false"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Crop the image</h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="img-container">
                            <img
                                id="image"
                                src="https://avatars0.githubusercontent.com/u/3456749"
                            />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary" id="crop">
                            Crop
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import imageFile from "../../assets/icons/image_file.svg";
import Form from "../../plugins/Form";
import axios from "axios";
import token from "../../dev/token";

export default {
    props: {
        setMenuDialog: {
            type: Function,
        },
        reloadMenus: {
            type: Function,
        },
        category: {
            type: Object
        }
    },
    data() {
        return {
            imageFile,
            canvas: null,
            imageName: "",
            price: "0.00",
            title: "",
            ingredients: ""
        };
    },
    methods: {
        submit() {
            if (!this.canvas) {
                return Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Please insert an image.",
                })
            }

            this.canvas.toBlob((blob) => {
                let formData = new FormData();
                formData.append("price", this.price);
                formData.append("title", this.title);
                formData.append("ingredients", this.ingredients);
                formData.append("image", blob, this.imageName);
                formData.append("category_id", this.category.value);
                axios.post('/api/menu', formData, token()).then(r => {
                    this.imageName = ""
                    this.price = "0.00"
                    this.title = ""
                    this.ingredients = ""
                    this.reloadMenus()
                    this.setMenuDialog(false)
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        text: r.data.message,
                    })
                }).catch(err => {
                    return Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: err.response.data.message,
                    })
                })
            });
        }
    },
    mounted() {
        require("bootstrap");
        let $ = (window.jQuery = require("jquery"));
        let Cropper = require("../../plugins/cropper/cropper.js");
        const avatar = document.getElementById("avatar");
        const image = document.getElementById("image");
        const input = document.getElementById("input");
        const $alert = $(".alert");
        const $modal = $("#modal");
        let cropper;

        input.addEventListener("change", (e) => {
            let files = e.target.files;
            let done = function (url) {
                input.value = "";
                image.src = url;
                $alert.hide();
                $modal.modal("show");
            };
            let reader;
            let file;

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
                this.imageName = file.name
            }
        });

        $modal
            .on("shown.bs.modal", function () {
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 3,
                });
            })
            .on("hidden.bs.modal", function () {
                cropper.destroy();
                cropper = null;
            });

        document.getElementById("crop").addEventListener("click", () => {
            let initialAvatarURL;
            let canvas;
            $modal.modal("hide");

            if (cropper) {
                canvas = cropper.getCroppedCanvas({
                    width: 132,
                    height: 132,
                });
                initialAvatarURL = avatar.src;
                avatar.src = canvas.toDataURL();
                $alert.removeClass("alert-success alert-warning");
                this.canvas = canvas
            }
        });
    },
};
</script>

<style scoped>
.mainWrapper {
    background: rgba(0, 16, 74, 0.6);
    height: 100vh;
    width: 100vw;
    position: fixed;
    z-index: 1;
}

.main {
    position: fixed;
    left: 50%;
    top: 55%;
    transform: translate(-50%, -50%);
    background: #faf9fb;
    width: 611px;
    height: 661px;
    z-index: 2;
    border-radius: 30px;
    padding: 140px 40px;
}

.imageCircle {
    width: 132px;
    height: 132px;
    background: #a3a3a3;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.inputArea .myWrapperArea {
    background: #fff;
    box-shadow: 0 2px 1px rgba(0, 0, 0, 0.15);
    padding: 15px 20px;
    height: 173px;
    border-radius: 10px;
}

.inputArea label {
    font-size: 1rem;
    font-weight: bold;
    margin-bottom: 7px;
    display: block;
}

.inputArea textarea {
    width: 100%;
    resize: none;
    font-size: 1.3rem;
    font-weight: bold;
    height: 100%;
}

.input input {
    font-size: 2rem;
    font-weight: bold;
    margin-left: 20px;
    margin-right: 40px;
    color: #353853;
    width: 100%;
    outline: none;
}

.input {
    border-radius: 55px;
    background: #fff;
    box-shadow: 0 2px 1px rgba(0, 0, 0, 0.15);
    height: 73px;
    display: flex;
    align-items: center;
    padding-left: 20px;
    margin: 15px 0;
}

.cropperContainer {
    margin: 20px auto;
    max-width: 640px;
}

img {
    max-width: 100%;
}

.cropper-view-box,
.cropper-face {
    border-radius: 50%;
}

.unploadInput {
    position: absolute;
    width: 100%;
    height: 100%;
}

.imageCircleContainer {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
}

.choosefile {
    cursor: pointer;
}

@import "https://unpkg.com/bootstrap@4.6.0/dist/css/bootstrap.min.css";
@import "../../plugins/cropper/cropper.css";
</style>
