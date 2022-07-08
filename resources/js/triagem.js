document.addEventListener("DOMContentLoaded", function(evt){
    document.getElementById("btn-buscar-triagem").addEventListener("click", function(){
        const id = document.getElementById("buscar-triagem")
        buscarId(id.value)
    })
})

const buscarId = function(id){

    triagem = document.getElementById("requerimento")
    triagem.innerHTML = " "

    add = document.getElementById("triagem-add")
    add.innerHTML = " "

            
    const uri = `https://central-atendimento-cliente.herokuapp.com/api/atendimento/id/${id}`
    fetch(uri).then(r => r.json().then(r => {

        requerimento.innerHTML += `<li class="list-group-item"> Senha:  ${r.numero_atendimento}${r.sufixo_atendimento}</li>`
        requerimento.innerHTML += `<li class="list-group-item"> CPF:  ${r.cpf}</li>`
        requerimento.innerHTML += `<li class="list-group-item"> Data e Hora da Emissao:  ${r.date_time_emissao_atendimento}</li>`
        requerimento.innerHTML += `<li class="list-group-item"> Estado:  ${r.inicio_atendimento}</li>`
        requerimento.innerHTML += `<li class="list-group-item"> Atendimento:  ${r.fim_atendimento}</li>`
        requerimento.innerHTML += `<li class="list-group-item"> ID Atendimento:  ${r.id_atendimento}</li>`
        requerimento.innerHTML += `<li class="list-group-item"> Observações:  ${r.observacoes}</li>`


        add.innerHTML += `<div class="form-group col-md-6">
        <label for="inputEmail4">CPF</label>
        <input type="text" class="form-control" id="cpf" placeholder="CPF">
      </div>
    </div>
    <div class="form-group">
      <label for="inputAddress">Observacoes</label>
      <input type="text" class="form-control" id="obs" placeholder="Observacoes">
    </div>
    <div class="form-group col-md-4">
      <label for="inputState">Servico Prestado</label>
      <select id="inputState" class="form-control">
        <option selected>Choose...</option>
        <option>...</option>
      </select>
    </div>
      <div class="form-group col-md-2">
        <label for="inputState">Sufixo Servico</label>
        <select id="inputState" class="form-control">
          <option selected>Selecione...</option>
          <option>FRC</option>
          <option>PDG</option>
          <option>SCT</option>
          <option>OTS</option>
        </select>
        <br>
    <button type="button" id="btn-enviar-triagem" class="btn btn-primary">Enviar</button>
      </div>`

    }))
}


