<script>
export default {
    data() {
        return {
            name: 'AppLoading'
        }
    },
}
</script>
<template>
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only"></span>
        </div>
    </div>
</template>
<style lang="scss">
    @use '../styles/generals.scss' as *;

    .loading-spinner{
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
</style>