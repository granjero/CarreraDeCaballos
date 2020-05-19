<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$idCarta = filter_input(INPUT_GET, 'idCarta');
//die(var_dump($idCarta));

if ($_SESSION['turnoActual'] != $_SESSION['miTurno'])
{
	die('ERROR de turnos');
}

include_once('../clases/conexion.php');

//var_dump($_SESSION);

// busca caracteristicas de la carta jugada
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call get_carta(?)');
//$query->bind_param('s', $idses);
$query->bind_param('i', $idCarta);
$query->execute();
$query->bind_result($id, $caballo, $posicion, $avanza, $multiplica, $maximo);
while ($query->fetch())
{
	//echo $nombre."<br>";
	$CARTA[] =
		array(
			"caballo" => $caballo
			,"posicion" => $posicion
			,"avanza" => $avanza
			,"multiplica" => $multiplica
			,"maximo" => $maximo
		);
}
$query->close();

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

// EN ESTE PUNTO TENGO EL ARRAY CARTA[0] QUE TIENE LA CARTA JUGADA
// Y TENGTO EL ARRAY CABALLO[0] QUE TIENE EL NUMERO DE CASILLA DE CADA CABALLO
// CALCULO 
arsort($CABALLO[0]);
$_SESSION['posicionesCaballosTablero'] = $CABALLO[0];


obtienePosJuego();

if($CARTA[0]['posicion'] == 0)
{
	$CABALLO[0][$CARTA[0]['caballo']] += $CARTA[0]['avanza'];
	
	// Actualiza posiciones de los caballos
	$conn = New claseConexion();
	$sql = NULL; //
	$sql = $conn->Open();
	$sql->select_db("estonoesunaweb_CDC");
	$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
	$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLO[0]['a'], $CABALLO[0]['b'], $CABALLO[0]['c'], $CABALLO[0]['d'], $idCarta);
	$query->execute();
	//$query->bind_result($turnoActual);
	//$query->fetch();
	$query->close();
}

elseif($CARTA[0]['posicion'] == 1)
{
	if($_SESSION['posicionesJuego'][$CARTA[0]['caballo']] != 1 )
	{
		die("ERROR el caballo seleccionado no está en el primer puesto");
	}
	else
	{
		foreach( $_SESSION['posicionesJuego'] as $llave => $valor )
		{
			if($valor == 2)
			{
				$caballo2puesto = $llave;
				break;
			}
		}
		
		$numeroTableroCaballo2puesto = $_SESSION['posicionesCaballosTablero'][$caballo2puesto];
		$numeroTableroCaballoCartaJugada = $_SESSION['posicionesCaballosTablero'][$CARTA[0]['caballo']];
		$nuevaPosCaballoCartaJugada = ( $numeroTableroCaballoCartaJugada - $numeroTableroCaballo2puesto ) * 2;
		
		$CABALLO[0][$CARTA[0]['caballo']] += $nuevaPosCaballoCartaJugada;
		
		// Actualiza posiciones de los caballos
		$conn = New claseConexion();
		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
		$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLO[0]['a'], $CABALLO[0]['b'], $CABALLO[0]['c'], $CABALLO[0]['d'], $idCarta);
		$query->execute();
		//$query->bind_result($turnoActual);
		//$query->fetch();
		$query->close();
	}
}


elseif($CARTA[0]['posicion'] == 2)
{
	$contadorSegundoPuesto = 0;
	foreach($_SESSION['posicionesJuego'] as $llave => $valor)
	{
		if ($valor == 2)
		{
			$contadorSegundoPuesto++;
		}
	}
	foreach($_SESSION['posicionesJuego'] as $llave => $valor)
	{
		if($valor == 2)
		{
			$caballo2puesto = $llave;
			break;
		}
	}

	if($contadorSegundoPuesto == 0)
	{
		die('No hay segundo puesto');
	}
	elseif($contadorSegundoPuesto > 1)
	{
		die('Hay mas de un segundo puesto');
	}
	else
	{
		$CABALLO[0][$caballo2puesto] += $CARTA[0]['avanza'];
		
		//die(var_dump($CARTA[0]['avanza']));

		// Actualiza posiciones de los caballos
		$conn = New claseConexion();
		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
		$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLO[0]['a'], $CABALLO[0]['b'], $CABALLO[0]['c'], $CABALLO[0]['d'], $idCarta);
		$query->execute();
		//$query->bind_result($turnoActual);
		//$query->fetch();
		$query->close();
	
	}
}


elseif($CARTA[0]['posicion'] == 3)
{
	$contadorTercerPuesto = 0;
	foreach($_SESSION['posicionesJuego'] as $llave => $valor)
	{
		if ($valor == 3)
		{
			$contadorTercerPuesto++;
		}
	}
	foreach($_SESSION['posicionesJuego'] as $llave => $valor)
	{
		if($valor == 3)
		{
			$caballo3puesto = $llave;
			break;
		}
	}

	if($contadorTercerPuesto == 0)
	{
		die('No hay tercer puesto. Volvar atrás.');
	}
	elseif($contadorTercerPuesto > 1)
	{
		die('Hay mas de un tercer puesto... Ya lo voy a solucionar. Volvar atrás.');
	}
	else
	{
		$CABALLO[0][$caballo3puesto] += $CARTA[0]['avanza'];
		
		//die(var_dump($CARTA[0]['avanza']));

		// Actualiza posiciones de los caballos
		$conn = New claseConexion();
		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
		$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLO[0]['a'], $CABALLO[0]['b'], $CABALLO[0]['c'], $CABALLO[0]['d'], $idCarta);
		$query->execute();
		//$query->bind_result($turnoActual);
		//$query->fetch();
		$query->close();
	
	}
}


elseif($CARTA[0]['posicion'] == 4)
{
	$contadorCuartoPuesto = 0;
	foreach($_SESSION['posicionesJuego'] as $llave => $valor)
	{
		if ($valor == 4)
		{
			$contadorCuartoPuesto++;
		}
	}
	foreach($_SESSION['posicionesJuego'] as $llave => $valor)
	{
		if($valor == 4)
		{
			$caballo4puesto = $llave;
			break;
		}
	}

	if($contadorCuartoPuesto == 0)
	{
		die('No hay cuarto puesto. Volver atrás.');
	}
	elseif($contadorCuartoPuesto > 1)
	{
		die('Hay mas de un cuarto puesto ya lo voy a solucionar... Volver atrás.');
	}
	else
	{
		$CABALLO[0][$caballo4puesto] += $CARTA[0]['avanza'];
		
		//die(var_dump($CARTA[0]['avanza']));

		// Actualiza posiciones de los caballos
		$conn = New claseConexion();
		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
		$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLO[0]['a'], $CABALLO[0]['b'], $CABALLO[0]['c'], $CABALLO[0]['d'], $idCarta);
		$query->execute();
		//$query->bind_result($turnoActual);
		//$query->fetch();
		$query->close();
	
	}
}

elseif($CARTA[0]['posicion'] == 99)
{
	if($CARTA[0]['caballo'] != 'x' && $_SESSION['posicionesJuego'][$CARTA['caballo']] != 1 )
	{	
		$CABALLO[0][$CARTA[0]['caballo']] += $CARTA[0]['avanza'];
		
		foreach( $_SESSION['posicionesJuego'] as $llave => $valor )
		{
			if($valor == 1)
			{
				$caballo1puesto = $llave;
				break;
			}
		}
		
		$numeroTableroCaballo1puesto = $_SESSION['posicionesCaballosTablero'][$caballo1puesto];
		
		if($CABALLO[0][$CARTA[0]['caballo']] >= ($numeroTableroCaballo1puesto - 4) ) 
		{
			$CABALLO[0][$CARTA[0]['caballo']] = $numeroTableroCaballo1puesto - 4;
		}
		
		// Actualiza posiciones de los caballos
		$conn = New claseConexion();
		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
		$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLO[0]['a'], $CABALLO[0]['b'], $CABALLO[0]['c'], $CABALLO[0]['d'], $idCarta);
		$query->execute();
		//$query->bind_result($turnoActual);
		//$query->fetch();
		$query->close();
	}
	else
	{
		die('carta max comodin o caballo en primer puesto');
	}
}





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




arsort($CABALLO[0]);
$_SESSION['posicionesCaballosTablero'] = $CABALLO[0];

obtienePosJuego();

$proximoTurno = $_SESSION['miTurno'] == count($_SESSION['listaJugadores']) ? 1 : $_SESSION['miTurno'] + 1;

// actuliza turno
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call set_proximo_turno_partida (?,?)');
$query->bind_param('si', $_SESSION['idCDC'], $proximoTurno);
$query->execute();
//$query->bind_result($turnoActual);
//$query->fetch();
$query->close();


// juega la carta, pone en el mazo_repartido la carta que se jugó para que no aparezca en el
$conn = New claseConexion();
$sql = NULL; //
$sql = $conn->Open();
$sql->select_db("estonoesunaweb_CDC");
$query = $sql->prepare('call set_carta_jugada (?,?)');
$query->bind_param('si', $_SESSION['idCDC'], $idCarta);
$query->execute();
//$query->bind_result($turnoActual);
//$query->fetch();
$query->close();
//$_SESSION['turnoActual'] = $turnoActual;
//die(var_dump($turnoActual));


//var_dump($proximoTurno);
header('Location: ../');


function obtienePosJuego()
{
	
	arsort($_SESSION['posicionesCaballosTablero']);
	$anterior = 9999;
	$contador = 0;
	$contadorExtra = 0;
	foreach($_SESSION['posicionesCaballosTablero'] as $llave => $valor)
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
		$_SESSION['posicionesJuego'][$llave] = $contador; 
		$anterior = $valor;
	}
	asort($_SESSION['posicionesJuego']);
}

?>

