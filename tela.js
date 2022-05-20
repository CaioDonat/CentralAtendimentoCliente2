
        function GetData() {

            const bot = document.getElementById("campo-texto");
            //localStorage.setItem("senha", bot.value)
            //alert(bot.value)

            const uri = 'https://central-atendimento-cliente.herokuapp.com/api/atendimento/post';
                    const initDetails = {
                        method: 'post',
                        headers: {
                            "Content-Type": "application/json; charset=utf-8"
                        },
                        mode: "cors"
                    }

            fetch((uri +bot.value), initDetails )
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
                    localStorage.setItem("requestResponse", JSON.stringify( myJson ));
                } )
                .catch( err =>
                {
                    console.log( 'Fetch Error :-S', err );
                } );
        }