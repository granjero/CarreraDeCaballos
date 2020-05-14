<?php
session_start();

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include_once('../clases/conexion.php');

//var_dump($_SESSION);
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call get_cartas_repartidas(?,?)');
$query->bind_param('ss', $_SESSION['idCDC'], $_SESSION['nombre']);
$query->execute();
$query->bind_result($idCarta, $caballo, $posicion, $avanza, $multiplica, $maximo);
while ($query->fetch())
{
	//echo $nombre."<br>";
	$mano[] =
		array(
			"idCarta" => $idCarta
			,"caballo" => $caballo
			,"posicion" => $posicion
			,"avanza" => $avanza
			,"multiplica" => $multiplica
			,"maximo" => $maximo
		);
}
$query->close();
//die(var_dump($mano));


$arrayColoresCaballo = array( "a" => "list-group-item-success", "b" => "list-group-item-warning", "c" => "list-group-item-primary", "d" => "list-group-item-danger", "x" => "list-group-item-light" );
$arrayEmojiCaballo = array( "a" => "🐢", "b" => "🐪", "c" => "🦕", "d" => "🐖", "x" => "🃏");
?>

<div class="col-12 py-2">
	<ul class="list-group">
		<li class="list-group-item list-group-item-secondary text-center">Suertes en Mano</li>
	</ul>
</div>

<div class="col-12">
	<div class="list-group d-flex flex-row flex-wrap">
	<?php
	$contador = 0;
	foreach($mano as $clave => $valor)
	{
		$textoCarta  = $arrayEmojiCaballo[$mano[$contador]['caballo']];
		$textoCarta .= $mano[$contador]['posicion'] == 0 ? "+" . $mano[$contador]['avanza'] : "";
		$textoCarta .= $mano[$contador]['posicion'] == 1 ? "1ºX3" : "";
		$textoCarta .= $mano[$contador]['posicion'] >= 2 && $mano[$contador]['posicion'] <= 10 ? $mano[$contador]['posicion'] ."º+". $mano[$contador]['avanza'] : "";
		$textoCarta .= $mano[$contador]['posicion'] == 99 ? "max" . $mano[$contador]['avanza'] : "";
	?>
		<a class="text-center list-group-item w-50 list-group-item-action <?= $arrayColoresCaballo[$mano[$contador]['caballo']]?>" href="#"><?= $textoCarta ?></a>
	<?php
		$contador++;
	}
	?>
		</div>
</div>
