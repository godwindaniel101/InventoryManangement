<template>
    <div class="">
       <h2>Userddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd </h2>
       <div class=""> 
            <router-view></router-view>
       </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
<style scoped>
.container{
    background:red;
    border: solid green 2px;
    }
</style>