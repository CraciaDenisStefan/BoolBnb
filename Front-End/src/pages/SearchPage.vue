<script>
import axios from 'axios';
import { store } from '../store.js';
import AppLoading from '../components/AppLoading.vue';
import ApartmentCard from '../components/ApartmentCard.vue';
import { autoAddress } from '../address.js';
  
export default {
    components: {
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
            selectedServices: [],
            sliderPosition: 0,
            itemsToShow: 4,
            sponsoredApartmentsArray: [],
            nonSponsoredApartmentsArray: []
        };
    },
    computed: {
        visibleServices(){
            const start = this.sliderPosition;
            const end = start + this.itemsToShow;
            return this.services.slice(start, end);
        },
    },
    created() {
        this.getApartments();
        this.getServices();
        this.filterApartments();
        this.calculateItemsToShow(); // Chiamato all'inizio
        this.handleWindowResize(); // Aggiungi un ascoltatore per il ridimensionamento
    },
    methods: {
        sponsoredApartments(){
            this.sponsoredApartmentsArray = this.apartmentsFilter.filter(apartment => apartment.sponsorships && apartment.sponsorships.length > 0 && apartment.visible);
        },
        
        nonSponsoredApartments(){
            this.nonSponsoredApartmentsArray = this.apartmentsFilter.filter(apartment => !apartment.sponsorships || apartment.sponsorships.length === 0 && apartment.visible);
        },

        prevSlide(){
            if(this.sliderPosition > 0){
                this.sliderPosition -= this.itemsToShow; // Aggiorna il valore del passo
            }
        },

        nextSlide(){
            const maxPosition = this.services.length - this.itemsToShow;
            if(this.sliderPosition < maxPosition){
                this.sliderPosition += this.itemsToShow; // Aggiorna il valore del passo
            }
        },

        moveSlide(direction) {
            if(direction === 'prev'){
                this.prevSlide();
            }else if(direction === 'next'){
                this.nextSlide();
            }
        },

        getApartments(){
            this.store.loading = true;
            axios.get(`${this.store.baseUrl}/api/apartmentsFilter`).then((response) => {
                if(response.data.success){
                    this.apartmentsFilter = response.data.results;
                    this.store.loading = false;
                } else{
                    this.$router.push({ name: 'not-found' });
                }
            });
        },

        resetFilters(){
            this.n_rooms = null;
            this.n_beds = null;
            store.address = null;
            this.range = 20;
            this.lat = null;
            this.lon = null;
            this.getApartments();
            this.selectedServices = [];
            this.filterApartments();
        },

        filterApartments(){
            const query = {
                n_rooms: this.n_rooms,
                n_beds: this.n_beds,
                address: this.store.address,
                range: this.range,
                services: this.selectedServices.join(','),
            };
            this.apartmentsFilter = [];

            axios
            .get(
            `https://api.tomtom.com/search/2/geocode/${this.store.address}.json?key=i0LOdzaKgh77G9KYA4lqDP3GOttQ0kZT`
            )
            .then((response) => {

            // Estrai latitudine e longitudine dalla risposta
            this.lat = response.data.results[0].position.lat;
            this.lon = response.data.results[0].position.lon;

            // Ora hai le coordinate latitudine e longitudine, puoi usarle per costruire l'URL
            this.myUrl = `${this.store.baseUrl}/api/apartmentsFilter?`;

            if(this.n_rooms !== null){
                this.myUrl += `n_rooms=${this.n_rooms}&`;
            }

            if(this.n_beds !== null){
                this.myUrl += `n_beds=${this.n_beds}&`;
            }

            if(this.store.address !== null){
                this.myUrl += `address=${this.store.address}&`;
                if (this.range !== null && this.lat !== null && this.lon !== null) {
                this.myUrl += `range=${this.range}&lat=${this.lat}&lon=${this.lon}&`;
                }
            }

            if(this.selectedServices.length > 0){
                const encodedServices = this.selectedServices.map((service) => encodeURIComponent(service));
                this.myUrl += `services=${encodedServices.join(',')}&`;
                console.log('servizi', this.myUrl);
            }

            axios.get(this.myUrl).then((response) => {
                this.apartmentsFilter = response.data.results;
                this.sponsoredApartments()
                this.nonSponsoredApartments();
            });

                this.$router.push({ name: 'search', query });
            })
            .catch((error) => {
                console.error('Errore nella geocodifica dell\'indirizzo:', error);
            });

           
        },

        getServices(){
            axios.get(`${this.store.baseUrl}/api/services`).then((response) => {
                if(response.data.success){
                    this.services = response.data.results;
                }else{
                    this.$router.push({ name: 'not-found' });
                }
            });
        },

        calculateItemsToShow(){
            const screenWidth = window.innerWidth;

            if(screenWidth >= 1200){
                this.itemsToShow = 5;
            }else if(screenWidth >= 992){
                this.itemsToShow = 5;
            }else if(screenWidth >= 768){
                this.itemsToShow = 4;
            }else{
                this.itemsToShow = 2;
            }
        },

        handleWindowResize(){
            window.addEventListener('resize', this.calculateItemsToShow);
        },
    },
};
</script>

<template>
    <AppLoading v-if="store.loading" />
    <div v-else class="container">
        <form action="">
            <div class="row">
                <div class="col-12 d-md-flex justify-content-between align-items-center">
                <div class="me-3 my-3">
                        <label for="address">Indirizzo</label>
                        <input
                            type="text"
                            id="address"
                            class="form-control me-2 rounded-3"
                            placeholder="Indirizzo"
                            @keyup="autoAddress"
                            v-model="store.address"
                        />
                        <ul id="autocomplete-list" class="list-group box-list"></ul>
                    </div>
                    <div class="me-3 my-3">
                        <label for="range">Raggio di ricerca</label>
                        <input
                            type="number"
                            id="range"
                            class="form-control me-2 rounded-3"
                            placeholder="Raggio di ricerca"
                            min="20"
                            v-model="range"
                        />
                    </div>
                    <div class="me-3 my-3">
                        <label>Numero di stanze</label>
                        <input
                            type="number"
                            class="form-control me-2 rounded-3"
                            placeholder="Numero di stanze"
                            v-model="n_rooms"
                        />
                    </div>
                    <div class="me-3 my-3">
                        <label>Posti letto</label>
                        <input
                            type="number"
                            class="form-control rounded-3"
                            placeholder="Posti letto"
                            v-model="n_beds"
                        />
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between my-4">
                <button class="slider-control" @click.prevent="moveSlide('prev')"><i class="fa-solid fa-arrow-left"></i></button>
                <div class="d-flex align-items-center justify-content-between">
                    <div
                        class="service-slider slider"
                        :style="{ transform: `translateX(${sliderPosition}%)` }"
                    >
                        <div v-for="service in visibleServices" :key="service.id" class="slider-item">
                            <div class="text-center">
                                <i :class="service.icon"></i>
                            </div>
                            <label class="my-checkbox">
                                <input
                                    :value="service.id"
                                    class="btn-check"
                                    type="checkbox"
                                    name="service"
                                    id="service"
                                    v-model="selectedServices"
                                />
                                <div class="btn btn-service" :class="{ 'active': selectedServices.includes(service.id) }">
                                    {{ service.name }}
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <button class="slider-control" @click.prevent="moveSlide('next')"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
            <div class="d-flex align-items-center justify-content-center">
                <button class="btn primary-colour mx-3" @click="filterApartments" type="button">Filtra</button>
                <button class="btn primary-colour" @click="resetFilters" type="button">Reset</button>
            </div>
        </form>
        <div class="row">
            <!-- Visualizza gli appartamenti sponsorizzati -->
            <h1>Appartamneti in evidenza</h1>
            <div v-for="apartment in this.sponsoredApartmentsArray" :key="apartment.id" class="col-12 col-md-6 col-lg-4 mb-4">
                <ApartmentCard :apartment="apartment" />
            </div>
        </div>

        <hr class="border border-danger border-3 opacity-100">

        <div class="row">
            <!-- Visualizza gli appartamenti non sponsorizzati -->
            <div v-for="apartment in this.nonSponsoredApartmentsArray" :key="apartment.id" class="col-12 col-md-6 col-lg-4 mb-4">
                <ApartmentCard :apartment="apartment" />
            </div>
        </div>
    </div>
</template>
  
<style lang="scss">
    .my-checkbox{
        display: inline-block;
        margin-right: 10px;
        cursor: pointer;
    }
  
    .btn-service{
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #fff;
        transition: background-color 0.3s;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
  
    .service-slider{
        display: flex;
        transition: transform 0.3s ease;
    }
  
    .slider-item{
        flex: 0 0 auto;
        margin-right: 10px;
    }
  
    .slider-control{
        background: transparent;
        border: none;
        font-size: 24px;
        cursor: pointer;
    }
  
    .slider-control:focus{
        outline: none;
    }
  
    @media (min-width: 768px) {
        .service-slider{
            width: calc(100% / 9);
        }
    }
  </style>
  