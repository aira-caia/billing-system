<template>
    <div>
        <div class="myCards" v-for="menu in menus" :class="{'justify-content-center': menu.length === 2}">
            <div class="myCard"  v-for="item in menu" :class="{'mx-12': menu.length === 2}">
                <img
                    :src="'/storage/images/'+item.image_path"
                    alt="food_image"
                />
                <h3 class="font-weight-bold">{{item.title}}</h3>
                <p>
                    {{item.ingredients}}
                </p>
                <span>P{{item.price}}</span>
                <v-icon  large class="removeMenu" color="white" @click="removeMenu(item.id)">mdi-minus</v-icon>
            </div>
        </div>
    </div>
</template>

<script>

import axios from "axios";
import token from "../../dev/token";

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
    }),
    methods: {
        removeMenu(menu_id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/menu/${menu_id}`,token()).then(r=>{
                        this.reloadMenus()
                        Swal.fire(
                            'Deleted!',
                            'Menu has been deleted successfully',
                            'success'
                        )
                    })

                }
            })

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
