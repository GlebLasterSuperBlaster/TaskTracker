new Vue({
    el: '#app',
    delimiters: ['${', '}'],
    data: { },
    methods: {
        push:function(event){
    let inputId1 = document.getElementById("inputId1");
            let div = document.getElementById("containerInput");
            let value = inputId1.value;
            inputId1.value= "";
            let divForInpuT = document.createElement("div");
            let link = document.createElement("a");
            let input = document.createElement("input");
            let button = document.createElement("button");
            divForInpuT.className = "forInputAndButton";
            link.className ='LinkForInput';
            button.innerText = 'delete user';
            input.id = value;
            input.className = 'inputProjectMenuForUser';
            button.id = value+1;
            button.className = 'deleteButton';
            // input.setAttribute("disabled" ,"disabled");
            input.setAttribute("data-email" ,value);
            input.setAttribute("type" ,'text');
            input.setAttribute("name" ,"project[user]["+value+"]");
            button.setAttribute("data-email" ,value);
            if (value != null && typeof value !== "undefined" && value !== "" ){
                div.append(divForInpuT);
                divForInpuT.append(link);
                divForInpuT.append(button);
            } else {console.log('empty')}
            link.append(input);
            button.onclick = function(e){
                let target = e.target;
                let data = target.getAttribute("data-email");
                document.getElementById(data).remove();
                document.getElementById(data+1).remove();
            };
    input.value = value;
            event.preventDefault();




        },

        test: function (e) {
            let target = e.target;
            let data = target.getAttribute("data-email");
            document.getElementById(data).remove();
            document.getElementById(data+1).remove();
        }
    },

   /* created: function(){
        this.deleteButtonClick()
    }*/

});


