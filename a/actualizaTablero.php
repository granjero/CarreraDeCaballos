<?php
// DEVUELVE UN ARRAY CON EL VALOR DE CADA CABALLO PARA PONERLO EN EL TABLERO.
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../clases/conexion.php');
// llama posicion caballos
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call get_caballo_carrera(?)');
$query->bind_param('s', $_SESSION['idCDC']);
$query->execute();
$query->bind_result($caballoA, $caballoB, $caballoC, $caballoD);
while ($query->fetch())
{
	//echo $nombre."<br>";
	$CABALLO =
		array(
			"a" => $caballoA
			,"b" => $caballoB
			,"c" => $caballoC
			,"d" => $caballoD
		);
}
$query->close();

echo json_encode($CABALLO);

?>
