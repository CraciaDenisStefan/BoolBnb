<script>
import axios from 'axios';
import { store } from '../store.js';
import AppLoading from '../components/AppLoading.vue';
import ApartmentCard from '../components/ApartmentCard.vue';
import { autoAddress } from '../address.js';
export default {
    components:{
        AppLoading,
        ApartmentCard
    },
    data() {
        return {
            apartments: [],
            store,
            autoAddress,
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

        redirectToSearch() {
            const addressInput = document.getElementById("address");
            const address = addressInput.value.trim(); // Ottieni l'indirizzo inserito e rimuovi gli spazi vuoti

            if (address !== "") {
                // Reindirizza l'utente alla pagina di ricerca con l'indirizzo come parametro
                this.$router.push({ name: 'search', query: { address: store.address } });
            }
        }

    },

}
</script>
<template> 
<AppLoading v-if="this.store.loading" />  
    <div v-else class="container">
        <div class="d-flex justify-content-center mb-4">
            <div>
                <input type="text" id="address" class="form-control" placeholder="città" @keyup.enter="redirectToSearch()" @keyup="this.autoAddress" v-model="store.address">
                <ul @click="redirectToSearch()" id="autocomplete-list" class="list-group box-list"></ul>
            </div>
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