<?php
require "conecta.php";
//header('Content-Type: text/html; charset=utf-8');

if (login($usu_email, $password, $Conn) == true) {
    // Login com sucesso
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('success' => true, 'message' => 'Login efetuado com sucesso!', 'redirect' => ''.$redirect.''));
} else {
    // Falha de login
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('success' => false, 'message' => 'Login ou Senha incorretos!'));
}