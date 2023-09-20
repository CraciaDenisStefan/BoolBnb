<script >
import AppHeader from './components/AppHeader.vue';
export default {
  components:{
      AppHeader
    },
}
</script>

<template>
  <div>
    <AppHeader />
    <router-view class="padding-top"></router-view>
  </div>
</template>

<style lang="scss">
  @use './styles/generals.scss' as *;

  .padding-top{
    padding-top: 100px;
  }
</style>
