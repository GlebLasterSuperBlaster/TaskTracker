Vue.component('modal', {
    template: '#modal-template'
});


// start app
new Vue({
    el: '#app',
    delimiters: ['${', '}'],
    data: {
        showModal: false
    }
});


