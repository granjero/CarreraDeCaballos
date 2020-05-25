<!DOCTYPE html>
<?php 
session_start();

$idCarta = filter_input(INPUT_GET, 'idCarta');

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include_once('../clases/conexion.php');



// LEE LA CARTA DE LA BASE DE DATOS Y LA GUARDA EN $CARTA 
// ======================================================
$conn = New claseConexion();
$sql = NULL; 
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call get_carta(?)');
$query->bind_param('i', $idCarta);
$query->execute();
$query->bind_result($id, $animal, $posicion, $avanza, $multiplica, $maximo);
while ($query->fetch())
{
	//echo $nombre."<br>";
	$CARTA =
		array(
			"caballo"		=> $animal		// Letra de la carta
			,"posicion"		=> $posicion	// Posición a la que aplica la carta (99 !primer puesto)
			,"avanza"		=> $avanza		// Cuantos porotos se suman a al caballo
			,"maximo"		=> $maximo		// relación con el primero
			,"id"			=> $id
		);
}
$query->close();

// LEE LA CANTIDAD DE POROTOS LOS CABALLOS Y LA GUARDA EN $CABALLOS
// ===================================================================
$conn = New claseConexion();
$sql = NULL;
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call get_caballo_carrera(?)');
$query->bind_param('s', $_SESSION['idCDC']);
$query->execute();
$query->bind_result($caballoA, $caballoB, $caballoC, $caballoD);
while ($query->fetch())
{
	//echo $nombre."<br>";
	$CABALLOS =
		array(
			"a"		=> $caballoA	// cantidad de porotos de cada caballo
			,"b"	=> $caballoB	// cantidad de porotos de cada caballo
			,"c"	=> $caballoC	// cantidad de porotos de cada caballo
			,"d"	=> $caballoD	// cantidad de porotos de cada caballo
		);
}
$query->close();
arsort($CABALLOS);

// ARMA LA TABLA DE POSICIONES
// ===========================
$anterior = 9999;
$contador = 0;
$contadorExtra = 0;
foreach($CABALLOS as $llave => $valor)
{
	if($anterior != $valor)
	{
		$contador += $contadorExtra;
		$contador++;
		$contadorExtra = 0;
	}
	else
	{
		$contadorExtra++;
	}
	$POSICIONES[$llave] = $contador; 
	$anterior = $valor;
}
asort($POSICIONES);

// ARMA LA LISTA DE REPETIDOS PARA LA ELECCION
// ===========================================
foreach($POSICIONES as $llave => $valor)
{
	if ($valor == $CARTA['posicion'])
	{
		$ELECCION[] = $llave;
	}
}


if ($CARTA['posicion'] == 99)
{
	unset($ELECCION);
	foreach($POSICIONES as $llave => $valor)
	{
		if ($valor != 1)
		{
			$ELECCION[] = $llave;
		}
	}
}
if(is_null($ELECCION))
{
	header("Location: ../");
	exit;
}

// ARRAYS DE INTERES
// =================
$arrayColoresCaballo = array(
	"a" => '<option class="list-group-item-success" value="a">🐢 - Verde - Casillero nº' . $CABALLOS['a'] . '</option>'
	,"b" => '<option class="list-group-item-warning" value="b">🐪 - Amarillo - Casillero nº' . $CABALLOS['b'] . '</option>'
	,"c" => '<option class="list-group-item-info" value="c">🦕 - Azul - Casillero nº' . $CABALLOS['c'] . '</option>'
	,"d" => '<option class="list-group-item-danger" value="d">🐖 - Rojo - Casillero nº' . $CABALLOS['d'] . '</option>'
);
$arrayEmojiCaballo = array( "a" => "🐢", "b" => "🐪", "c" => "🦕", "d" => "🐖", "x" => "🃏");

//die();



//var_dump($CARTA);
//var_dump($CABALLOS);
//var_dump($POSICIONES);


if ($CARTA['posicion'] == 1 || $CARTA['posicion'] == 99)
{

	?>
	<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Carrera de  &#127937; &nbsp; &#128014;</title>
			<link rel="stylesheet" href="../css/bootstrap.min.css" media="all">
			<link rel="stylesheet" href="../css/cdc.css" media="all">
		</head>

		<body>
	<?php // var_dump($_SESSION); ?>
	<?php // var_dump($_SESSION['posicionesJuego']); ?>

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
				<div class="col-3">
					<div class="card" style="width: 18rem;">
					<h5 class="card-header">CARTA JUGADA</h5>
					<div class="card-body text-center">
							<div style="font-size: 5em;">
								<?= $arrayEmojiCaballo[$CARTA['caballo']]?>
							</div> 
						</div> 
						<div class="card-body">
						<h5 class="card-title"> + <?= $CARTA['avanza']; ?> MAX</h5>
							<p class="card-text">Avanza <?= $CARTA['avanza']; ?> casilleros si no es primero.</p>
							<!--<a href="#" class="btn btn-primary">Go somewhere</a>-->
						</div>
					</div>
				</div>
				<div id="perfecta" class="col-3">
				<form action="a/jugarCarta.php" method="GET">
					<label for="apuestaCaballo">Hay <?php echo count($ELECCION); ?> animalitos que no van primeros. Seleccioná a cual va dirigida:</label>
					<select id="apuestaCaballo" name="caballo" class="form-control form-control-lg" onchange="objetivo()">
						<option selected value="0" disabled>Seleccionar...</option>
						<?php
						foreach($ELECCION as $llave => $valor)
						{
							echo $arrayColoresCaballo[$valor];
						}
						?>
					</select>
					<input type="hidden" name="idCarta" value="<?= $idCarta ?>">
					<br /><button id="boton" type="submit" name="jugar" value="jugar" class="btn btn-success btn-lg btn-block" disabled>Jugar Carta</button>
				</form>
				</div>
			</div>
		</div>


		<script src="../js/jquery-3.5.1.min.js"></script>
		<!--<script src="../js/popper.min.js"></script>-->
		<!--<script src="../js/bootstrap.min.js"></script>-->
		<script src="../js/bootstrap.bundle.min.js"></script>

	<script>
	//habilita el boton de enviar cuando cambia el valor del select
	var objetivo = function() {
		console.log('onbherkj');
		if ((document.getElementById('apuestaCaballo').value != 0))
		{
			document.getElementById('boton').removeAttribute('disabled', '');
		} else {
			document.getElementById('boton').setAttribute('disabled', '');
		}
	}
	</script>

<?php

}
else
{

	?>
	<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Carrera de  &#127937; &nbsp; &#128014;</title>
			<link rel="stylesheet" href="../css/bootstrap.min.css" media="all">
			<link rel="stylesheet" href="../css/cdc.css" media="all">
		</head>

		<body>
	<?php // var_dump($_SESSION); ?>
	<?php // var_dump($_SESSION['posicionesJuego']); ?>

		<div class="container-fluid py-1 px-4">
			<div class="row">
				<div class="col-3">
					<div class="card" style="width: 18rem;">
					<h5 class="card-header">CARTA JUGADA</h5>
					<div class="card-body text-center">
							<div style="font-size: 5em;">
								<?= $arrayEmojiCaballo[$CARTA['caballo']]?>
							</div> 
						</div> 
						<div class="card-body">
						<h5 class="card-title"><?= $CARTA['posicion']; ?>º + <?= $CARTA['avanza']; ?></h5>
							<p class="card-text">El puesto número <?= $CARTA['posicion']; ?> avanza <?= $CARTA['avanza']; ?> casilleros.</p>
							<!--<a href="#" class="btn btn-primary">Go somewhere</a>-->
						</div>
					</div>
				</div>
				<div id="perfecta" class="col-3">
				<form action="a/jugarCarta.php" method="GET">
					<label for="apuestaCaballo">Hay <?php echo count($ELECCION); ?> animalitos en la casilla nº <?= $CABALLOS[$ELECCION[0]] ?> en el puesto nº <?= $CARTA['posicion']; ?>. Seleccioná a cual va dirigida:</label>
					<select id="apuestaCaballo" name="caballo" class="form-control form-control-lg" onchange="objetivo()">
						<option selected value="0" disabled>Seleccionar...</option>
						<?php
						foreach($ELECCION as $llave => $valor)
						{
							echo $arrayColoresCaballo[$valor];
						}
						?>
					</select>
					<input type="hidden" name="idCarta" value="<?= $idCarta ?>">
					<br /><button id="boton" type="submit" name="jugar" value="jugar" class="btn btn-success btn-lg btn-block" disabled>Jugar Carta</button>
				</form>
				</div>
			</div>
		</div>


		<script src="../js/jquery-3.5.1.min.js"></script>
		<!--<script src="../js/popper.min.js"></script>-->
		<!--<script src="../js/bootstrap.min.js"></script>-->
		<script src="../js/bootstrap.bundle.min.js"></script>

	<script>
	//habilita el boton de enviar cuando cambia el valor del select
	var objetivo = function() {
		console.log('onbherkj');
		if ((document.getElementById('apuestaCaballo').value != 0))
		{
			document.getElementById('boton').removeAttribute('disabled', '');
		} else {
			document.getElementById('boton').setAttribute('disabled', '');
		}
	}
	</script>

<?php

}


//var_dump($ELECCION);
//var_dump($CARTA['posicion']);





if(function_exists('jsScripts'))
{
	jsScripts();
}

function randomAnimal($cant)
{
	//$animal = array("🦓","🦒","🐎","🐂","🐫","🦒","🐖","🦕","🦏","🦍","🐩","🐏","🐐","🐢","🐓","🐜","🐆");
	$animal = array("🐖","🦕","🐢","🐪");
	shuffle($animal);
	for($i = 0; $i < $cant; $i++)
	{
		echo $animal[$i]." ";
	}
}
?>

	</body>
</html>
