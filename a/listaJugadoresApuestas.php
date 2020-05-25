<?php
session_start();

if ($_SESSION['admin'])
{
	sleep(8);
}

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$arrayTipoApuestas = array("1" => " A Ganador ", "2" => " Segundo ", "3" => " Bifecta ");
$arrayColoresCaballo = array(
	"a" => " 🐢 Verde"
	, "b" => " 🐪 Amarillo"
	, "c" => " 🦕 Azul"
	, "d" => " 🐖 Rojo"
	,"ab" => "1º 🐢 Verde - 2º 🐪 Amarillo"
	,"ac" => "1º 🐢 Verde - 2º 🦕 Azul"
	,"ad" => "1º 🐢 Verde - 2º 🐖 Rojo"
	,"ba" => "1º 🐪 Amarillo - 2º 🐢 Verde"
	,"bc" => "1º 🐪 Amarillo - 2º 🦕 Azul"
	,"bd" => "1º 🐪 Amarillo - 2º 🐖 Rojo"
	,"ca" => "1º 🦕 Azul - 2º 🐢 Verde"
	,"cb" => "1º 🦕 Azul - 2º 🐪 Amarillo"
	,"cd" => "1º 🦕 Azul - 2º 🐖 Rojo"
	,"da" => "1º 🐖 Rojo - 2º 🐢 Verde"
	,"db" => "1º 🐖 Rojo - 2º 🐪 Amarillo"
	,"dc" => "1º 🐖 Rojo - 2º 🦕 Azul"
	);

$arrayColoresCaballoSinPalabra = array(
	"a" => " 🐢 "
	, "b" => " 🐪 "
	, "c" => " 🦕 "
	, "d" => " 🐖 "
);
//include_once('../clases/conexion.php');
// LEE LAS APUESTAS DE LA BASE Y GUARDA EN LISTAAPUESTAS
// =====================================================
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call get_juagadores_apuestas (?)');
//$query->bind_param('s', $idses);
$query->bind_param('s', $_SESSION['idCDC']);
$query->execute();
$query->bind_result($nombre, $tipo, $monto, $caballo);
while ($query->fetch())
{
	//echo $nombre."<br>";
	$listadoApuestas[] =
		array(
			"nombre"	=> $nombre
			,"tipo"		=> $tipo
			,"monto"	=> $monto 
			,"caballo"	=> $caballo 
		);
}
$query->close();

// LEE EL SALDO DE LOS JUGADORES
// =============================
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call get_dinero_jugadores (?)');
//$query->bind_param('s', $idses);
$query->bind_param('s', $_SESSION['idCDC']);
$query->execute();
$query->bind_result($nombre, $monto);
while ($query->fetch())
{
	//echo $nombre."<br>";
	$DINEROJUGADORES[$nombre] = $monto;
}
$query->close();
//die(var_dump($DINEROJUGADORES));

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
	if($contador > 2)
	{
		continue;
	}
	$POSICIONES[$llave] = $contador; 
	$POSICIONESINVERT[$contador] = $llave;
	$anterior = $valor;
}
asort($POSICIONES);

//var_dump($POSICIONES);
?>

				<!--<div class="row">-->
				<div class="col-3 text-center">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">✨✨Primer Puesto✨✨</h5> <h1> 🏆 <?= $print = $arrayColoresCaballoSinPalabra[$POSICIONESINVERT[1]] ?> </h1>
						</div>
					</div>
				</div>
				<div class="col-3 text-center">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">Segundo Puesto</h5> <h1> 🥈 <?= $print = $arrayColoresCaballoSinPalabra[$POSICIONESINVERT[2]]  ?> </h1>
						</div>
					</div>
				</div>
				</div>



<div class="row">
<?php
// ARMA LA LISTA EN HTML
// =====================
$nombreAnterior = 'sdfsdihegjhbgdsfgvdsfhn';
$imprimeCierre = FALSE;
foreach($listadoApuestas as $llave => $apuesta)
{
	if ($apuesta['nombre'] != $nombreAnterior)
	{
		if($imprimeCierre)
		{
		?>
				</div>
				<h5 class="card-header"> <?= 'Ganancias $' . $dineroTotaljugador?> <br> + Saldo $<?= $DINEROJUGADORES[$nombreAnterior] ?> <br> TOTAL 💸💸💸: $<?= $dineroTotaljugador + $DINEROJUGADORES[$nombreAnterior]  ?> </h5>
			</div> <!-- card -->
		</div> <!-- col -->
		<?php


		if ($_SESSION['admin'])
		{
			$dineroDB = $dineroTotaljugador + $DINEROJUGADORES[$nombreAnterior];
			//var_dump($_SESSION['idCDC']);
			//var_dump($nombreAnterior);
			//var_dump($dineroTotaljugador);
			// actualiza dinero jugador
			$conn = New claseConexion();
			$sql = NULL; //
			$sql = $conn->Open();
			$sql->select_db("estonoesunaweb_CDC");
			$query = $sql->prepare('call set_dinero_jugador(?,?,?)');
			$query->bind_param('ssi'
				,$_SESSION['idCDC']
				,$nombreAnterior
				,$dineroDB
			);
			$query->execute();
			$query->close();
		}


		}
		$imprimeCierre = TRUE;
		$dineroTotaljugador = 0;
		$nombreAnterior = $apuesta['nombre'];
		?>
		<div class="col-3">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title"><?= $apuesta['nombre'] ?> apostó:</h5>
		<?php
	}
	$dineroGanado = 0;	
	switch ($apuesta['tipo']) 
	{
		case 1:
			$dineroGanado += $POSICIONES[$apuesta['caballo']] == $apuesta['tipo'] ? $apuesta['monto'] * 3 : 0;
			break;
		case 2:
			$dineroGanado += $POSICIONES[$apuesta['caballo']] == $apuesta['tipo'] ? $apuesta['monto'] * 2 : 0;
			break;
		case 3:
			//var_dump($apuesta['caballo']);
			$primP = substr($apuesta['caballo'], 0, 1);
			$segP = substr($apuesta['caballo'], -1);
			
			//var_dump($POSICIONES[$primP]);
			//var_dump($primP);
			//var_dump($POSICIONES[$segP]);
			//var_dump($segP);
			
			$dineroGanado += ($POSICIONES[$primP] == 1 && $POSICIONES[$segP] == 2) ? $apuesta['monto'] * 5 : 0;
			break;
	}
	?>
		<p class="card-text">🎫 $ <?= $apuesta['monto'] . $arrayTipoApuestas[$apuesta['tipo']] . $arrayColoresCaballo[$apuesta['caballo']]?> <br> Ganó: $<?= $dineroGanado ?></p>
	<?php
	$dineroTotaljugador += $dineroGanado; 
}

		if ($_SESSION['admin'])
		{
			$dineroDB = $dineroTotaljugador + $DINEROJUGADORES[$nombreAnterior];
			//var_dump($_SESSION['idCDC']);
			//var_dump($nombreAnterior);
			//var_dump($dineroTotaljugador);
			// actualiza dinero jugador
			$conn = New claseConexion();
			$sql = NULL; //
			$sql = $conn->Open();
			$sql->select_db("estonoesunaweb_CDC");
			$query = $sql->prepare('call set_dinero_jugador(?,?,?)');
			$query->bind_param('ssi'
				,$_SESSION['idCDC']
				,$nombreAnterior
				,$dineroDB
			);
			$query->execute();
			$query->close();
		}




	?>
		</div>
		<h5 class="card-header"> <?= 'Ganancias $' . $dineroTotaljugador?> <br> + Saldo $<?= $DINEROJUGADORES[$nombreAnterior] ?> <br> TOTAL 💸💸💸: $<?= $dineroTotaljugador + $DINEROJUGADORES[$nombreAnterior]  ?> </h5>
	</div><!-- card -->

</div><!-- col -->

</div>

<?php
		unset($_SESSION['apuestasJugador']);
		if ($_SESSION['admin'])
		{
?>
			<a href="a/a.php?a=RESTART" role="button" class="btn btn-lg btn-primary btn-block mt-5">Siguiente Carrera</a>
<?php
		}
?>
