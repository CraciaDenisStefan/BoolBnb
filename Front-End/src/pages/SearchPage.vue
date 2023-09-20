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
            apartmentsFilter: [],
            store,
            searchText:'',
            myUrl: '',
            n_rooms: null,
            n_beds: null,
   
        }
    },
    created() {
        this.getApartments();
    },
    methods: {
        getApartments(){
                this.store.loading = true;
                axios.get(`${this.store.baseUrl}/api/apartmentsFilter`).then((response) => {
                    if(response.data.success){
                        this.apartmentsFilter = response.data.results;
                        this.store.loading = false;
                    }else{
                        this.$router.push({ name: 'not-found' })
                    }
                });
            },


    //     searchApartment(){
          
    //       store.myUrl = store.baseUrl;
    //      if(store.searchText !== ''){
    //       if (store.typePokemon !== '') {
    //       store.myUrl += `&eq[type1]=${store.typePokemon}`
    //      }
    //       store.myUrl += `&start[name]=${store.searchText}`;
    //     }else if (store.typePokemon !== ''){
    //       store.myUrl += `&eq[type1]=${store.typePokemon}`
    //     }
      

    //     axios.get(store.myUrl).then((response)=>{
    //     store.pokemons=response.data.docs
    //     store.loading = false
    //   })
    //   },
    filterApartment() {
            this.myUrl = `${this.store.baseUrl}/api/apartmentsFilter?`; // Inizia con un punto interrogativo

            if (this.n_rooms !== null) {
                this.myUrl += `n_rooms=${this.n_rooms}&`; // Aggiungi n_rooms se è definito
            }

            if (this.n_beds !== null) {
                this.myUrl += `n_beds=${this.n_beds}&`; // Aggiungi n_beds se è definito
            }

            // Rimuovi l'ultimo carattere (&) dall'URL
            this.myUrl = this.myUrl.slice(0, -1);

            // Esegui la richiesta API
            axios.get(this.myUrl).then((response) => {
                this.apartmentsFilter = response.data.results;
            });
        },
    }

    
}
</script>
<template> 
<AppLoading v-if="this.store.loading" />  
    <div v-else class="container">
        <input type="number" class="form-control"  v-model="this.n_rooms" @keyup="filterApartment()">
        <input type="number" class="form-control"  v-model="this.n_beds" @keyup="filterApartment()">
        <div class="row">
            <div v-for="apartment in apartmentsFilter" :key="apartment.id" class="col-12 col-lg-6 mb-4">
                <ApartmentCard :apartment="apartment"/>
            </div>
        </div>     
    </div>
</template>
<style lang="scss">
   
</style>