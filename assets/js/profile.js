new Vue({
    el:'#app',
    delimiters: ['${', '}'],
    data: {
        errors:"",
        user_password_first: null,
        user_password_second:null,
    },
    methods: {
        checkForm:function(e) {
            if(this.user_password_first === this.user_password_second) return true;
            this.errors = "";
            if(this.user_password_first !== this.user_password_second) this.errors+=("ERROR Passwords not match.");
            e.preventDefault();
        },

}});
