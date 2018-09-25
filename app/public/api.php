<?php
require "../src/conecta.php";
header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json; charset=utf-8');

$stmt = $Conn->prepare("SELECT usu_id, usu_nome, usu_email, usu_usuario, usu_senha FROM tbl_usuarios /*WHERE email = ? LIMIT 1*/");
//$stmt->bind_param('s', filter_var($email, FILTER_SANITIZE_EMAIL));  // Relaciona  "$email" ao parâmetro.
$stmt->execute();    // Executa a tarefa estabelecida.
$stmt->store_result();
$cont = $stmt->num_rows;

// obtém variáveis a partir dos resultados.
$stmt->bind_result($usu_id,$usu_nome, $usu_email, $usu_usuario, $usu_senha);
$stmt->fetch();

$user = array();
$user[0]['cont'] = $cont;
$user[0]['id'] = $usu_id;
$user[0]['nome'] = $usu_nome;
$user[0]['email'] = $usu_email;
$user[0]['usuario'] = $usu_usuario;
$user[0]['senha'] = $usu_senha;

echo json_encode($user);