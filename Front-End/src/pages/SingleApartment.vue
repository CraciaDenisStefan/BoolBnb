<script>
import axios from 'axios';
import { store } from '../store.js';
import AppLoading from '../components/AppLoading.vue';
import tt from '@tomtom-international/web-sdk-maps';
export default {
    components:{
        AppLoading
    },
    data() {
        return {
            store,
            apartment: [],
            loading: false,
            name: '',
            email: '',
            content: '',
            errors: {},
            apartment_id: '',
        }
    },
    
    methods: {
        async getMap(longitude, latitude) {
    // Assicurati che il documento HTML sia completamente caricato
    const mapElement = document.getElementById('map');

    if (mapElement) {
      // L'elemento "map" è stato trovato, puoi ora creare la mappa TomTom
      const point = [longitude, latitude];

      try {
        let map = tt.map({
            key: "HmfWKAQTl23dOsGqCArlxGZ4o2Jx6Q02",
            container: 'map',
            center: point,
            zoom: 15,
        });

        //  map.addControl(new tt.FullscreenControl());
        //  map.addControl(new tt.NavigationControl(), 'top-left');
        // console.log(point)
         map.on('load', () => {
           new tt.Marker().setLngLat(point).addTo(map);
         });

      } catch (error) {
        console.error('Si è verificato un errore nella richiesta al servizio di geocodifica di TomTom:', error);
      }
    } else {
      console.error('L\'elemento con id "map" non è stato trovato nel DOM.');
    }
  },

        async getSingleApartment(){
            this.store.loading = true;
            try {
                const response = await axios.get(`${store.baseUrl}/api/apartment/${this.$route.params.slug}`)
           
                this.apartment = response.data.apartment;
                this.apartment_id = this.apartment.id;
                console.log(this.apartment_id);
                
                this.getMap(this.apartment.longitude, this.apartment.latitude);

                // if (this.apartment.address) {
                //     this.getTom(this.apartment.address)
                // }
                this.store.loading = false;
            } catch (error) {
                if(this.res.data.data.success){
                    this.$router.push({name: 'NotFound'})
                }
            }
        },
       
        sendForm() {
    
            const data = {
                name: this.name,
                email: this.email,
                content: this.content,
                apartment_id: this.apartment_id
            } 
    
            this.loading = true;
    
            axios.post(`${this.store.baseUrl}/api/mail`, data ).then( response => {
    
                console.log(response)
                this.success = response.data.success;
                if(!this.success){
                    this.errors = response.data.errors;
                }
                else{
                    //PULISCO I DATI IN INPUT
                    this.name = '';
                    this.email = '';
                    this.message = '';
                    this.apartment_id = '';
    
                    this.$router.push({ name: 'thank-you' })
                }
                this.loading = false
            });
    
        },
    },
    mounted(){
        this.getSingleApartment();
    },
}
</script>
<template>
    <div class="container mb-5">

        <div v-if="apartment" class="row">
            <div class="left col-12 col-md-12 col-lg-7 px-5">
    
                <!-- cover -->
                <div>
                    <div
                    v-if="apartment.img"
                    class=""
                    :style="{
                        backgroundImage: `url('${this.store.baseUrl}/storage/${apartment.img}')`,
                        backgroundSize: 'cover',
                        backgroundPosition: 'center',
                        height: '400px',
                        width: '100%',
                    }"
                    ></div>
                    <div
                        v-else
                        class="card-img my_card"
                        style="background-image: url('https://vestnorden.com/wp-content/uploads/2018/03/house-placeholder.png'); background-size: cover; background-position: center; height: 300px; width: '100%'"
                    ></div>
                </div>                
              

                <!-- mappa -->
                <div id='map' class='map my-5 margine'>
                </div>
            </div>

            <!-- details -->
            <div class="right col-12 col-md-12 col-lg-5 px-4">
            
                    <div class="card p-3">
    
                    <!-- title and address -->
                    <ul class="p-0">
                        <li>
                            <h2 class="border-bottom mb-3 pb-3">{{ apartment.title }}</h2>
                        </li>
                        <li>
                            <h6 class="mt-2">
                                <i class="fa-solid fa-location-dot"></i>
                                {{ apartment.address }}
                            </h6>
                        </li>
                    </ul>
    
                    <!-- info appartamento -->
                    <h4 class="mt-3 mb-2">Info Appartamento</h4>
                    <ul class="p-0 d-flex flex-column row-gap-2">
                        <li>
                            <p>{{ apartment.description }}</p>
                        </li>
                        <li>
                            <strong>Stanze letto: </strong>
                            {{ apartment.n_rooms }}
                        </li>
                        <li>
                            <strong>Bagni: </strong>
                            {{ apartment.n_beds }}
                        </li>
                        <li>
                            <strong>Metri quadri: </strong>
                            {{ apartment.mq }} mq
                        </li>
                        <li v-if="apartment.price">
                            <strong>Prezzo: </strong>
                            {{ apartment.price }} &euro;
                        </li>
                    </ul>
    
                    <!-- servizi -->
                    <div>
                        <h5 class="mt-3 mb-2"> Servizi della struttura</h5>
                        <div class="d-flex flex-wrap gap-2">
    
                            <span v-for="( elem, index ) in apartment.services" :key="index" class="p-1 mt-1 card d-inline"> 
                                <i :class="`fa-solid ${ elem.icon } me-1`"></i> {{  elem.name }} 
                            </span>
                        </div>
                    </div>
            </div>

                <!-- form contatta la truttura -->
                <form class="card p-4 mt-5 mb-4 margine" @submit.prevent="sendForm()">
                    <h3 class="mb-2">Contatta la struttura</h3>

                    <!-- name -->
                    <div class="mb-3">
                        <label for="" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" placeholder="Nome" name="name" v-model="name">
                        <p v-for="(error, index) in errors.name" :key="index" class="text-danger">
                            {{ error }}
                        </p>
                    </div>
    
                    <!-- email -->
                    <div class="mb-3">
                        <label for="" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" v-model="email">
                        <p v-for="(error, index) in errors.email" :key="index" class="text-danger">
                            {{ error }}
                        </p>
                    </div>
    
                    <!-- message -->
                    <div class="mb-3">
                        <div class="mb-3">
                            <label for="" class="form-label">Messaggio</label>
                            <textarea class="form-control" id="content" rows="3" name="content" v-model="content"></textarea>
                            <p v-for="(error, index) in errors.content" :key="index" class="text-danger">
                                {{ error }}
                            </p>
                        </div>
                    </div>

                    <!-- apartment_id -->
                    <input type="hidden" name="apartment_id" id="apartment_id" v-model="apartment_id">
    
                    <!-- button -->
                    <button type="submit" class="btn" :disabled="loading">
                        <span v-if="!loading">Invia</span>
                        <span v-else>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span role="status">Caricamento...</span>
                        </span>
                    </button>

                    <!-- confirmation modal -->
                    <div class="modal mt-5" id="confirmationModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- header -->
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        Messaggio inviato con successo!
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <!-- footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn" data-bs-dismiss="modal">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.container {
    margin-top: 100px;

    .row {
            // maps
            .map {
                aspect-ratio: 3/2;
                width: 100%;
                border-radius: 20px;
            }
        }

        // right
        .right {

            ul {
                list-style: none;
            }

            // form
            form {
                box-shadow: rgba(50, 50, 93, 0.20) 0px 10px 30px -20px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
            
                .btn {
                    background-color: #C6AB7C;
                    color: white;
                }
            }
        }
    }


</style>