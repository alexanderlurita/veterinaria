<?php

require_once '../models/Cliente.php';
require_once '../utils/Crypter.php';

$cliente = new Cliente();

if (isset($_POST['operacion'])) {

  if ($_POST['operacion'] == 'add') {
    if ($_POST['claveacceso'] != '') {
      $claveEncriptada = encrypt($_POST['claveacceso']);

      $data = [
        "apellidos"     => $_POST['apellidos'],
        "nombres"       => $_POST['nombres'],
        "dni"           => $_POST['dni'],
        "claveacceso"   => $claveEncriptada
      ];
      $result = $cliente->create($data);
      echo json_encode($result);
    } else {
      echo json_encode(-1);
    }
  }

  if ($_POST['operacion'] == 'login') {
    $response = [
      "login"       => false,
      "idcliente"   => "",
      "dni"         => "",  
      "tipocliente" => "",
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
      $response['dni'] = $result['dni'];
      $response['tipocliente'] = $result['tipocliente'];
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
