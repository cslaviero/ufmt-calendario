<?php

//DEFINE O LOGIN E SENHA PARA CONEXÃO COM O BANCO DE DADOS
$ConnLocal = "localhost";
$ConnLogin = "root";
$ConnSenha = "";
$ConnDatabase = "calendario";

// CONECTA-SE COM O BANCO DE DADOS MySQLi
global $Conn;
$Conn = new mysqli($ConnLocal, $ConnLogin, $ConnSenha, $ConnDatabase);

mysqli_set_charset($Conn, "utf8"); // define o charset dos dados, será o mesmo charset das páginas
if (mysqli_connect_errno()) trigger_error(mysqli_connect_error()); // imprimir alguma mensagem caso houve erro

// função para retornar a ID do período que está ativo no momento (período atual)
function periodo_atual ($d, $c){ // parâmetro $d é a data atual, $c é para conectar do banco de dados
    $sqll = "SELECT prd_id, prd_data_ini, prd_data_fim FROM tbl_periodos;";
    $exel = $c->query($sqll);

    $periodo = null;

    while ($reg = $exel->fetch_array()) {
        if(strtotime($reg['prd_data_ini']) <= strtotime($d)
            && strtotime($reg['prd_data_fim']) >= strtotime($d)){ // verifica pela data atual qual o período que está dentro deste intervalo
            $periodo = $reg['prd_id']; // quando achar, imprime a id
        }
    }

    return $periodo; // retorna a id
}

// função para receber o mês em dígitos numéricos e retornar em texto abreviado
function mes ($m){
    switch ($m) {
        case "01":    $mes = "Jan";   break;
        case "02":    $mes = "Fev";   break;
        case "03":    $mes = "Mar";   break;
        case "04":    $mes = "Abr";   break;
        case "05":    $mes = "Mai";   break;
        case "06":    $mes = "Jun";   break;
        case "07":    $mes = "Jul";   break;
        case "08":    $mes = "Ago";   break;
        case "09":    $mes = "Set";   break;
        case "10":    $mes = "Out";   break;
        case "11":    $mes = "Nov";   break;
        case "12":    $mes = "Dez";   break;
    }

    return $mes; // retorna o mês abreviado
}

// função para receber o mês em dígitos numéricos e retornar em texto completo
function mescomp ($m){
    switch ($m) {
        case "01":    $mesc = "JANEIRO";     break;
        case "02":    $mesc = "FEVEREIRO";   break;
        case "03":    $mesc = "MARÇO";       break;
        case "04":    $mesc = "ABRIL";       break;
        case "05":    $mesc = "MAIO";        break;
        case "06":    $mesc = "JUNHO";       break;
        case "07":    $mesc = "JULHO";       break;
        case "08":    $mesc = "AGOSTO";      break;
        case "09":    $mesc = "SETEMBRO";    break;
        case "10":    $mesc = "OUTUBRO";     break;
        case "11":    $mesc = "NOVEMBRO";    break;
        case "12":    $mesc = "DEZEMBRO";    break;
    }

    return $mesc; // retorna o mês completo
}