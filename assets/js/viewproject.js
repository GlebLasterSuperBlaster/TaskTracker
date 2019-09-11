import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(VueAxios, axios);
Vue.component('modal', {
    template: '#modal-template'
});


// start app
new Vue({
    el: '#app',
    delimiters: ['${', '}'],
    data: {
        showModal: false,
        info: {items: []},
        data: "",

    },
    methods:{
         getLink:function(e) {
                 let target = e.target;
                 this.data = target.getAttribute("href");
             axios
                 .get('http://localhost:1025'+this.data)
                 .then(response => (this.info = response.data.task));
},
        inputcontent:function () {
            let div = document.getElementById("divTaskTitle");
            let input = document.getElementById("inputTaskTitle");
            let divContent = div.innerText;
            input.value = divContent

        }
    },
    updated() {
        this.inputcontent()
    }

});


