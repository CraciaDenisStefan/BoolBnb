<script>
import { store } from '../store.js';
export default {
    props: {
        apartment: Object,
    },
   
    data() {
        return {
            maxCaracters: 30,
            store
        }
    },
    methods:{
        truncateText(text) {
            if (text.length > this.maxCaracters) {
                return text.substr(0, this.maxCaracters) + '...';
            }
                return text
            }
    }
}
</script>
<template>
        <div class="card border m-2 border-danger rounded-4 min-height">
            <div
              v-if="apartment.img"
              class="card-img my_card shadow-lg d-flex justify-content-center border-bottom border-danger rounded-top-4"
              :style="{
                backgroundImage: `url('${this.store.baseUrl}/storage/${apartment.img}')`,
                backgroundSize: 'cover',
                backgroundPosition: 'center',
                height: '300px',
                width: '100%',
              }"
            ></div>
          <div
            v-else
            class="card-img my_card"
            style="background-image: url('https://vestnorden.com/wp-content/uploads/2018/03/house-placeholder.png'); background-size: cover; background-position: center; height: 300px; width: '100%'"
          ></div>
          <div class="card-body">
            <h5 class="card-title" v-if="apartment.title">{{ apartment.title }}</h5>
            <p class="card-text truncate-text" v-if="apartment.description">{{ truncateText(apartment.description) }}</p>
            <p class="card-text"><strong><i class="fa-solid fa-location-dot"></i> </strong> {{ apartment.address }}</p>
            <p class="card-text"><strong><i class="fa-solid fa-bed"></i></strong> {{ apartment.n_beds }}</p>
            <p class="card-text"><strong><i class="fa-solid fa-door-open"></i> </strong> {{ apartment.n_rooms }}</p>
            <span class="card-text me-1"><strong><i class="fa-solid fa-circle-info"></i></strong> </span>
            <span v-if="apartment.services && apartment.services.length > 0">
                <span v-for="(service, index) in apartment.services" :key="index">
                    {{ service.name }}{{ index < apartment.services.length - 1 ? ', ' : '' }}
                </span>
            </span>
            <span v-else>Nessun servizio disponibile</span>
          </div>
        </div>
  </template>
<style lang="scss">
    
</style>