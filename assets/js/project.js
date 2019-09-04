new Vue({
    el: '#app',
    delimiters: ['${', '}'],
    data: {
        social: 'test',
    },

    methods: {
        push:function(event){
    let inputId1 = document.getElementById("inputId1");
            let div = document.getElementById("containerInput");
            let value = inputId1.value;
            inputId1.value= "";
            let link = document.createElement("a");
            let input = document.createElement("input");
            let button = document.createElement("button");
            button.innerText = 'delete user';
            input.id = value;
            button.id = value+1;
            button.className = 'deleteButton';
            // input.setAttribute("disabled" ,"disabled");
            input.setAttribute("data-email" ,value);
            input.setAttribute("type" ,'text');
            input.setAttribute("name" ,"project[user]["+value+"]");
            button.setAttribute("data-email" ,value);
            div.append(link);
            link.append(input);
            link.append(button);
         /*   let getButton = document.getElementsByClassName('deleteButton');
            button.addEventListener('click', function(e){
                let target = e.target;
                let data = target.getAttribute("data-email");
                document.getElementById(data).remove();
                document.getElementById(data+1).remove();
            });*/

    input.value = value;
            event.preventDefault();
},
        deleteButtonClick:function(){
            let getButton = document.querySelectorAll('containerInput');
         for (let i =0; i < getButton.length; i++){
             console.log(getButton[i])
         }
        },


/*deleteButton:function () {
        let deleteButton = document.getElementsByClassName('deleteButton');
        for (let i =0; i < deleteButton.length; i++){
        deleteButton[i].addEventListener('click', function(e){
        let target = e.target;
        let data = target.getAttribute("data-email");
        document.getElementById(data).remove();
        document.getElementById(data+1).remove();
        alert('test')
    })}
}*/

    },
    created: function () {
        this.deleteButtonClick()
    },
});


