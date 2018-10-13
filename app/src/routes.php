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

// Rota para retorno de usuários da área restrita
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

// Rota para Fazer o push de novo usuário ao serviço Firebase de Notificações
$app->post('/push/clientes', function (Request $request, Response $response) {
    $token = $_POST['token']; // Token gerado no serviço Browser notification para requisitar a inscrição

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://iid.googleapis.com/iid/v1/".$token."/rel/topics/clientes", // chamada à API do Firebase, incluindo o token do usuário que aceitou receber as notificações
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POST, true,
        CURLOPT_HTTPHEADER => array(
            "authorization: key=AAAAVaQJ-YM:APA91bG3bY56WZTnryd4c49Ri1hCywt8uj9z2SHD3srcmB9yI_rtgyBDyfNUYu3Rbr0fQI4w4Di1flIPk0yrdYAaIsbEo-tCzLGdwojHJGuRIjwahGbcP5ZKaQ1sFwBe02tokYhOHvoM", // chave do servidor Firebase (Trocar acessando o console do firebase na aba Cloud Messaging do menu Configurações)
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
        return $response; // a resposta será um ID que não precisa de armazenamento, pois o próprio Browser guarda e é chamado quando houver notificação
    }
});

// Rota para períodos do calendário
$app->get('/periodos', function (Request $request, Response $response) {
    require "conecta.php"; // chama as conexões ao banco de dados MySQL
    $date = date("Y-m-d H:i:s"); // pega a data atual do servidor

    $sql = "SELECT prd_id, prd_nome, prd_data_ini, prd_data_fim, prd_url FROM tbl_periodos ORDER BY prd_id DESC LIMIT 6;"; // select para chamar os dados da tabela Períodos, listando os últimos 6 períodos
    $exe = $Conn->query($sql);
    $cont = $exe->num_rows;

    $prd = array(); // cria um array para armazenar os dados
    $i   = 0;
    $prd[$i]['cont'] = $cont; // conta quantos registros irá retornar

    while ($reg = $exe->fetch_array()) {

        if(strtotime($reg['prd_data_ini']) <= strtotime($date) // se a data de início do período cadastrado for menor ou igual à data atual
            && strtotime($reg['prd_data_fim']) >= strtotime($date)){ // e se a data de fim do período for maior ou igual à data atual
            if(isset($prd[0]['id'])){ // verifica se o primeiro registro já foi preenchido, caso foi, irá trocar com a última posição para que a posição atual seja a primeira do array para ser mostrado no calendário, por ser o período atual do calendário.
                // troca os dados do primeiro registro e coloca eles na ultima posição
                $prd[$i]['id']       = $prd[0]['id'];
                $prd[$i]['nome']     = $prd[0]['nome'];
                $prd[$i]['data_ini'] = $prd[0]['data_ini'];
                $prd[$i]['data_fim'] = $prd[0]['data_fim'];
                $prd[$i]['url']      = $prd[0]['url'];
                $prd[$i]['ativo']    = $prd[0]['ativo'];

                // e troca o período atual que achamos para a primeira posição do array
                $prd[0]['id']       = $reg['prd_id'];
                $prd[0]['nome']     = $reg['prd_nome'];
                $prd[0]['data_ini'] = $reg['prd_data_ini'];
                $prd[0]['data_fim'] = $reg['prd_data_fim'];
                $prd[0]['url']      = $reg['prd_url'];
                $prd[0]['ativo']    = "1"; // habilita ele como o período atual ativo
            } else {
                // caso o primeiro registro não foi preenchido, colocar o período atual nele, pois será ainda o primeiro
                $prd[0]['id']       = $reg['prd_id'];
                $prd[0]['nome']     = $reg['prd_nome'];
                $prd[0]['data_ini'] = $reg['prd_data_ini'];
                $prd[0]['data_fim'] = $reg['prd_data_fim'];
                $prd[0]['url']      = $reg['prd_url'];
                $prd[0]['ativo']    = "1"; // habilita ele como o período atual ativo
            }
        } else {
            // caso as datas do período estejam fora da data atual, vem pra cá, para virar opções
            $prd[$i]['id']          = $reg['prd_id'];
            $prd[$i]['nome']        = $reg['prd_nome'];
            $prd[$i]['data_ini']    = $reg['prd_data_ini'];
            $prd[$i]['data_fim']    = $reg['prd_data_fim'];
            $prd[$i]['url']         = $reg['prd_url'];
            $prd[$i]['ativo']       = "0"; // não habilita ele como o período atual ativo
        }
        $i++;
    }

    return $response->withJson($prd); // monta o JSON com os dados
});

// Rota para retorno dos Campus universitários
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

// Rota para retorno das categorias de eventos
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

// Rota para retorno dos próximos eventos (Próximos 20 dias, para alterar mude na linha onde há o comando "+20 days")
$app->post('/prox_eventos', function (Request $request, Response $response) {
    require "conecta.php";
    $date = date("Y-m-d H:i:s");
    $timestamp = strtotime($date . "+20 days"); // pega a data atual e adiciona 20 dias
    $data_prox = date("Y-m-d H:i:s", $timestamp); // converte a transformação timestamp para a data máxima fornecida (atual + 20 dias)

    $categoria  = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_NUMBER_INT); // recebe o POST da categoria
    $campus     = filter_input(INPUT_POST, 'camp', FILTER_SANITIZE_NUMBER_INT); // recebe o POST do campus
    $periodo    = filter_input(INPUT_POST, 'prd', FILTER_SANITIZE_NUMBER_INT); // recebe o POST do período
    $busca      = filter_input(INPUT_POST, 'b', FILTER_SANITIZE_STRING); // recebe o POST da busca
    $param      = "cat_id = eve_categoria"; // parte do parâmetro SQL para montar na query abaixo

    if($categoria != ""){
        $param .= " AND eve_categoria = '$categoria'"; // caso o POST da categoria vier com algum id, adicionar o parâmetro SQL à query
    }
    if($campus != ""){
        $param .= " AND eve_campus = '$campus'"; // caso o POST do campus vier com algum id, adicionar o parâmetro SQL à query
    }
    if($periodo != ""){
        $param .= " AND eve_periodo = '$periodo'"; // caso o POST do período vier com algum id, adicionar o parâmetro SQL à query
    } else {
        $param .= " AND eve_periodo = '".periodo_atual($date, $Conn)."'"; // caso não vier com algum id, descobrir a id do período atual chamando a função que está no arquivo conecta.php
    }
    if($busca != ""){
        $param .= " AND eve_nome LIKE '%$busca%'"; // caso o POST da busca vier com algum termo, adicionar o parâmetro SQL à query
    }

    // aqui vai buscar os dados dos eventos e alguns elementos, de acordo com os parâmetros e as datas de início e fim se aproximarem dos próximos 20 dias
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
    $cont = $exe->num_rows; // conta os registros

    $prd = array(); // prepara a lista dos eventos
    $i   = 0;
    $prd[$i]['cont'] = $cont; // registra na primeira linha a contagem de eventos encontrados

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

        // manipula as datas para escrever por extenso ou abreviado
        $dhi = explode(" ", $reg['eve_data_ini']);
        $dhf = explode(" ", $reg['eve_data_fim']);

        $di = explode("-", $dhi[0]);
        $df = explode("-", $dhf[0]);

        // se os dias forem os mesmos, mostra apenas o dia como sendo um só
        if($di[2] == $df[2]){
            $prd[$i]['data_titulo'] = $di[2]."/".mes($di[1]);
            $prd[$i]['data_ext'] = $di[2]."/".mes($di[1])." - ".str_replace(":", "h", substr($dhi[1], 0, 5))." à ".str_replace(":", "h", substr($dhf[1], 0, 5));
        } else { // se não, mostra a data de inicio até a data final em 2 formatos, abreviado e por extenso
            $prd[$i]['data_titulo'] = $di[2]."/".mes($di[1])." à ".$df[2]."/".mes($df[1]);
            $prd[$i]['data_ext'] = $di[2]."/".mes($di[1])." às ".str_replace(":", "h", substr($dhi[1], 0, 5))." à ".$df[2]."/".mes($df[1])." às ".str_replace(":", "h", substr($dhf[1], 0, 5));
        }

        $i++;
    }

    return $response->withJson($prd); // monta JSON para a resposta
});

// Rota para retorno dos eventos de um período ou do período atual (selecionado automaticamente quando não selecionado)
$app->post('/eventos', function (Request $request, Response $response) {
    require "conecta.php";
    $date = date("Y-m-d H:i:s");

    $categoria  = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_NUMBER_INT); // recebe o POST da categoria
    $campus     = filter_input(INPUT_POST, 'camp', FILTER_SANITIZE_NUMBER_INT); // recebe o POST do campus
    $periodo    = filter_input(INPUT_POST, 'prd', FILTER_SANITIZE_NUMBER_INT); // recebe o POST do período
    $busca      = filter_input(INPUT_POST, 'b', FILTER_SANITIZE_STRING); // recebe o POST da busca
    $param      = "cat_id = eve_categoria AND prd_id = eve_periodo"; // parte do parâmetro SQL para montar na query abaixo

    if($categoria != ""){
        $param .= " AND eve_categoria = '$categoria'"; // caso o POST da categoria vier com algum id, adicionar o parâmetro SQL à query
    }
    if($campus != ""){
        $param .= " AND eve_campus = '$campus'"; // caso o POST do campus vier com algum id, adicionar o parâmetro SQL à query
    }
    if($periodo != ""){
        $param .= " AND eve_periodo = '$periodo'"; // caso o POST do período vier com algum id, adicionar o parâmetro SQL à query
    } else {
        $param .= " AND eve_periodo = '".periodo_atual($date, $Conn)."'"; // caso não vier com algum id, descobrir a id do período atual chamando a função que está no arquivo conecta.php
    }
    if($busca != ""){
        $param .= " AND eve_nome LIKE '%$busca%' OR ".$param." AND eve_descricao LIKE '%$busca%'"; // caso o POST da busca vier com algum termo, adicionar o parâmetro SQL à query
    }

    // aqui vai buscar os dados dos eventos e alguns elementos, de acordo com os parâmetros
    $sql2 = "SELECT e.*, c.cat_cor, p.prd_url FROM tbl_eventos e, tbl_categoria c, tbl_periodos p 
             WHERE 
                $param 
             ORDER BY 
                eve_data_ini ASC;";
    $exe2 = $Conn->query($sql2);
    $cont2 = $exe2->num_rows;

    $prd2 = array(); // listagem dos eventos
    $j    = 0;
    $i    = 1;
    $result = array(); // listagem dos meses, onde estarão separados os eventos
    $mes  = null; // para guardar os nomes dos meses para cada índice do array $result
    $prd2[$j]['cont'] = $cont2;

    while ($reg2 = $exe2->fetch_array()) {
        // manipula as datas para escrever por extenso ou abreviado
        $dhi = explode(" ", $reg2['eve_data_ini']);
        $dhf = explode(" ", $reg2['eve_data_fim']);

        $di = explode("-", $dhi[0]);
        $df = explode("-", $dhf[0]);

        if($mes != mescomp($di[1])){ // Se o mês já gravado no primeiro loop for diferente do mês do loop atual, grava o novo mês na variável e escreve no array $result
            if($mes != null){ // caso seja o loop inicial
                $result[$mes] = $prd2; // colocar os eventos no mês
            }
            $mes = "".mescomp($di[1]).""; // troca o nome do mês na variavel pra colocar os próximos eventos nele
            $j = 0;
            $prd2 = null; // limpa a lista
            $prd2 = array(); // inicia uma nova lista para o próximo mês
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

        // se os dias forem os mesmos, mostra apenas o dia como sendo um só
        if($di[2] == $df[2]){
            $prd2[$j]['data_titulo'] = $di[2]."/".mes($di[1]);
            $prd2[$j]['data_ext'] = $di[2]."/".mes($di[1])." - ".str_replace(":", "h", substr($dhi[1], 0, 5))." à ".str_replace(":", "h", substr($dhf[1], 0, 5));
        } else { // se não, mostra a data de inicio até a data final em 2 formatos, abreviado e por extenso
            $prd2[$j]['data_titulo'] = $di[2]."/".mes($di[1])." à ".$df[2]."/".mes($df[1]);
            $prd2[$j]['data_ext'] = $di[2]."/".mes($di[1])." às ".str_replace(":", "h", substr($dhi[1], 0, 5))." à ".$df[2]."/".mes($df[1])." às ".str_replace(":", "h", substr($dhf[1], 0, 5));
        }

        if($i == $cont2){ // se a última posição, corresponder ao número de eventos encontrados
            $result[$mes] = $prd2; // incluir a última lista de eventos ao último mês encontrado
        }

        $j++;
        $i++;
    }

    return $response->withJson($result); // monta JSON para a resposta
});

// Rota para retorno do evento selecionado, exibido na página evento.html
$app->get('/evento', function (Request $request, Response $response) {
    require "conecta.php";
    $date = date("Y-m-d H:i:s");

    $id  = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o id do evento

    // requisita os dados do evento
    $sql = "SELECT e.*, c.cat_cor, c.cat_nome, p.prd_url FROM tbl_eventos e, tbl_categoria c, tbl_periodos p 
            WHERE 
               cat_id = eve_categoria AND prd_id = eve_periodo AND eve_id = '".$id."';";
    $exe = $Conn->query($sql);
    $cont = $exe->num_rows;

    $prd = array();
    $j    = 0;
    $prd[$j]['cont'] = $cont;

    while ($reg = $exe->fetch_array()) {
        // manipula as datas para escrever por extenso ou abreviado
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

        // se os dias forem os mesmos, mostra apenas o dia como sendo um só
        if($di[2] == $df[2]){
            $prd[$j]['data_titulo'] = $di[2]."/".mes($di[1]);
            $prd[$j]['data_ext'] = $di[2]."/".mes($di[1])." - ".str_replace(":", "h", substr($dhi[1], 0, 5))." à ".str_replace(":", "h", substr($dhf[1], 0, 5));
        } else { // se não, mostra a data de inicio até a data final em 2 formatos, abreviado e por extenso
            $prd[$j]['data_titulo'] = $di[2]."/".mes($di[1])." à ".$df[2]."/".mes($df[1]);
            $prd[$j]['data_ext'] = $di[2]."/".mes($di[1])." às ".str_replace(":", "h", substr($dhi[1], 0, 5))." à ".$df[2]."/".mes($df[1])." às ".str_replace(":", "h", substr($dhf[1], 0, 5));
        }

        $j++;
    }

    return $response->withJson($prd); // monta JSON para a resposta
});