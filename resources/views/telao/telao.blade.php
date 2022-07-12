@extends("layouts.app")

@section('content')

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Telao</title>
        <link href="{{ asset('css/telao.css') }}" rel="stylesheet">
        
    </head>

    <script src="js/script.js"></script>

    <body onload="teste(); time(); data()">

        <div>
            <header class="cabecalho">    
            <img class="cabecalho-img" src="/img/logo_senai_branco.png" alt="logo_senai">
            </header>
        <main>
            <section class="senha-guiche">
                <a class="senha"><strong>SENHA</strong>
                <a class="guiche"><strong>GUICHÊ</strong>
            </section>
            <section class="numero-tabela">
                <header class="vertical-line-2"></header>
                <a id="senhaAtual" class="senhaTelao">M-08</a>
                <a class="guicheTelao" id="nmrGuiche">02</a>
            </section>
            <div>
                <h1 class="hora" id="hora"></h1>
                <h1 class="data" id="data"></h1>
            <table class="primeiro-numero" id="primeiroFila">
                <tr>
                    <th class="tabela-1">SENHAS ANTERIORES</th>
                    <th class="tabela-1"onclick="callNext()">GUICHÊ</strong></th> 
                    <h1 id="data"></h1>
                </tr>
            </table>
        </div>
            </div>
            </main>
        </div>
    </body>
</html> 