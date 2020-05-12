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
?>
<div class="py-2">
	<div class="list-group">
	<li class="list-group-item list-group-item-secondary text-center">Cartas en Mano</li>
<?php
$arrayColoresCaballo = array( "a" => "list-group-item-success", "b" => "list-group-item-warning", "c" => "list-group-item-primary", "d" => "list-group-item-danger", "x" => "list-group-item-light" );
$contador = 0;
foreach($mano as $clave => $valor)
{
?>
	<a class="list-group-item list-group-item-action <?= $arrayColoresCaballo[$mano[$contador]['caballo']]?>" href="#"><?= $mano[$contador]['caballo']." + ".$mano[$contador]['avanza']?></a>
<?php
	$contador++;
}
?>
	</div>
</div>


