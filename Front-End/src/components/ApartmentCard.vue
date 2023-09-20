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
<template >
    <div class="card border m-2" >
        <div class="row g-0">
            <div  class="col-md-4">
                <div v-if="apartment.img" class="card-img my_card shadow-lg"
                    :style="{ backgroundImage: `url('${this.store.baseUrl}/storage/${apartment.img}')`,backgroundSize: 'cover', backgroundPosition: 'center', height: '150px'  }">
                </div>
                <div v-else class="card-img my_card"
                    style="background-image: url('https://vestnorden.com/wp-content/uploads/2018/03/house-placeholder.png'); background-size: cover; background-position: center; height: 150px;">
                </div>
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title" v-if="apartment.title">{{ apartment.title }}</h5>
                    <p class="card-text truncate-text" v-if="apartment.description">{{ truncateText(apartment.description) }}</p> 
                </div>            
            </div>
        </div>
    </div>
</template>
<style lang="scss">
    
</style>