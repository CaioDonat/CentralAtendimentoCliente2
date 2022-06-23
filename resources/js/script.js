
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
