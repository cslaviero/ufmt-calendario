<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

/*$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});*/

$app->post('/usuarios', function (Request $request, Response $response) {
    require "conecta.php";
    $date = date("Y-m-d H:i:s");

    $email = filter_var(filter_input(INPUT_POST, 'email'), FILTER_SANITIZE_EMAIL);

    $stmt = $Conn->prepare("SELECT usu_id, usu_nome, usu_email, usu_usuario, usu_senha FROM tbl_usuarios WHERE usu_email = ? LIMIT 1");
    $stmt->bind_param('s', $email);  // Relaciona  "$email" ao parâmetro.
    $stmt->execute();    // Executa a tarefa estabelecida.
    $stmt->store_result();
    $cont = $stmt->num_rows;

    // obtém variáveis a partir dos resultados.
    $stmt->bind_result($usu_id,$usu_nome,$usu_email,$usu_usuario,$usu_senha);
    $stmt->fetch();

    $user = array();
    $user[0]['cont']    = $cont;
    $user[0]['id']      = $usu_id;
    $user[0]['nome']    = $usu_nome;
    $user[0]['email']   = $usu_email;
    $user[0]['usuario'] = $usu_usuario;
    $user[0]['senha']   = $usu_senha;

    return $response->withJson($user);
});

$app->get('/periodos', function (Request $request, Response $response) {
    require "conecta.php";
    $date = date("Y-m-d H:i:s");

    $sql = "SELECT prd_id, prd_nome, prd_data_ini, prd_data_fim FROM tbl_periodos;";
    $exe = $Conn->query($sql);
    $cont = $exe->num_rows;

    $prd = array();
    $i   = 0;
    $prd[$i]['cont'] = $cont;

    while ($reg = $exe->fetch_array()) {
        $prd[$i]['id']          = $reg['prd_id'];
        $prd[$i]['nome']        = $reg['prd_nome'];
        $prd[$i]['data_ini']    = $reg['prd_data_ini'];
        $prd[$i]['data_fim']    = $reg['prd_data_fim'];
        if(strtotime($prd[$i]['data_ini']) <= strtotime($date)
            && strtotime($prd[$i]['data_fim']) >= strtotime($date)){
            $prd[$i]['ativo']   = "1";
        } else {
            $prd[$i]['ativo']   = "0";
        }
        $i++;
    }
    //echo var_dump($prd);
    return $response->withJson($prd);
});

$app->get('/campus', function (Request $request, Response $response) {
    require "conecta.php";
    $date = date("Y-m-d H:i:s");

    $sql = "SELECT cps_id, cps_nome FROM tbl_campus;";
    $exe = $Conn->query($sql);
    $cont = $exe->num_rows;

    $prd = array();
    $i   = 0;
    $prd[$i]['cont'] = $cont;

    while ($reg = $exe->fetch_array()) {
        $prd[$i]['id']      = $reg['cps_id'];
        $prd[$i]['nome']    = $reg['cps_nome'];
        $i++;
    }

    return $response->withJson($prd);
});

$app->get('/categorias', function (Request $request, Response $response) {
    require "conecta.php";

    $sql = "SELECT cat_id, cat_nome, cat_cor FROM tbl_categoria;";
    $exe = $Conn->query($sql);
    $cont = $exe->num_rows;

    $prd = array();
    $i   = 0;
    $prd[$i]['cont'] = $cont;

    while ($reg = $exe->fetch_array()) {
        $prd[$i]['id']      = $reg['cat_id'];
        $prd[$i]['nome']    = $reg['cat_nome'];
        $prd[$i]['cor']     = $reg['cat_cor'];
        $i++;
    }

    return $response->withJson($prd);
});

$app->get('/prox_eventos', function (Request $request, Response $response) {
    require "conecta.php";
    $date = date("Y-m-d H:i:s");

    $sql = "SELECT * FROM tbl_eventos;";
    $exe = $Conn->query($sql);
    $cont = $exe->num_rows;

    $prd = array();
    $i   = 0;
    $prd[$i]['cont'] = $cont;

    while ($reg = $exe->fetch_array()) {
        $prd[$i]['id']          = $reg['eve_id'];
        $prd[$i]['periodo']     = $reg['eve_periodo'];
        $prd[$i]['campus']      = $reg['eve_campus'];
        $prd[$i]['categoria']   = $reg['eve_categoria'];
        $prd[$i]['nome']        = $reg['eve_nome'];
        $prd[$i]['local']       = $reg['eve_local'];
        $prd[$i]['data_ini']    = $reg['eve_data_ini'];
        $prd[$i]['data_fim']    = $reg['eve_data_fim'];
        $prd[$i]['desc']        = $reg['eve_descricao'];
        $prd[$i]['url']         = $reg['eve_url'];
        $i++;
    }

    return $response->withJson($prd);
});