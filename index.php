<!DOCTYPE html>
<?php 
session_start();

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include_once('clases/conexion.php');
?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Carrera de  &#127937; &nbsp; &#128014;</title>
		<link rel="stylesheet" href="css/bootstrap.min.css" media="all">
	</head>

	<body>
<?php var_dump($_SESSION); ?>
	<div class="container-fluid py-1 px-4">
		<div class="row align-middle bg-secondary">
			<div class="col-11 bg-secondary text-center">
				<!--<p class="h1">&#127937; &nbsp; &#128014; &nbsp; &#128014; &nbsp; &#128014;</p>-->
				<p class="h1">&#127937; <?= randomAnimal(4)?></p>
			</div>
			<div class="col-1 bg-secondary align-self-center">
				<a class="btn btn-outline-danger" href="a/a.php?a=salir" role="button">SALIR</a>
			</div>
		</div>
	</div>
	<div class="container-fluid py-1 px-4">
		<div class="row">
			<!--<div class="col-1 bg-light">-->
				<?php
				//include_once('secciones/barra.php');
				?>
			<!--</div>-->
			<!--<div class="col-11">-->
				<?php
				include_once('secciones/principal.php');
				?>
			<!--</div>-->
		</div>
	</div>


	<script src="js/jquery-3.5.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<?php
	if(function_exists('jsScripts'))
	{
		jsScripts();
	}

	function randomAnimal($cant)
	{
		$animal = array("🦓","🦒","🐎","🐂","🐫","🦒","🐖","🦕","🦏","🦍","🐩","🐏","🐐","🐢","🐓","🐜","🐆");
		shuffle($animal);
		for($i = 0; $i < $cant; $i++)
		{
			echo $animal[$i]." ";
		}
	}
	?>

	</body>
</html>
