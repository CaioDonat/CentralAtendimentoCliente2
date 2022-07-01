document.addEventListener("DOMContentLoaded", function(evt){
    document.getElementById("btn-config").addEventListener("click", function(){
        const select = document.getElementById('select-setor');
        value = select.options[select.selectedIndex].value;
        selecionarSetor(select.value)
        console.log(value)

    })
})


const selecionarSetor = function(value){

    servicos = document.getElementById("servicos-setores")
    servicos.innerHTML = " "
    post = document.getElementById("post-triagem")
    post.innerHTML = " "


    const uri = `https://central-atendimento-cliente.herokuapp.com/api/servicos/${value}`
    fetch(uri).then(r => r.json().then(r =>{
        r.forEach(r1 => {

            servicos.innerHTML += ` <li class="list-group-item">${r1.servico}<button type="button" class="btn mx-3 btn-outline-danger"><i class="fa-solid fa-trash"></i></button></li>`
        });
        post.innerHTML += `<div class="form-group">
            <label for="inputAddress">Adicionar Servico</label>
            <input type="text" class="form-control" id="newServico" placeholder="Novo Servico">
            <button type="button" id="put" class=" mt-2 btn btn-success">Adicionar Servico <i class="fa-solid fa-plus"></i> </button>
          </div>`
    }) )

}