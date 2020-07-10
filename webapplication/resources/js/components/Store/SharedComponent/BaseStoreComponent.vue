<template>
<div >
    <!-- <h3>{{counter}}{{anothercounter}}</h3> -->
    <router-view></router-view>
</div>
    
</template>

<script>
export default {
    computed:{
        counter(){
            return this.$store.getters.doublegets;
        },
        anothercounter(){
            return this.$store.getters.Anotterdoublegets;
        }
    }
}
</script>