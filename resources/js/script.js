
function callAlert(){
    location.href = "alert.html"
}

function EmitirSenha(sufixo_atendimento) {

    //localStorage.setItem("senha", bot.value)
    //alert(bot.value)
    const endPoint = 'https://central-atendimento-cliente.herokuapp.com/api/';
    const route = 'atendimento/post';
    const cpf = document.getElementById("campo-texto").value;
    const date = '?' + 'cpf=' + cpf + '&' + 'sufixo_atendimento=' + sufixo_atendimento;

    const initDetails = {
        method: 'post',
        headers: {
            "Content-Type": "application/json; charset=utf-8"
        },
        mode: "cors"
    }
    
    fetch((endPoint + route + date), initDetails )
        .then( response =>
        {
            if ( response.status !== 200 )
            {
                console.log( 'Looks like there was a problem. Status Code: ' +
                    response.status );
                return;
            }

            console.log( response.headers.get( "Content-Type" ) );
            return response.json();
        }
        ).then( myJson =>
        {
            console.log( JSON.stringify( myJson ) );
            alert( JSON.stringify( myJson ) )
        } )
        .catch( err =>
        {
            console.log( 'Fetch Error :-S', err );
        } );
}


function GetData() {

    //localStorage.setItem("senha", bot.value)
    //alert(bot.value)

    const endPoint = 'https://central-atendimento-cliente.herokuapp.com/api/';
    const route = 'atendimento/post';
    const servico = document.getElementById("servico").text;
    const cpf = document.getElementById("campo-texto").value;
    const sufixo_atendimento = 'FCR';
    const date = '?' + 'cpf=' + cpf + '&' + 'sufixo_atendimento=' + sufixo_atendimento;


    const initDetails = {
        method: 'post',
        headers: {
            "Content-Type": "application/json; charset=utf-8"
        },
        mode: "cors"
    }
    
    fetch((endPoint + route + date), initDetails )
        .then( response =>
        {
            if ( response.status !== 200 )
            {
                console.log( 'Looks like there was a problem. Status Code: ' +
                    response.status );
                return;
            }

            console.log( response.headers.get( "Content-Type" ) );
            return response.json();
        }
        ).then( myJson =>
        {
            console.log( JSON.stringify( myJson ) );
            alert( JSON.stringify( myJson ) )
        } )
        .catch( err =>
        {
            console.log( 'Fetch Error :-S', err );
        } );
}


function servicoFinanceiro(){
    servicos = document.getElementById("opcoesFcr")
    servicos.innerHTML = " "

    const uri = `https://central-atendimento-cliente.herokuapp.com/api/servicos/fcr`
    fetch(uri).then(r => r.json().then(r =>{
        r.forEach(r1 => {

            servicos.innerHTML += ` <div class="box-opt">
            <label for="opt1" id="">
                <input type="checkbox" value="opcao1" name="opt" id="">
                ${r1.servico}
            </label>
        </div> `
            console.log(r1.servico)
        });
    }))

}

function servicoPedagogico(){
    servicos = document.getElementById("opcoesPdg")
    servicos.innerHTML = " "

    const uri = `https://central-atendimento-cliente.herokuapp.com/api/servicos/pdg`
    fetch(uri).then(r => r.json().then(r =>{
        r.forEach(r1 => {

            servicos.innerHTML += ` <div class="box-opt">
            <label for="opt1" id="">
                <input type="checkbox" value="opcao1" name="opt" id="">
                ${r1.servico}
            </label>
        </div> `
            console.log(r1.servico)
        });
    }))

}

function servicoSecretaria(){
    servicos = document.getElementById("opcoesSct")
    servicos.innerHTML = " "

    const uri = `https://central-atendimento-cliente.herokuapp.com/api/servicos/sct`
    fetch(uri).then(r => r.json().then(r =>{
        r.forEach(r1 => {

            servicos.innerHTML += ` <div class="box-opt">
            <label for="opt1" id="">
                <input type="checkbox" value="opcao1" name="opt" id="">
                ${r1.servico}
            </label>
        </div> `
            console.log(r1.servico)
        });
    }))

}

function servicoOutrosServicos(){
    servicos = document.getElementById("opcoesOts")
    servicos.innerHTML = " "

    const uri = `https://central-atendimento-cliente.herokuapp.com/api/servicos/ots`
    fetch(uri).then(r => r.json().then(r =>{
        r.forEach(r1 => {

            servicos.innerHTML += ` <div class="box-opt">
            <label for="opt1" id="">
                <input type="checkbox" value="${r1.servico}" name="opt" id="">
                ${r1.servico}
            </label>
        </div> `
            console.log(r1.servico)
        });
    }))

} 