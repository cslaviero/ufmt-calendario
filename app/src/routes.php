<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Respect\Validation\Validator as v;

require '../../vendor/autoload.php';

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
    $sql = "SELECT e.*, c.cat_cor, c.cat_nome, p.prd_url
            FROM tbl_eventos e, tbl_categoria c, tbl_periodos p 
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

        $prd[$j]['di'] = $di[2]."/".$di[1]."/".$di[0]." ".$dhi[1];
        $prd[$j]['df'] = $df[2]."/".$df[1]."/".$df[0]." ".$dhf[1];

        $j++;
    }

    return $response->withJson($prd); // monta JSON para a resposta
});

// Rota para inserir evento na área administrativa
$app->post('/inserir/evento', function (Request $request, Response $response) {
    require "conecta.php";
    $date = date("Y-m-d H:i:s");

    $nome        = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING); // recebe o nome do evento
    $periodo     = filter_input(INPUT_POST, 'periodo', FILTER_SANITIZE_NUMBER_INT); // recebe o id do periodo
    $campus      = filter_input(INPUT_POST, 'campus', FILTER_SANITIZE_NUMBER_INT); // recebe o id do campus
    $dataini     = filter_input(INPUT_POST, 'dataini', FILTER_SANITIZE_STRING); // recebe a data/hora de inicio
    $datafim     = filter_input(INPUT_POST, 'datafim', FILTER_SANITIZE_STRING); // recebe a data/hora de fim
    $categoria   = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_NUMBER_INT); // recebe o id da categoria
    $notificacao = filter_input(INPUT_POST, 'notificacao', FILTER_SANITIZE_STRING); // recebe o valor da notificação
    $local       = filter_input(INPUT_POST, 'local', FILTER_SANITIZE_STRING); // recebe o local do evento
    $url         = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_STRING); // recebe a url do evento
    $desc        = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING); // recebe a descrição do evento

    // manipula as datas para escrever no banco de dados
    $dhi = explode(" ", $dataini);
    $di = explode("/", $dhi[0]);

    $dhf = explode(" ", $datafim);
    $df = explode("/", $dhf[0]);

    $dataini = $di[2].'-'.$di[1].'-'.$di[0].' '.$dhi[1];
    $datafim = $df[2].'-'.$df[1].'-'.$df[0].' '.$dhf[1];

    $sql = "INSERT INTO tbl_eventos (eve_periodo,eve_campus,eve_categoria,eve_nome,eve_local,eve_data_ini,eve_data_fim,eve_descricao,eve_url) VALUES ('$periodo','$campus','$categoria','$nome','$local','$dataini','$datafim','$desc','$url');";

    if($Conn->query($sql)){
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para alterar evento na área administrativa
$app->post('/alterar/evento', function (Request $request, Response $response) {
    require "conecta.php";

    $id        = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o nome do evento
    $nome        = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING); // recebe o nome do evento
    $periodo     = filter_input(INPUT_POST, 'periodo', FILTER_SANITIZE_NUMBER_INT); // recebe o id do periodo
    $campus      = filter_input(INPUT_POST, 'campus', FILTER_SANITIZE_NUMBER_INT); // recebe o id do campus
    $dataini     = filter_input(INPUT_POST, 'dataini', FILTER_SANITIZE_STRING); // recebe a data/hora de inicio
    $datafim     = filter_input(INPUT_POST, 'datafim', FILTER_SANITIZE_STRING); // recebe a data/hora de fim
    $categoria   = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_NUMBER_INT); // recebe o id da categoria
    $notificacao = filter_input(INPUT_POST, 'notificacao', FILTER_SANITIZE_STRING); // recebe o valor da notificação
    $local       = filter_input(INPUT_POST, 'local', FILTER_SANITIZE_STRING); // recebe o local do evento
    $url         = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_STRING); // recebe a url do evento
    $desc        = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING); // recebe a descrição do evento

    // manipula as datas para escrever no banco de dados
    $dhi = explode(" ", $dataini);
    $di = explode("/", $dhi[0]);

    $dhf = explode(" ", $datafim);
    $df = explode("/", $dhf[0]);

    $dataini = $di[2].'-'.$di[1].'-'.$di[0].' '.$dhi[1];
    $datafim = $df[2].'-'.$df[1].'-'.$df[0].' '.$dhf[1];

    $sql = "UPDATE tbl_eventos 
            SET eve_periodo = '$periodo',
                eve_campus = '$campus',
                eve_categoria = '$categoria',
                eve_nome = '$nome',
                eve_local = '$local',
                eve_data_ini = '$dataini',
                eve_data_fim = '$datafim',
                eve_descricao = '$desc',
                eve_url = '$url'
            WHERE eve_id = '$id';";

    if($Conn->query($sql)){
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para deletar evento na área administrativa
$app->post('/deletar/evento', function (Request $request, Response $response) {
    require "conecta.php";

    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o id do evento

    $sql = "DELETE FROM tbl_eventos WHERE eve_id = '$id';";

    if($Conn->query($sql)){
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para inserir categoria na área administrativa
$app->post('/inserir/categoria', function (Request $request, Response $response) {
    require "conecta.php";
    $date = date("Y-m-d H:i:s");

    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING); // recebe o nome da categoria
    $cor  = filter_input(INPUT_POST, 'cor', FILTER_SANITIZE_STRING); // recebe a cor

    $sql = "INSERT INTO tbl_categoria (cat_nome, cat_cor) VALUES ('$nome','$cor');";

    if($Conn->query($sql)){
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para alterar categoria na área administrativa
$app->post('/alterar/categoria', function (Request $request, Response $response) {
    require "conecta.php";

    $id   = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o id da categoria
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING); // recebe o nome da categoria
    $cor  = filter_input(INPUT_POST, 'cor', FILTER_SANITIZE_STRING); // recebe a cor da categoria

    $sql = "UPDATE tbl_categoria 
            SET cat_nome = '$nome',
                cat_cor  = '$cor'
            WHERE cat_id = '$id';";

    if($Conn->query($sql)){
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para deletar categoria na área administrativa
$app->post('/deletar/categoria', function (Request $request, Response $response) {
    require "conecta.php";

    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o id da categoria

    $sql = "DELETE FROM tbl_categoria WHERE cat_id = '$id';";

    if($Conn->query($sql)){
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para retorno da categoria selecionada, exibido na página
$app->get('/listar/categoria', function (Request $request, Response $response) {
    require "conecta.php";

    $id  = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o id da categoria

    // requisita os dados do evento
    $sql = "SELECT *
            FROM tbl_categoria
            WHERE 
               cat_id = '".$id."';";
    $exe = $Conn->query($sql);
    $cont = $exe->num_rows;

    $prd = array();
    $j   = 0;
    $prd[$j]['cont'] = $cont;

    while ($reg = $exe->fetch_array()) {
        $prd[$j]['id']   = $reg['cat_id'];
        $prd[$j]['nome'] = $reg['cat_nome'];
        $prd[$j]['cor']  = $reg['cat_cor'];

        $j++;
    }

    return $response->withJson($prd); // monta JSON para a resposta
});

// Rota para inserir campus na área administrativa
$app->post('/inserir/campus', function (Request $request, Response $response) {
    require "conecta.php";

    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING); // recebe o nome do campus

    $sql = "INSERT INTO tbl_campus (cps_nome) VALUES ('$nome');";

    if($Conn->query($sql)){
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para alterar campus na área administrativa
$app->post('/alterar/campus', function (Request $request, Response $response) {
    require "conecta.php";

    $id   = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o id do campus
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING); // recebe o nome do campus

    $sql = "UPDATE tbl_campus 
            SET cps_nome = '$nome'
            WHERE cps_id = '$id';";

    if($Conn->query($sql)){
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para deletar campus na área administrativa
$app->post('/deletar/campus', function (Request $request, Response $response) {
    require "conecta.php";

    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o id do campus

    $sql = "DELETE FROM tbl_campus WHERE cps_id = '$id';";

    if($Conn->query($sql)){
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para retorno do campus selecionado, exibido na página
$app->get('/listar/campus', function (Request $request, Response $response) {
    require "conecta.php";

    $id  = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o id do campus

    // requisita os dados do evento
    $sql = "SELECT *
            FROM tbl_campus
            WHERE 
               cps_id = '".$id."';";
    $exe = $Conn->query($sql);
    $cont = $exe->num_rows;

    $prd = array();
    $j   = 0;
    $prd[$j]['cont'] = $cont;

    while ($reg = $exe->fetch_array()) {
        $prd[$j]['id']   = $reg['cps_id'];
        $prd[$j]['nome'] = $reg['cps_nome'];

        $j++;
    }

    return $response->withJson($prd); // monta JSON para a resposta
});

// Rota para inserir periodo na área administrativa
$app->post('/inserir/periodo', function (Request $request, Response $response) {
    require "conecta.php";

    $nome               = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING); // recebe o nome do periodo
    $dataini            = filter_input(INPUT_POST, 'dataini', FILTER_SANITIZE_STRING); // recebe a data inicial do período
    $datafim            = filter_input(INPUT_POST, 'datafim', FILTER_SANITIZE_STRING); // recebe a data final do período
    $url                = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_STRING); // recebe a url do calendário
    $importar           = filter_input(INPUT_POST, 'importar', FILTER_SANITIZE_STRING); // checkbox importar eventos de outros periodos
    $periodo_importar   = filter_input(INPUT_POST, 'periodo', FILTER_SANITIZE_STRING); // periodo que exportará para o novo periodo criado

    // manipula as datas para escrever no banco de dados
    $dhi = explode(" ", $dataini);
    $di = explode("/", $dhi[0]);

    $dhf = explode(" ", $datafim);
    $df = explode("/", $dhf[0]);

    $dataini = $di[2].'-'.$di[1].'-'.$di[0].' '.$dhi[1];
    $datafim = $df[2].'-'.$df[1].'-'.$df[0].' '.$dhf[1];

    $Commit = $Conn->query("SET AUTOCOMMIT=0");
    $ok = 0;

    $sql = "INSERT INTO tbl_periodos (prd_nome, prd_data_ini, prd_data_fim, prd_url) 
            VALUES ('$nome','$dataini','$datafim','$url');";
    $exe = $Conn->query($sql);

    if($importar == "1"){
        $sql_id = "SELECT LAST_INSERT_ID();";
        $rs  = $Conn->query($sql_id)->fetch_array();
        $id_p = $rs['LAST_INSERT_ID()'];

        $sql_importa = "SELECT * FROM tbl_eventos WHERE eve_periodo = '$periodo_importar';";
        $exe_importa = $Conn->query($sql_importa);

        while($reg_importa = $exe_importa->fetch_array()){
            $sql_ins = "INSERT INTO tbl_eventos (
                        eve_periodo,
                        eve_campus,
                        eve_categoria,
                        eve_nome,
                        eve_local,
                        eve_data_ini,
                        eve_data_fim,
                        eve_descricao,
                        eve_url)
                        VALUES (
                        '".$id_p."',
                        '".$reg_importa['eve_campus']."',
                        '".$reg_importa['eve_categoria']."',
                        '".$reg_importa['eve_nome']."',
                        '".$reg_importa['eve_local']."',
                        '".$reg_importa['eve_data_ini']."',
                        '".$reg_importa['eve_data_fim']."',
                        '".$reg_importa['eve_descricao']."',
                        '".$reg_importa['eve_url']."');";

            $exe_ins = $Conn->query($sql_ins);
        }
        $ok = 1;
    } else {
        $ok = 1;
    }

    if($ok == 1){
        $Conn->query("COMMIT");
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        $Conn->query("ROLLBACK");
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para alterar periodo na área administrativa
$app->post('/alterar/periodo', function (Request $request, Response $response) {
    require "conecta.php";

    $id      = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o nome do periodo
    $nome    = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING); // recebe o nome do periodo
    $dataini = filter_input(INPUT_POST, 'dataini', FILTER_SANITIZE_STRING); // recebe a data inicial do período
    $datafim = filter_input(INPUT_POST, 'datafim', FILTER_SANITIZE_STRING); // recebe a data final do período
    $url     = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_STRING); // recebe a url do calendário

    // manipula as datas para escrever no banco de dados
    $dhi = explode(" ", $dataini);
    $di = explode("/", $dhi[0]);

    $dhf = explode(" ", $datafim);
    $df = explode("/", $dhf[0]);

    $dataini = $di[2].'-'.$di[1].'-'.$di[0].' '.$dhi[1];
    $datafim = $df[2].'-'.$df[1].'-'.$df[0].' '.$dhf[1];

    $sql = "UPDATE tbl_periodos 
            SET prd_nome = '$nome',
            prd_data_ini  = '$dataini',
            prd_data_fim = '$datafim',
            prd_url = '$url'
            WHERE prd_id = '$id';";

    if($Conn->query($sql)){
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para deletar periodo na área administrativa
$app->post('/deletar/periodo', function (Request $request, Response $response) {
    require "conecta.php";

    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o id do periodo

    $sql_eve = "DELETE FROM tbl_eventos WHERE eve_periodo = '$id';";
    $sql     = "DELETE FROM tbl_periodos WHERE prd_id = '$id';";

    if($Conn->query($sql_eve) && $Conn->query($sql)){
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para retorno do periodo selecionado, exibido na página
$app->get('/listar/periodo', function (Request $request, Response $response) {
    require "conecta.php";

    $id  = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o id do periodo

    // requisita os dados do evento
    $sql = "SELECT *
            FROM tbl_periodos
            WHERE 
               prd_id = '".$id."';";
    $exe = $Conn->query($sql);
    $cont = $exe->num_rows;

    $prd = array();
    $j   = 0;
    $prd[$j]['cont'] = $cont;

    while ($reg = $exe->fetch_array()) {
        $dhi = explode(" ", $reg['prd_data_ini']);
        $dhf = explode(" ", $reg['prd_data_fim']);

        $di = explode("-", $dhi[0]);
        $df = explode("-", $dhf[0]);

        $prd[$j]['id']       = $reg['prd_id'];
        $prd[$j]['nome']     = $reg['prd_nome'];
        $prd[$j]['data_ini'] = $reg['prd_data_ini'];
        $prd[$j]['data_fim'] = $reg['prd_data_fim'];
        $prd[$j]['url']      = $reg['prd_url'];

        $prd[$j]['di'] = $di[2]."/".$di[1]."/".$di[0]." ".$dhi[1];
        $prd[$j]['df'] = $df[2]."/".$df[1]."/".$df[0]." ".$dhf[1];

        $j++;
    }

    return $response->withJson($prd); // monta JSON para a resposta
});

// Rota para inserir usuário na área administrativa
$app->post('/inserir/usuario', function (Request $request, Response $response) {
    require "conecta.php";

    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $inscampus = filter_input(INPUT_POST, 'inscampus', FILTER_SANITIZE_STRING);
    $altcampus = filter_input(INPUT_POST, 'altcampus', FILTER_SANITIZE_STRING);
    $delcampus = filter_input(INPUT_POST, 'delcampus', FILTER_SANITIZE_STRING);
    $inscat = filter_input(INPUT_POST, 'inscat', FILTER_SANITIZE_STRING);
    $altcat = filter_input(INPUT_POST, 'altcat', FILTER_SANITIZE_STRING);
    $delcat = filter_input(INPUT_POST, 'delcat', FILTER_SANITIZE_STRING);
    $inseve = filter_input(INPUT_POST, 'inseve', FILTER_SANITIZE_STRING);
    $alteve = filter_input(INPUT_POST, 'alteve', FILTER_SANITIZE_STRING);
    $deleve = filter_input(INPUT_POST, 'deleve', FILTER_SANITIZE_STRING);
    $insprd = filter_input(INPUT_POST, 'insprd', FILTER_SANITIZE_STRING);
    $altprd = filter_input(INPUT_POST, 'altprd', FILTER_SANITIZE_STRING);
    $delprd = filter_input(INPUT_POST, 'delprd', FILTER_SANITIZE_STRING);
    $insusu = filter_input(INPUT_POST, 'insusu', FILTER_SANITIZE_STRING);
    $altusu = filter_input(INPUT_POST, 'altusu', FILTER_SANITIZE_STRING);
    $delusu = filter_input(INPUT_POST, 'delusu', FILTER_SANITIZE_STRING);

    if($inscampus == "1"){
        $inscampus = 1;
    } else {
        $inscampus = 0;
    }

    if($altcampus == "1"){
        $altcampus = 1;
    } else {
        $altcampus = 0;
    }

    if($delcampus == "1"){
        $delcampus = 1;
    } else {
        $delcampus = 0;
    }

    if($inscat == "1"){
        $inscat = 1;
    } else {
        $inscat = 0;
    }

    if($altcat == "1"){
        $altcat = 1;
    } else {
        $altcat = 0;
    }

    if($delcat == "1"){
        $delcat = 1;
    } else {
        $delcat = 0;
    }

    if($inseve == "1"){
        $inseve = 1;
    } else {
        $inseve = 0;
    }

    if($alteve == "1"){
        $alteve = 1;
    } else {
        $alteve = 0;
    }

    if($deleve == "1"){
        $deleve = 1;
    } else {
        $deleve = 0;
    }

    if($insprd == "1"){
        $insprd = 1;
    } else {
        $insprd = 0;
    }

    if($altprd == "1"){
        $altprd = 1;
    } else {
        $altprd = 0;
    }

    if($delprd == "1"){
        $delprd = 1;
    } else {
        $delprd = 0;
    }

    if($insusu == "1"){
        $insusu = 1;
    } else {
        $insusu = 0;
    }

    if($altusu == "1"){
        $altusu = 1;
    } else {
        $altusu = 0;
    }

    if($delusu == "1"){
        $delusu = 1;
    } else {
        $delusu = 0;
    }

    $Commit = $Conn->query("SET AUTOCOMMIT=0");

    $sql = "INSERT INTO tbl_usuarios (usu_nome, usu_email, usu_usuario, usu_senha) 
            VALUES ('$nome', '$email', '$usuario', '$senha');";
    if($Conn->query($sql)){
        $ok = 1;
    } else {
        $ok = 0;
    }

    $sql_id = "SELECT LAST_INSERT_ID();";
    $rs  = $Conn->query($sql_id)->fetch_array();
    $id_p = $rs['LAST_INSERT_ID()'];

    // menu eventos
    $sql1 = "INSERT INTO tbl_permissoes (prm_usuario, prm_item_permitido, prm_inserir, prm_alterar, prm_deletar) 
            VALUES ($id_p, 1, '$inseve', '$alteve', '$deleve');";
    // menu categorias
    $sql2 = "INSERT INTO tbl_permissoes (prm_usuario, prm_item_permitido, prm_inserir, prm_alterar, prm_deletar) 
            VALUES ($id_p, 2, '$inscat', '$altcat', '$delcat');";
    // menu periodos
    $sql3 = "INSERT INTO tbl_permissoes (prm_usuario, prm_item_permitido, prm_inserir, prm_alterar, prm_deletar) 
            VALUES ($id_p, 3, '$insprd', '$altprd', '$delprd');";
    // menu campus
    $sql4 = "INSERT INTO tbl_permissoes (prm_usuario, prm_item_permitido, prm_inserir, prm_alterar, prm_deletar)  
            VALUES ($id_p, 4, '$inscampus', '$altcampus', '$delcampus');";
    // menu usuarios
    $sql5 = "INSERT INTO tbl_permissoes (prm_usuario, prm_item_permitido, prm_inserir, prm_alterar, prm_deletar)  
            VALUES ($id_p, 5, '$insusu', '$altusu', '$delusu');";

    $exe1 = $Conn->query($sql1);
    $exe2 = $Conn->query($sql2);
    $exe3 = $Conn->query($sql3);
    $exe4 = $Conn->query($sql4);
    $exe5 = $Conn->query($sql5);

    if($ok == 1){
        $Conn->query("COMMIT");
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        $Conn->query("ROLLBACK");
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para alterar usuário na área administrativa
$app->post('/alterar/usuario', function (Request $request, Response $response) {
    require "conecta.php";

    $id   = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o id do usuario
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $inscampus = filter_input(INPUT_POST, 'inscampus', FILTER_SANITIZE_STRING);
    $altcampus = filter_input(INPUT_POST, 'altcampus', FILTER_SANITIZE_STRING);
    $delcampus = filter_input(INPUT_POST, 'delcampus', FILTER_SANITIZE_STRING);
    $inscat = filter_input(INPUT_POST, 'inscat', FILTER_SANITIZE_STRING);
    $altcat = filter_input(INPUT_POST, 'altcat', FILTER_SANITIZE_STRING);
    $delcat = filter_input(INPUT_POST, 'delcat', FILTER_SANITIZE_STRING);
    $inseve = filter_input(INPUT_POST, 'inseve', FILTER_SANITIZE_STRING);
    $alteve = filter_input(INPUT_POST, 'alteve', FILTER_SANITIZE_STRING);
    $deleve = filter_input(INPUT_POST, 'deleve', FILTER_SANITIZE_STRING);
    $insprd = filter_input(INPUT_POST, 'insprd', FILTER_SANITIZE_STRING);
    $altprd = filter_input(INPUT_POST, 'altprd', FILTER_SANITIZE_STRING);
    $delprd = filter_input(INPUT_POST, 'delprd', FILTER_SANITIZE_STRING);
    $insusu = filter_input(INPUT_POST, 'insusu', FILTER_SANITIZE_STRING);
    $altusu = filter_input(INPUT_POST, 'altusu', FILTER_SANITIZE_STRING);
    $delusu = filter_input(INPUT_POST, 'delusu', FILTER_SANITIZE_STRING);

    if($inscampus == "1"){
        $inscampus = 1;
    } else {
        $inscampus = 0;
    }

    if($altcampus == "1"){
        $altcampus = 1;
    } else {
        $altcampus = 0;
    }

    if($delcampus == "1"){
        $delcampus = 1;
    } else {
        $delcampus = 0;
    }

    if($inscat == "1"){
        $inscat = 1;
    } else {
        $inscat = 0;
    }

    if($altcat == "1"){
        $altcat = 1;
    } else {
        $altcat = 0;
    }

    if($delcat == "1"){
        $delcat = 1;
    } else {
        $delcat = 0;
    }

    if($inseve == "1"){
        $inseve = 1;
    } else {
        $inseve = 0;
    }

    if($alteve == "1"){
        $alteve = 1;
    } else {
        $alteve = 0;
    }

    if($deleve == "1"){
        $deleve = 1;
    } else {
        $deleve = 0;
    }

    if($insprd == "1"){
        $insprd = 1;
    } else {
        $insprd = 0;
    }

    if($altprd == "1"){
        $altprd = 1;
    } else {
        $altprd = 0;
    }

    if($delprd == "1"){
        $delprd = 1;
    } else {
        $delprd = 0;
    }

    if($insusu == "1"){
        $insusu = 1;
    } else {
        $insusu = 0;
    }

    if($altusu == "1"){
        $altusu = 1;
    } else {
        $altusu = 0;
    }

    if($delusu == "1"){
        $delusu = 1;
    } else {
        $delusu = 0;
    }

    $sql = "UPDATE tbl_usuarios 
            SET usu_nome = '$nome', usu_email = '$email', usu_usuario = '$usuario', usu_senha = '$senha' 
            WHERE usu_id = '$id';";

    // menu eventos
    $sql1 = "UPDATE tbl_permissoes 
             SET prm_inserir = '$inseve', prm_alterar = '$alteve', prm_deletar = '$deleve'
             WHERE prm_usuario = $id AND prm_item_permitido = 1;";
    // menu categorias
    $sql2 = "UPDATE tbl_permissoes 
             SET prm_inserir = '$inscat', prm_alterar = '$altcat', prm_deletar = '$delcat'
             WHERE prm_usuario = $id AND prm_item_permitido = 2;";
    // menu periodos
    $sql3 = "UPDATE tbl_permissoes 
             SET prm_inserir = '$insprd', prm_alterar = '$altprd', prm_deletar = '$delprd'
             WHERE prm_usuario = $id AND prm_item_permitido = 3;";
    // menu campus
    $sql4 = "UPDATE tbl_permissoes 
             SET prm_inserir = '$inscampus', prm_alterar = '$altcampus', prm_deletar = '$delcampus'
             WHERE prm_usuario = $id AND prm_item_permitido = 4;";
    // menu usuarios
    $sql5 = "UPDATE tbl_permissoes 
             SET prm_inserir = '$insusu', prm_alterar = '$altusu', prm_deletar = '$delusu'
             WHERE prm_usuario = $id AND prm_item_permitido = 5;";

    $exe1 = $Conn->query($sql1);
    $exe2 = $Conn->query($sql2);
    $exe3 = $Conn->query($sql3);
    $exe4 = $Conn->query($sql4);
    $exe5 = $Conn->query($sql5);

    if($Conn->query($sql)){
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para deletar usuário na área administrativa
$app->post('/deletar/usuario', function (Request $request, Response $response) {
    require "conecta.php";

    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o id do campus

    $sql = "DELETE FROM tbl_usuarios WHERE usu_id = '$id';";

    if($Conn->query($sql)){
        return $response->withJson(array("sucesso" => 1)); // monta JSON para a resposta
    } else {
        return $response->withJson(array("sucesso" => 0)); // monta JSON para a resposta
    }
});

// Rota para retorno do usuário selecionado, exibido na página
$app->get('/listar/usuario', function (Request $request, Response $response) {
    require "conecta.php";

    $id  = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT); // recebe o id do usuario

    // requisita os dados do usuario
    $sql = "SELECT * FROM tbl_item_permissao;";
    $exe = $Conn->query($sql);
    $cont = $exe->num_rows;

    $prd2 = array(); // listagem dos usuarios
    $i    = 1;
    $result = array(); // listagem dos menus, onde estarão separados os itens
    $menu  = null; // para guardar os nomes dos menus para cada índice do array $result
    $prd2[0]['cont'] = $cont;

    while ($reg = $exe->fetch_array()) {
        // requisita os dados do usuario
        $sqlu = "SELECT *
            FROM tbl_usuarios, tbl_permissoes
            WHERE 
                prm_usuario = usu_id AND prm_item_permitido = '".$reg['pri_id']."' AND usu_id = '".$id."' LIMIT 1;";
        $exeu = $Conn->query($sqlu);

        while ($reg2 = $exeu->fetch_array()) {
            if ($menu != $reg['pri_nome_menu']) { // Se o menu já gravado no primeiro loop for diferente do menu do loop atual, grava o novo menu na variável e escreve no array $result
                if ($menu != null) { // caso seja o loop inicial
                    $result[$menu] = $prd2; // colocar os eventos no menu
                }
                $menu = "" . $reg['pri_nome_menu'] . ""; // troca o nome do menu na variavel pra colocar as próximas permissões nele
                $prd2 = null; // limpa a lista
                $prd2 = array(); // inicia uma nova lista para o próximo menu
            }

            $prd2[0]['id'] = $reg2['usu_id'];
            $prd2[0]['nome'] = $reg2['usu_nome'];
            $prd2[0]['email'] = $reg2['usu_email'];
            $prd2[0]['usuario'] = $reg2['usu_usuario'];
            $prd2[0]['senha'] = $reg2['usu_senha'];
            $prd2[0]['permissao_id'] = $reg2['prm_id'];
            $prd2[0]['item_permitido'] = $reg2['prm_item_permitido'];
            $prd2[0]['inserir'] = $reg2['prm_inserir'];
            $prd2[0]['alterar'] = $reg2['prm_alterar'];
            $prd2[0]['deletar'] = $reg2['prm_deletar'];
        }

        if ($i == $cont) { // se a última posição, corresponder ao número de usuario encontrado
            $result[$menu] = $prd2; // incluir a última lista de eventos ao último mês encontrado
        }

        $i++;
    }

    return $response->withJson($result); // monta JSON para a resposta
});

// Login da área restrita
$app->post('/login', function (Request $request, Response $response) {

    $user = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $user_validate = v::alnum()->noWhitespace();
    $pass_validate = v::alnum()->noWhitespace()->length(5, 15);

    if($user_validate->validate($user) == false || $pass_validate->validate($pass) == false){

        $result = array('success' => false, 'msg' => 'Usuário ou senha inválidos', 'token' => false);
        return $response->withJson($result);

    } else {

        include "conecta.php";

        $sql = "SELECT usu_id, usu_nome, usu_usuario, usu_senha, prm_item_permitido, prm_inserir, prm_alterar, prm_deletar
                FROM tbl_usuarios, tbl_permissoes
                WHERE prm_usuario = usu_id AND usu_usuario = '$user';";
        $exe = $Conn->query($sql);
        $num = $exe->num_rows;

        $item_perm  = array();
        $permissoes = array();

        if ($num == 0) {
            // Falha de login
            $result = array('success' => false, 'msg' => 'Usuário não encontrado', 'token' => false);
            return $response->withJson($result);

        } else {
            while($reg = $exe->fetch_array()) {
                if($pass == $reg['usu_senha']) {
                    // Login com sucesso
                    $uid = $reg['usu_id'];
                    $user = $reg['usu_nome'];

                    $permissoes['inserir'] = $reg['prm_inserir'];
                    $permissoes['alterar'] = $reg['prm_alterar'];
                    $permissoes['deletar'] = $reg['prm_deletar'];

                    $item_perm[$reg['prm_item_permitido']] = $permissoes;
                } else {
                    // Falha de login
                    $result = array('success' => false, 'msg' => 'Senha incorreta', 'token' => false);
                    return $response->withJson($result);
                    exit;
                }
            }

            $signer = new Sha256();

            $token = (new Builder())->setIssuer('http://www.ufmt.br')// Configures the issuer (iss claim)
            ->setAudience('APP')// Configures the audience (aud claim)
            //->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
            ->setNotBefore(time())// Configures the time that the token can be used (nbf claim)
            ->setExpiration(time() + 3600)// Configures the expiration time of the token (nbf claim)
            ->set('uid', $uid)// Configures a new claim, called "uid"
            ->set('user', $user)
            ->set('permissoes', $item_perm)
                ->sign($signer, 'calendarioufmt')// creates a signature using "testing" as key
                ->getToken(); // Retrieves the generated token

            $result = array('success' => true, 'msg' => 'Login ok', 'token' => ''.$token.'', 'id' => $uid);
            return $response->withJson($result);
        }
    }
});

// Consultar Login da área restrita através do token
$app->post('/consulta/login', function (Request $request, Response $response) {

    $t = filter_input(INPUT_POST, 'token');
    $result = null;

    try {
        $token = (new Parser())->parse($t);
    } catch (Exception $exception) {
        $result = array('success' => false);
    }

    $validationData = new ValidationData();
    $validationData->setIssuer('http://www.ufmt.br');
    $validationData->setAudience('APP');

    $signer = new Sha256();

    if($token->validate($validationData) && $token->verify($signer, 'calendarioufmt')){
        $token->getClaims(); // Retrieves the token claims
        $result = array('success' => true, 'permissoes' => $token->getClaim('permissoes'), 'id' => $token->getClaim('uid'));
        return $response->withJson($result);
    } else {
        $result = array('success' => false);
        return $response->withJson($result);
    }
});