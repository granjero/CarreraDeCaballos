<?php
session_start();

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

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
	$CABALLO[] =
		array(
			"a" => $caballoA
			,"b" => $caballoB
			,"c" => $caballoC
			,"d" => $caballoD
		);
}
$query->close();
//$_SESSION['posicionesCaballosTablero'] = $CABALLO[0];

//var_dump($_SESSION);
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call check_apuestas_cerradas(?)');
$query->bind_param('s', $_SESSION['idCDC']);
$query->execute();
$query->bind_result($nombre, $cerrada);
while ($query->fetch())
{
	//echo $nombre."<br>";
	$apuestasCerradas [] =
		array(
			"nombre" => $nombre
			,"cerrada" => $cerrada
		);
}
$query->close();
//var_dump($apuestasCerradas);
$sePuedeComenzar = TRUE;
$todosLosJugadores = FALSE;
$nombreanterior = '';
$contador = 0;
$contadorJugadores = 0;
foreach($apuestasCerradas as $llave => $valor)
{

	if($nombreanterior != $apuestasCerradas[$contador]['nombre'])
	{
		$contadorJugadores++;
		$nombreanterior = $apuestasCerradas[$contador]['nombre'];
	}

	if($apuestasCerradas[$contador]['cerrada'] == 0)
	{
		$sePuedeComenzar = FALSE;
	}
	$contador++;
}

if($contadorJugadores == count($_SESSION['listaJugadores']))
{
	$todosLosJugadores = TRUE;
}

//var_dump($sePuedeComenzar);
//var_dump($masDeUnJugador);
if($sePuedeComenzar && $todosLosJugadores)
{
?>
	<a href="a/a.php?a=comenzarJuego" role="button" class="btn btn-lg btn-primary btn-block">Comenzar el Juego</a>
<?php
}
else
{
?>
	<a href="#" role="button" class="btn btn-lg btn-light btn-block disabled">Esperando que todos cierren sus apuestas...</a>
<?php
}
