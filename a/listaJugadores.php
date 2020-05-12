<?php
session_start();

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include_once('../clases/conexion.php');
//$idses = 'GtjMORXP3W';
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
	//echo $nombre."<br>";
	$lista[] =
		array(
			"nombre" => $nombre
			,"admin" => $admin
		);
}
$query->close();
//die(var_dump($lista));
$fila = 0;
foreach($lista as $llave => $valor)
{
	if(rtrim($lista[$fila]['nombre']) == 'LISTO_JUGADORES')  // Corta la ejecución si en la tabla dice LISTO y pone el botón de ver cartas
	{
		if($_SESSION['esperandoApuestas']) // Corta la ejecución si se esperan apuestas
		{
			break;
		}
	?>
		<a class="btn btn-block btn-success" href="./a/a.php?a=ver_cartas" role="button">Ver Cartas</a>
	<?php
		break;
	}
?>
	<li id="<?= $lista[$fila]['nombre'] ?>" class="list-group-item"><?= $lista[$fila]['nombre'] ?></li>
<?php
$fila++;
}
?>
