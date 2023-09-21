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
            myUrl: '',
            n_rooms: null,
            n_beds: null,
            address: null,
            lat: null,
            lon: null,
            range: 20
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

            filterApartments() {
                    console.log('funziono1');
                    this.myUrl = `${this.store.baseUrl}/api/apartmentsFilter?`; // Inizia con un punto interrogativo

                    if (this.n_rooms !== null) {
                        this.myUrl += `n_rooms=${this.n_rooms}&`; // Aggiungi n_rooms se è definito
                    }

                    if (this.n_beds !== null) {
                        this.myUrl += `n_beds=${this.n_beds}&`; // Aggiungi n_beds se è definito
                    }

                    if (this.address !== null) 
                    {

                        this.myUrl += `address=${this.address}&`; // Aggiungi l'indirizzo se è definito
                        axios.get(`https://api.tomtom.com/search/2/geocode/${this.address}.json?key=i0LOdzaKgh77G9KYA4lqDP3GOttQ0kZT`)
                        .then((response) => {
                            console.log('funziono2')
                            // Estrai latitudine e longitudine dalla risposta
                            this.lat = response.data.results[0].position.lat;
                            this.lon = response.data.results[0].position.lon;

                            // Ora hai le coordinate latitudine e longitudine, puoi usarle per cercare gli appartamenti nel raggio desiderato
                            // this.searchApartmentsWithinRadius(lat, lon);
                            console.log(this.lat)
                            console.log(this.lon)
                                if (this.range !== null && this.lat !== null && this.lon !== null) 
                                {
                                    this.myUrl += `range=${this.range}&lat=${this.lat}&lon=${this.lon}&`; // Aggiungi raggio, latitudine e longitudine
                                }

                                // Rimuovi l'ultimo carattere (&) dall'URL
                                this.myUrl = this.myUrl.slice(0, -1);

                                axios.get(this.myUrl).then((response) => {
                                    this.apartmentsFilter = response.data.results;
                                });

                        })
                        .catch((error) => {
                            console.error('Errore nella geocodifica dell\'indirizzo:', error);
                        })
                    }
            },

            userCordinates() 
            
            {axios.get(`https://api.tomtom.com/search/2/geocode/${this.address}.json?key=i0LOdzaKgh77G9KYA4lqDP3GOttQ0kZT`)
                .then((response) => {
                    console.log('funziono2')
                    // Estrai latitudine e longitudine dalla risposta
                    this.lat = response.data.results[0].position.lat;
                    this.lon = response.data.results[0].position.lon;

                    // Ora hai le coordinate latitudine e longitudine, puoi usarle per cercare gli appartamenti nel raggio desiderato
                    // this.searchApartmentsWithinRadius(lat, lon);
                    console.log(this.lat)
                    console.log(this.lon)

                })
                .catch((error) => {
                    console.error('Errore nella geocodifica dell\'indirizzo:', error);
                })

            },

    }
    
}
</script>
<template> 
<AppLoading v-if="this.store.loading" />  
    <div v-else class="container">
        <form action="">
            <input type="number" class="form-control" placeholder="numero di stanze" v-model="this.n_rooms">
            <input type="number" class="form-control" placeholder="numero di letti"  v-model="this.n_beds">
            <input type="text" class="form-control" placeholder="città"  v-model="this.address">
            <input type="number" class="form-control" placeholder="raggio di ricerca" min="20" v-model="this.range">
            
            <button class="btn primary-colour" @click=" filterApartments();" type="button">Filtra</button>
        </form>
            <div class="row">
                <div v-for="apartment in apartmentsFilter" :key="apartment.id" class="col-12 col-lg-6 mb-4">
                    <ApartmentCard :apartment="apartment"/>
                </div>
            </div> 
    </div>
</template>
<style lang="scss">
   
</style>