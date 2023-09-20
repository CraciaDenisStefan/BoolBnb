<script>
import axios from 'axios';
import { store } from '../store.js';
import AppLoading from '../components/AppLoading.vue';
export default {
    components:{
        AppLoading
    },
    data() {
        return {
            apartments: [],
            store,
            maxCaracters: 30,
        }
    },
    created() {
        this.getApartments();
    },
    methods: {
        getApartments(){
                this.store.loading = true;
                axios.get(`${this.store.baseUrl}/api/apartments`).then((response) => {
                    console.log(response)
                    if(response.data.success){
                        this.apartments = response.data.results;
                        this.store.loading = false;
                    }else{
                        this.$router.push({ name: 'not-found' })
                    }
                });
            },
        truncateText(text) {
                if (text.length > this.maxCaracters) {
                    return text.substr(0, this.maxCaracters) + '...';
                }
                return text
                }
    },

}
</script>
<template>    
    <div class="container">
        <div class="row">
            <div v-for="apartment in apartments" :key="apartment.id" class="col-12 col-lg-6 mb-4"> 
                <div class="card border m-2" >
                    <div class="row g-0">
                        <div  class="col-md-4">
                            <div v-if="apartment.img" class="card-img my_card shadow-lg"
                                :style="{ backgroundImage: `url('${this.store.baseUrl}/storage/${apartment.img}')`,backgroundSize: 'cover', backgroundPosition: 'center', height: '150px'  }">
                            </div>
                            <div v-else class="card-img my_card"
                                style="background-image: url('https://vestnorden.com/wp-content/uploads/2018/03/house-placeholder.png'); background-size: cover; background-position: center; height: 150px;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title" v-if="apartment.title">{{ apartment.title }}</h5>
                                <p class="card-text truncate-text" v-if="apartment.description">{{ truncateText(apartment.description) }}</p> 
                            </div>            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss">
    
</style>