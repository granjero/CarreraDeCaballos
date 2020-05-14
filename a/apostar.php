<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include_once('../clases/conexion.php');

var_dump($_POST);

$caballo = $_POST['caballo'].$_POST['caballoBifecta'];
// Hace la apuesta 
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call set_apuesta (?,?,?,?,?)');

$query->bind_param('ssiis'
,$_SESSION['idCDC']
,$_SESSION['nombre']
,$_POST['tipoApuesta']
,$_POST['monto']
,$caballo
);

$query->execute();
$query->close();


// Actualiza dinero del jugador
$_SESSION['dinero'] = $_SESSION['dinero'] - $_POST['monto'];
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call acutaliza_saldo_jugador (?,?,?)');

$query->bind_param('ssi'
,$_SESSION['idCDC']
,$_SESSION['nombre']
,$_SESSION['dinero']
);

$query->execute();
$query->close();


$_SESSION['apuestasJugador'][] = array(
"tipoApuesta" => $_POST['tipoApuesta']
,"monotoApuesta" => $_POST['monto']
,"caballo" => $caballo
);

header('Location: ../');

?>
