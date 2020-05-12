<?php
//session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//include_once('../clases/conexion.php');
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call get_mazo');
$query->execute();
$query->bind_result($idCarta, $caballo, $posicion, $avanza, $multiplica, $maximo);
while ($query->fetch())
{
	//echo $nombre."<br>";
	$mazo[] =
		array(
			"idCarta"		=> $idCarta
			,"caballo"		=> $caballo
			,"posicion"		=> $posicion
			,"avanza"		=> $avanza
			,"multiplica"	=> $multiplica
			,"maximo"		=> $maximo
		);
}
$query->close();

$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call lista_jugadores (?)');
//$query->bind_param('s', $idses);
$query->bind_param('s', $_SESSION['idCDC']);
$query->execute();
$query->bind_result($nombre, $admin);
while ($query->fetch())
{
	if(rtrim($nombre) == 'LISTO_JUGADORES')
	{
		break;
	}
	//echo $nombre."<br>";
	$listaJugadores[] =
		array(
			"nombre" => $nombre
		);
}
$query->close();

shuffle($mazo);
shuffle($mazo);
shuffle($mazo);

$contadorMazo = 0;
$contadorJugadores = 0;
$cantidadJugadores = count($listaJugadores);


$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call reparte_mazo(?,?,?,?,?,?,?,?)');

foreach($mazo as $carta => $valor)
{
	$query->bind_param('ssisiiii'
	,$_SESSION['idCDC']
	,$listaJugadores[$contadorJugadores]['nombre']
	,$mazo[$contadorMazo]['idCarta']
	,$mazo[$contadorMazo]['caballo']
	,$mazo[$contadorMazo]['posicion']
	,$mazo[$contadorMazo]['avanza']
	,$mazo[$contadorMazo]['multiplica']
	,$mazo[$contadorMazo]['maximo']
);
	$query->execute();
	$contadorJugadores++;
	$contadorJugadores = $contadorJugadores >= $cantidadJugadores ? 0 : $contadorJugadores ;
	$contadorMazo++;
}
$query->close();
?>
