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
        data: "",
        title: "",
        description: ""

    },
    methods:{
         getLink:function(e) {
                 let target = e.target;
                 this.data = target.getAttribute("href");
             axios
                 .get('http://localhost:1025'+this.data)
                 .then((function (response) {this.title = response.data.task.title;
                     this.description = response.data.task.description}).bind(this));
}

    },

});

document.getElementById('copier').addEventListener('click', function(e) {
    let copier = document.getElementById('copier')
    let div = document.getElementById('textCopy');
    let copytext = document.createElement('input');
    let Textcopy = document.createElement('div');
    let copierHref = copier.getAttribute("href");
    Textcopy.innerText = 'Text copy';
    div.append(Textcopy);
    copytext.value = copierHref;
    document.body.appendChild(copytext);
    copytext.select();
    document.execCommand('copy');

});


