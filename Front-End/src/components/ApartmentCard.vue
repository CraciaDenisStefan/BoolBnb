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
    <div class="card border m-2 border-danger rounded-4">
      <div class="row g-0">
          <div class="col-12">
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
            style="background-image: url('https://vestnorden.com/wp-content/uploads/2018/03/house-placeholder.png'); background-size: cover; background-position: center; height: 150px;"
          ></div>
        </div>
        <div class="col-md-12">
          <div class="card-body">
            <h5 class="card-title" v-if="apartment.title">{{ apartment.title }}</h5>
            <p class="card-text truncate-text" v-if="apartment.description">{{ truncateText(apartment.description) }}</p>
            <p class="card-text"><strong>Indirizzo:</strong> {{ apartment.address }}</p>
            <p class="card-text"><strong>Numero di letti:</strong> {{ apartment.n_beds }}</p>
            <p class="card-text"><strong>Numero di stanze:</strong> {{ apartment.n_rooms }}</p>
            <p class="card-text"><strong>Servizi: </strong> 
                <span v-for="(service, index) in apartment.services" :key="index">
                    {{ service.name }}{{ index < apartment.services.length - 1 ? ', ' : '' }}
                </span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </template>
<style lang="scss">
    
</style>