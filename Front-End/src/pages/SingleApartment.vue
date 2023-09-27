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
            name: 'SingleApartment',
            store,
            apartment: []
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
          zoom: 15
        });

        map.addControl(new tt.FullscreenControl());
        map.addControl(new tt.NavigationControl());

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
       
    },
    mounted(){
        this.getSingleApartment();
    },
}
</script>
<template>
    
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h1>{{ apartment.title }}</h1>
                        <div id="map" class="map">

                        </div>
                </div>
            </div>
        </div>
</template>
<style lang="scss">
    
</style>