<script>
import axios from 'axios';
import { store } from '../store.js';
import AppLoading from '../components/AppLoading.vue';
import ApartmentCard from '../components/ApartmentCard.vue';
import { autoAddress } from '../address.js';
export default {
    components:{
        AppLoading,
        ApartmentCard,
    },
    data() {
        return {
            apartmentsFilter: [],
            store,
            autoAddress,
            myUrl: '',
            n_rooms: null,
            n_beds: null,
            lat: null,
            lon: null,
            range: 20,
            services: [],
            selectedServices: []
        }
    },
    created() {
        this.getApartments();
        this.getServices();
        this.filterApartments();
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

            resetFilters() {
                this.n_rooms = null;
                this.n_beds = null;
                store.address = null;
                this.range = 20;
                this.lat = null;
                this.lon = null;
                this.getApartments(); // Richiama il metodo per ottenere nuovamente gli appartamenti originali
                this.selectedServices = []
            },

            filterApartments() {
                const query = {
                    n_rooms: this.n_rooms,
                    n_beds: this.n_beds,
                    address: this.store.address,
                    range: this.range,
                    services: this.selectedServices.join(','),
                };
                this.apartmentsFilter = [];
                
                axios.get(`https://api.tomtom.com/search/2/geocode/${this.store.address}.json?key=i0LOdzaKgh77G9KYA4lqDP3GOttQ0kZT`)
                    .then((response) => {
                        console.log('Geocodifica avvenuta con successo');
                        
                        // Estrai latitudine e longitudine dalla risposta
                        this.lat = response.data.results[0].position.lat;
                        this.lon = response.data.results[0].position.lon;

                        // Ora hai le coordinate latitudine e longitudine, puoi usarle per costruire l'URL
                        this.myUrl = `${this.store.baseUrl}/api/apartmentsFilter?`;

                        if (this.n_rooms !== null) {
                            this.myUrl += `n_rooms=${this.n_rooms}&`;
                        }

                        if (this.n_beds !== null) {
                            this.myUrl += `n_beds=${this.n_beds}&`;
                        }

                        if (this.store.address !== null) {
                            this.myUrl += `address=${this.store.address}&`;
                            if (this.range !== null && this.lat !== null && this.lon !== null) {
                                this.myUrl += `range=${this.range}&lat=${this.lat}&lon=${this.lon}&`;
                            }
                        }

                        if (this.selectedServices.length > 0) {
                            const encodedServices = this.selectedServices.map(service => encodeURIComponent(service));
                            this.myUrl += `services=${encodedServices.join(',')}&`;
                            console.log('servizi', this.myUrl);
                        }

                        
                        axios.get(this.myUrl).then((response) => {
                            this.apartmentsFilter = response.data.results;
                        });

                        this.$router.push({ name: 'search', query });
                    })
                    .catch((error) => {
                        console.error('Errore nella geocodifica dell\'indirizzo:', error);
                    });

                    
            },
            getServices(){
                axios.get(`${this.store.baseUrl}/api/services`).then((response) => {
                    console.log(response)
                    if(response.data.success){
                        this.services = response.data.results;
                    }else{
                        this.$router.push({ name: 'not-found' })
                    }
                });
            }, 
    }
    
}
</script>
<template> 
<AppLoading v-if="this.store.loading" />  
    <div v-else class="container">
        <form action="">
            <div class="d-flex justify-content-between mx-2">
                <input type="text" id="address" class="form-control rounded-3 me-3" placeholder="Indirizzo" @keyup="this.autoAddress" v-model="store.address">
                <ul id="autocomplete-list" class="list-group box-list"></ul>
                <input type="number" class="form-control rounded-3 me-3" placeholder="Raggio di ricerca" min="20" v-model="range">
                <input type="number" class="form-control rounded-3 me-3" placeholder="Numero di stanze" v-model="n_rooms">
                <input type="number" class="form-control rounded-3" placeholder="Numero di letti"  v-model="n_beds">
            </div>
                <div v-for="service in services" :key="service.id">
                    <input :value="service.id" type="checkbox" name="service" id="service" v-model="selectedServices"> <span>{{ service.name }}</span>
                </div>
    
                <button  class="btn primary-colour mx-3" @click=" filterApartments();" type="button">Filtra</button>
                <button class="btn primary-colour" @click="resetFilters();" type="button">Reset</button>
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