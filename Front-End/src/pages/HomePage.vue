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
            store
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
    },
}
</script>
<template>
    <AppLoading v-if="this.store.loading"/>
    <div v-else>
        <div class="container">
            <div class="row">
                <div v-for="apartment in apartments" :key="apartment.id">
                    <div class="col-12 col-md-6">
                        <div class="card">
                            {{ apartment.title }}
                            <img class="card-img-top my-img" :src="`${this.store.baseUrl}/storage/${apartment.img}`" :alt="'Immagine non disponibile'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss">
    
</style>