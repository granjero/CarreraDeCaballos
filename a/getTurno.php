<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../clases/conexion.php');

//var_dump($_SESSION);
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call get_turno_actual(?)');
$query->bind_param('s', $_SESSION['idCDC']);
$query->execute();
$query->bind_result($turnoActual);
$query->fetch();
$query->close();
$_SESSION['turnoActual'] = $turnoActual;
//die(var_dump($turnoActual));

echo $turnoActual;

?>

