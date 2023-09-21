<?php

require_once '../models/Cliente.php';
require_once '../utils/Crypter.php';

$cliente = new Cliente();

if (isset($_POST['operacion'])) {

  if ($_POST['operacion'] == 'add') {
    $claveEncriptada = encrypt($_POST['claveacceso']);

    $data = [
      "apellidos"     => $_POST['apellidos'],
      "nombres"       => $_POST['nombres'],
      "dni"           => $_POST['dni'],
      "claveacceso"   => $claveEncriptada
    ];
    $cliente->create($data);
  }

  if ($_POST['operacion'] == 'login') {
    $response = [
      "login"       => false,
      "idcliente"   => "",
      "apellidos"   => "",
      "nombres"     => ""
    ];

    $result = $cliente->login($_POST['username']);
    $passwordInput = $_POST['password'];

    if ($result && password_verify($passwordInput, $result['claveacceso'])) {
      $response['login'] = true;
      $response['idcliente'] = $result['idcliente'];
      $response['apellidos'] = $result['apellidos'];
      $response['nombres'] = $result['nombres'];
    } else {
      $response['message'] = 'Credenciales inválidas';
    }

    echo json_encode($response);
  }
}

if (isset($_GET['operacion'])) {

  if ($_GET['operacion'] == 'search') {
    $result = $cliente->searchByDNI($_GET['dni']);
    echo json_encode($result);
  }
}
