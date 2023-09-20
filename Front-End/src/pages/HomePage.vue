<script>
import axios from 'axios';
import { store } from '../store.js';
import AppLoading from '../components/AppLoading.vue';
import ApartmentCard from '../components/ApartmentCard.vue';
export default {
    components:{
        AppLoading,
        ApartmentCard
    },
    data() {
        return {
            apartments: [],
            store,
   
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
<AppLoading v-if="this.store.loading" />  
    <div v-else class="container">
        <div class="d-flex justify-content-center mb-4">
            <router-link class="btn primary-colour" :to="{ name:'search' }">Ricerca appartamenti</router-link>
        </div>
        <div class="row">
            <div v-for="apartment in apartments" :key="apartment.id" class="col-12 col-lg-6 mb-4">
                <ApartmentCard :apartment="apartment"/>
            </div>
        </div>     
    </div>
</template>
<style lang="scss">
   
</style>