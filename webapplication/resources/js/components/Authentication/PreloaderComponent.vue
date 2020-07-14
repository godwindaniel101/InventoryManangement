<template>
    <div class="loaderWrapper">
       <div class="noRecordcontianer" v-if="status == 'loading'">
                 <div class="noRecordImage">
                        <img src="/image/preloader.gif"/>
                 </div>
                 <div class="noRecordText" v-show="showtexted">
                        <p v-text="status"></p>
                  </div>
         </div>
         <div class="noRecordcontianer" v-else-if="status == 'error'">
                 <div class="noRecordImage">
                        <img src="/image/error.png"/>
                 </div>
                 <div class="noRecordText" v-show="showtexted">
                        <p v-text="status"></p>
                  </div>
         </div>
         <div class="noRecordcontianer" v-else-if="status == 'good'">
                 <div class="noRecordImage">
                        <img src="/image/good.png"/>
                 </div>
                 <div class="noRecordText" v-show="showtexted">
                        <p v-text="status"></p>
                  </div>
         </div>
         <div v-else>
                
         </div>
    </div>
</template>

<script>
    export default {
        props:{
            status,
            showtexted:{
            type: Boolean,
            require: true
        },
        data(){
            return{
                // showtexted : showtexted
            }
        }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
