import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export const store = new Vuex.Store({
    state : {
        counter:0,
    },
    getters:{
        doublegets : state =>{
            return ( state.counter * 2)
        },
        Anotterdoublegets : state =>{
            return ( state.counter + 'clicks')
        }
    }
})