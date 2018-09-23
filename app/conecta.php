<?php

//DEFINE O LOGIN E SENHA PARA CONEXÃO COM O BANCO DE DADOS
$ConnLocal = "localhost";
$ConnLogin = "root";
$ConnSenha = "";
$ConnDatabase = "calendario";

// CONECTA-SE COM O BANCO DE DADOS MySQLi
global $Conn;
$Conn = new mysqli($ConnLocal, $ConnLogin, $ConnSenha, $ConnDatabase);
if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());