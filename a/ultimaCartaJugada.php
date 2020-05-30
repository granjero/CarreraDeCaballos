
<?php
// DEVUELVE UN ARRAY CON EL VALOR DE CADA CABALLO PARA PONERLO EN EL TABLERO.
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../clases/conexion.php');
// llama ultima carta
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call get_ultima_carta (?)');
$query->bind_param('s', $_SESSION['idCDC']);
$query->execute();
$query->bind_result($carta);
$cartaJugada = $query->fetch();
$query->close();

//echo $carta;

// llama carta
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call get_carta (?)');
$query->bind_param('i', $carta);
$query->execute();
$query->bind_result($id_carta, $caballo, $posicion, $avanza, $multiplica, $maximo);
$cartaJugada = $query->fetch();
$query->close();


$arrayColoresCaballo = array( "a" => "bg-success", "b" => "bg-warning", "c" => "bg-primary", "d" => "bg-danger", "x" => "bg-light" );
$arrayEmojiCaballo = array( "a" => "🐢", "b" => "🐪", "c" => "🦕", "d" => "🐖", "x" => "🃏");


$textoCarta  = $arrayEmojiCaballo[$caballo];
$textoCarta .= $posicion == 0 ? "+" . $avanza : "";
$textoCarta .= $posicion == 1 ? "1ºX3" : "";
$textoCarta .= $posicion >= 2 && $posicion <= 10 ? $posicion ."º+". $avanza : "";
$textoCarta .= $posicion == 99 ? "+" . $avanza ."mx" : "";

?>

	<div class="alert bg-light" role="alert">Ultima Carta Jugada: <?= $textoCarta ?></div>
