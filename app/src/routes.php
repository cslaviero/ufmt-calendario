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

$app->post('/push/clientes', function (Request $request, Response $response) {
    $token = $_POST['token'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://iid.googleapis.com/iid/v1/".$token."/rel/topics/clientes",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POST, true,
        CURLOPT_HTTPHEADER => array(
            "authorization: key=AAAAVaQJ-YM:APA91bG3bY56WZTnryd4c49Ri1hCywt8uj9z2SHD3srcmB9yI_rtgyBDyfNUYu3Rbr0fQI4w4Di1flIPk0yrdYAaIsbEo-tCzLGdwojHJGuRIjwahGbcP5ZKaQ1sFwBe02tokYhOHvoM",
            "content-type: application/json",
            "content-length: 0"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        return $response;
    }
});

$app->get('/periodos', function (Request $request, Response $response) {
    require "conecta.php";
    $date = date("Y-m-d H:i:s");

    $sql = "SELECT prd_id, prd_nome, prd_data_ini, prd_data_fim, prd_url FROM tbl_periodos;";
    $exe = $Conn->query($sql);
    $cont = $exe->num_rows;

    $prd = array();
    $i   = 0;
    $prd[$i]['cont'] = $cont;

    while ($reg = $exe->fetch_array()) {

        if(strtotime($reg['prd_data_ini']) <= strtotime($date)
            && strtotime($reg['prd_data_fim']) >= strtotime($date)){
            if(isset($prd[0]['id'])){
                $prd[$i]['id']       = $prd[0]['id'];
                $prd[$i]['nome']     = $prd[0]['nome'];
                $prd[$i]['data_ini'] = $prd[0]['data_ini'];
                $prd[$i]['data_fim'] = $prd[0]['data_fim'];
                $prd[$i]['url']      = $prd[0]['url'];
                $prd[$i]['ativo']    = $prd[0]['ativo'];

                $prd[0]['id']       = $reg['prd_id'];
                $prd[0]['nome']     = $reg['prd_nome'];
                $prd[0]['data_ini'] = $reg['prd_data_ini'];
                $prd[0]['data_fim'] = $reg['prd_data_fim'];
                $prd[0]['url']      = $reg['prd_url'];
                $prd[0]['ativo']    = "1";
            } else {
                $prd[0]['id']       = $reg['prd_id'];
                $prd[0]['nome']     = $reg['prd_nome'];
                $prd[0]['data_ini'] = $reg['prd_data_ini'];
                $prd[0]['data_fim'] = $reg['prd_data_fim'];
                $prd[0]['url']      = $reg['prd_url'];
                $prd[0]['ativo']    = "1";
            }
        } else {
            $prd[$i]['id']          = $reg['prd_id'];
            $prd[$i]['nome']        = $reg['prd_nome'];
            $prd[$i]['data_ini']    = $reg['prd_data_ini'];
            $prd[$i]['data_fim']    = $reg['prd_data_fim'];
            $prd[$i]['url']         = $reg['prd_url'];
            $prd[$i]['ativo']       = "0";
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

$app->post('/prox_eventos', function (Request $request, Response $response) {
    require "conecta.php";
    $date = date("Y-m-d H:i:s");
    $timestamp = strtotime($date . "+20 days");
    $data_prox = date("Y-m-d H:i:s", $timestamp);

    $categoria  = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_STRING);
    $campus     = filter_input(INPUT_POST, 'camp', FILTER_SANITIZE_STRING);
    $periodo    = filter_input(INPUT_POST, 'prd', FILTER_SANITIZE_STRING);
    $busca      = filter_input(INPUT_POST, 'b', FILTER_SANITIZE_STRING);
    $param      = "cat_id = eve_categoria";

    if($categoria != ""){
        $param .= " AND eve_categoria = '$categoria'";
    }
    if($campus != ""){
        $param .= " AND eve_campus = '$campus'";
    }
    if($periodo != ""){
        $param .= " AND eve_periodo = '$periodo'";
    } else {
        $param .= " AND eve_periodo = '".periodo_atual($date, $Conn)."'";
    }
    if($busca != ""){
        $param .= " AND eve_nome LIKE '%$busca%'";
    }

    $sql = "SELECT e.*, c.cat_cor FROM tbl_eventos e, tbl_categoria c
            WHERE 
                date_format(eve_data_ini, '%Y-%m-%d %H:%i:%s') >= date_format('$date', '%Y-%m-%d %H:%i:%s')
                AND date_format(eve_data_ini, '%Y-%m-%d %H:%i:%s') <= date_format('$data_prox', '%Y-%m-%d %H:%i:%s')
                AND $param
            OR
                date_format(eve_data_fim, '%Y-%m-%d %H:%i:%s') >= date_format('$date', '%Y-%m-%d %H:%i:%s')
                AND date_format(eve_data_fim, '%Y-%m-%d %H:%i:%s') <= date_format('$data_prox', '%Y-%m-%d %H:%i:%s')
                AND $param
            ORDER BY
                eve_data_fim ASC;";
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
        $prd[$i]['cor']         = $reg['cat_cor'];

        $dhi = explode(" ", $reg['eve_data_ini']);
        $dhf = explode(" ", $reg['eve_data_fim']);

        $di = explode("-", $dhi[0]);
        $df = explode("-", $dhf[0]);

        if($di[2] == $df[2]){
            $prd[$i]['data_titulo'] = $di[2]."/".mes($di[1]);
            $prd[$i]['data_ext'] = $di[2]."/".mes($di[1])." - ".str_replace(":", "h", substr($dhi[1], 0, 5))." à ".str_replace(":", "h", substr($dhf[1], 0, 5));
        } else {
            $prd[$i]['data_titulo'] = $di[2]."/".mes($di[1])." à ".$df[2]."/".mes($df[1]);
            $prd[$i]['data_ext'] = $di[2]."/".mes($di[1])." às ".str_replace(":", "h", substr($dhi[1], 0, 5))." à ".$df[2]."/".mes($df[1])." às ".str_replace(":", "h", substr($dhf[1], 0, 5));
        }

        $i++;
    }

    return $response->withJson($prd);
});

$app->post('/eventos', function (Request $request, Response $response) {
    require "conecta.php";
    $date = date("Y-m-d H:i:s");

    $categoria  = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_STRING);
    $campus     = filter_input(INPUT_POST, 'camp', FILTER_SANITIZE_STRING);
    $periodo    = filter_input(INPUT_POST, 'prd', FILTER_SANITIZE_STRING);
    $busca      = filter_input(INPUT_POST, 'b', FILTER_SANITIZE_STRING);
    $param      = "cat_id = eve_categoria AND prd_id = eve_periodo";

    if($categoria != ""){
        $param .= " AND eve_categoria = '$categoria'";
    }
    if($campus != ""){
        $param .= " AND eve_campus = '$campus'";
    }
    if($periodo != ""){
        $param .= " AND eve_periodo = '$periodo'";
    } else {
        $param .= " AND eve_periodo = '".periodo_atual($date, $Conn)."'";
    }
    if($busca != ""){
        $param .= " AND eve_nome LIKE '%$busca%' OR ".$param." AND eve_descricao LIKE '%$busca%'";
    }

    $sql2 = "SELECT e.*, c.cat_cor, p.prd_url FROM tbl_eventos e, tbl_categoria c, tbl_periodos p 
             WHERE 
                $param 
             ORDER BY 
                eve_data_ini ASC;";
    $exe2 = $Conn->query($sql2);
    $cont2 = $exe2->num_rows;

    $prd2 = array();
    $j    = 0;
    $i    = 1;
    $result = array();
    $mes  = null;
    $prd2[$j]['cont'] = $cont2;

    while ($reg2 = $exe2->fetch_array()) {
        $dhi = explode(" ", $reg2['eve_data_ini']);
        $dhf = explode(" ", $reg2['eve_data_fim']);

        $di = explode("-", $dhi[0]);
        $df = explode("-", $dhf[0]);

        if($mes != mescomp($di[1])){
            if($mes != null){
                $result[$mes] = $prd2;
            }
            $mes = "".mescomp($di[1])."";
            $j = 0;
            $prd2 = null;
            $prd2 = array();
        }
        $prd2[$j]['id']          = $reg2['eve_id'];
        $prd2[$j]['periodo']     = $reg2['eve_periodo'];
        $prd2[$j]['campus']      = $reg2['eve_campus'];
        $prd2[$j]['categoria']   = $reg2['eve_categoria'];
        $prd2[$j]['nome']        = $reg2['eve_nome'];
        $prd2[$j]['local']       = $reg2['eve_local'];
        $prd2[$j]['data_ini']    = $reg2['eve_data_ini'];
        $prd2[$j]['data_fim']    = $reg2['eve_data_fim'];
        $prd2[$j]['desc']        = $reg2['eve_descricao'];
        $prd2[$j]['url']         = $reg2['eve_url'];
        $prd2[$j]['cor']         = $reg2['cat_cor'];
        $prd2[$j]['prd_url']     = $reg2['prd_url'];

        if($di[2] == $df[2]){
            $prd2[$j]['data_titulo'] = $di[2]."/".mes($di[1]);
            $prd2[$j]['data_ext'] = $di[2]."/".mes($di[1])." - ".str_replace(":", "h", substr($dhi[1], 0, 5))." à ".str_replace(":", "h", substr($dhf[1], 0, 5));
        } else {
            $prd2[$j]['data_titulo'] = $di[2]."/".mes($di[1])." à ".$df[2]."/".mes($df[1]);
            $prd2[$j]['data_ext'] = $di[2]."/".mes($di[1])." às ".str_replace(":", "h", substr($dhi[1], 0, 5))." à ".$df[2]."/".mes($df[1])." às ".str_replace(":", "h", substr($dhf[1], 0, 5));
        }

        if($i == $cont2){
            $result[$mes] = $prd2;
        }

        $j++;
        $i++;
    }

    return $response->withJson($result);
});

$app->get('/evento', function (Request $request, Response $response) {
    require "conecta.php";
    $date = date("Y-m-d H:i:s");

    $id  = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT e.*, c.cat_cor, c.cat_nome, p.prd_url FROM tbl_eventos e, tbl_categoria c, tbl_periodos p 
            WHERE 
               cat_id = eve_categoria AND prd_id = eve_periodo AND eve_id = '".$id."';";
    $exe = $Conn->query($sql);
    $cont = $exe->num_rows;

    $prd = array();
    $j    = 0;
    $prd[$j]['cont'] = $cont;

    while ($reg = $exe->fetch_array()) {
        $dhi = explode(" ", $reg['eve_data_ini']);
        $dhf = explode(" ", $reg['eve_data_fim']);

        $di = explode("-", $dhi[0]);
        $df = explode("-", $dhf[0]);

        $prd[$j]['id']          = $reg['eve_id'];
        $prd[$j]['periodo']     = $reg['eve_periodo'];
        $prd[$j]['campus']      = $reg['eve_campus'];
        $prd[$j]['categoria']   = $reg['eve_categoria'];
        $prd[$j]['cat_nome']    = $reg['cat_nome'];
        $prd[$j]['nome']        = $reg['eve_nome'];
        $prd[$j]['local']       = $reg['eve_local'];
        $prd[$j]['data_ini']    = $reg['eve_data_ini'];
        $prd[$j]['data_fim']    = $reg['eve_data_fim'];
        $prd[$j]['desc']        = $reg['eve_descricao'];
        $prd[$j]['url']         = $reg['eve_url'];
        $prd[$j]['cor']         = $reg['cat_cor'];
        $prd[$j]['prd_url']     = $reg['prd_url'];

        if($di[2] == $df[2]){
            $prd[$j]['data_titulo'] = $di[2]."/".mes($di[1]);
            $prd[$j]['data_ext'] = $di[2]."/".mes($di[1])." - ".str_replace(":", "h", substr($dhi[1], 0, 5))." à ".str_replace(":", "h", substr($dhf[1], 0, 5));
        } else {
            $prd[$j]['data_titulo'] = $di[2]."/".mes($di[1])." à ".$df[2]."/".mes($df[1]);
            $prd[$j]['data_ext'] = $di[2]."/".mes($di[1])." às ".str_replace(":", "h", substr($dhi[1], 0, 5))." à ".$df[2]."/".mes($df[1])." às ".str_replace(":", "h", substr($dhf[1], 0, 5));
        }

        $j++;
    }

    return $response->withJson($prd);
});