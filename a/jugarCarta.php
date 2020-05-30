<pre>
<?php
session_start();

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$idCarta = filter_input(INPUT_GET, 'idCarta');
$caballoSeleccionado = filter_input(INPUT_GET, 'caballo');
//die(var_dump($caballoSeleccionado));

// Chequea que sea el turno
if ($_SESSION['turnoActual'] != $_SESSION['miTurno'])
{
	die('ERROR -> Esto no debería pasar. Es algo de los turnos.');
}

include_once('../clases/conexion.php');

//var_dump($_SESSION);

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
		);
}
$query->close();

//die(var_dump($CARTA));

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


//var_dump($CARTA);
//var_dump($CABALLOS);
//var_dump($POSICIONES);


// SI LA CARTA ES PARA POSICIÓN 0
// ==============================
if($CARTA['posicion'] == 0)
{
	// actualiza el valor de $CABALLOS
	$CABALLOS[$CARTA['caballo']] += $CARTA['avanza'];
	
	// Actualiza posiciones de los caballos
	$conn = New claseConexion();
	$sql = NULL; //
	$sql = $conn->Open();
	$sql->select_db("estonoesunaweb_CDC");
	$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
	$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLOS['a'], $CABALLOS['b'], $CABALLOS['c'], $CABALLOS['d'], $idCarta);
	$query->execute();
	$query->close();
}

// SI LA CARTA ES PARA POSICIÓN 1
// ==============================
elseif($CARTA['posicion'] == 1)
{
	if( $POSICIONES[$CARTA['caballo']] != 1 )
	{
		die('<div class="alert alert-danger" role="alert">Carta Ilegal -> El caballo seleccionado no se encuentra en el primer puesto.</div>');
	}
	else
	{
		// saco la letra del caballo en la segunda posición
		foreach( $POSICIONES as $llave => $valor )
		{
			if( $valor == 2 )
			{
				$caballo2puesto = $llave;
				break;
			}
		}
		
		//var_dump(($CABALLOS[$CARTA['caballo']] - $CABALLOS[$caballo2puesto]) * 2);
		//die();
		
		$CABALLOS[$CARTA['caballo']] += (($CABALLOS[$CARTA['caballo']] - $CABALLOS[$caballo2puesto]) * 2);
		
		// Actualiza posiciones de los caballos
		$conn = New claseConexion();
		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
		$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLOS['a'], $CABALLOS['b'], $CABALLOS['c'], $CABALLOS['d'], $idCarta);
		$query->execute();
		$query->close();
	}
}

// SI LA CARTA ES PARA POSICIÓN 2 
// ==============================
elseif($CARTA['posicion'] == 2)
{
	// controla que no haya mas de un segundo puesto
	$contador2Puesto = 0;
	foreach($POSICIONES as $llave => $valor)
	{
		if ($valor == 2)
		{
			$contador2Puesto++;
		}
	}
	// saco la letra del caballo en la segunda posición
	foreach( $POSICIONES as $llave => $valor )
	{
		if( $valor == 2 )
		{
			$caballo2puesto = $llave;
			break;
		}
	}

	//var_dump($contador2Puesto);
	//var_dump($caballo2puesto);
	//die();

	if($contador2Puesto == 0)
	{
		die('<div class="alert alert-danger" role="alert">Carta Ilegal -> No hay animales en el segundo puesto.</div>');
		//die('ERROR -> No hay segundo puesto. VOLVER ATRÁS!');
	}
	elseif($contador2Puesto > 1)
	{
		if(!is_null($caballoSeleccionado))
		{
			$CABALLOS[$caballoSeleccionado] += $CARTA['avanza'];
			// Actualiza posiciones de los caballos
			$conn = New claseConexion();
			$sql = NULL;
			$sql = $conn->Open();
			$sql->select_db("estonoesunaweb_CDC");
			$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
			$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLOS['a'], $CABALLOS['b'], $CABALLOS['c'], $CABALLOS['d'], $idCarta);
			$query->execute();
			$query->close();
		}
		else
		{
			header("Location: ../o/?idCarta=$idCarta");
			exit;
			//die('ERROR -> Hay mas de un segundo puesto. VOLVER ATRÁS');
		}
	}
	else
	{
		$CABALLOS[$caballo2puesto] += $CARTA['avanza'];
		
		// Actualiza posiciones de los caballos
		$conn = New claseConexion();
		$sql = NULL;
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
		$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLOS['a'], $CABALLOS['b'], $CABALLOS['c'], $CABALLOS['d'], $idCarta);
		$query->execute();
		$query->close();
	}
}

// SI LA CARTA ES PARA POSICIÓN 3 
// ==============================

elseif($CARTA['posicion'] == 3)
{
	// controla que no haya mas de un 3 puesto
	$contador3Puesto = 0;
	foreach($POSICIONES as $llave => $valor)
	{
		if ($valor == 3)
		{
			$contador3Puesto++;
		}
	}
	// saco la letra del caballo en la 3 posición
	foreach( $POSICIONES as $llave => $valor )
	{
		if( $valor == 3 )
		{
			$caballo3puesto = $llave;
			break;
		}
	}

	if($contador3Puesto == 0)
	{
		die('<div class="alert alert-danger" role="alert">Carta Ilegal -> No hay animales en el tercer puesto.</div>');
		//die('ERROR -> No hay 3 puesto. VOLVER ATRÁS!');
	}
	elseif($contador3Puesto > 1)
	{
		if(!is_null($caballoSeleccionado))
		{
			$CABALLOS[$caballoSeleccionado] += $CARTA['avanza'];
			// Actualiza posiciones de los caballos
			$conn = New claseConexion();
			$sql = NULL;
			$sql = $conn->Open();
			$sql->select_db("estonoesunaweb_CDC");
			$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
			$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLOS['a'], $CABALLOS['b'], $CABALLOS['c'], $CABALLOS['d'], $idCarta);
			$query->execute();
			$query->close();
		}
		else
		{
			header("Location: ../o/?idCarta=$idCarta");
			exit;
			//die('ERROR -> Hay mas de un segundo puesto. VOLVER ATRÁS');
		}
	}
	else
	{
		$CABALLOS[$caballo3puesto] += $CARTA['avanza'];
		
		// Actualiza posiciones de los caballos
		$conn = New claseConexion();
		$sql = NULL;
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
		$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLOS['a'], $CABALLOS['b'], $CABALLOS['c'], $CABALLOS['d'], $idCarta);
		$query->execute();
		$query->close();
	}
}

// SI LA CARTA ES PARA POSICIÓN 4 
// ==============================
elseif($CARTA['posicion'] == 4)
{
	// controla que no haya mas de un 4 puesto
	$contador4Puesto = 0;
	foreach($POSICIONES as $llave => $valor)
	{
		if ($valor == 4)
		{
			$contador4Puesto++;
		}
	}
	// saco la letra del caballo en la 4 posición
	foreach( $POSICIONES as $llave => $valor )
	{
		if( $valor == 4 )
		{
			$caballo4puesto = $llave;
			break;
		}
	}

	if($contador4Puesto == 0)
	{
		die('<div class="alert alert-danger" role="alert">Carta Ilegal -> No hay animales en el cuarto puesto.</div>');
		//die('ERROR -> No hay 4 puesto. VOLVER ATRÁS!');
	}
	elseif($contador4Puesto > 1)
	{
		if(!is_null($caballoSeleccionado))
		{
			$CABALLOS[$caballoSeleccionado] += $CARTA['avanza'];
			// Actualiza posiciones de los caballos
			$conn = New claseConexion();
			$sql = NULL;
			$sql = $conn->Open();
			$sql->select_db("estonoesunaweb_CDC");
			$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
			$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLOS['a'], $CABALLOS['b'], $CABALLOS['c'], $CABALLOS['d'], $idCarta);
			$query->execute();
			$query->close();
		}
		else
		{
			header("Location: ../o/?idCarta=$idCarta");
			exit;
			//die('ERROR -> Hay mas de un segundo puesto. VOLVER ATRÁS');
		}
	}
	else
	{
		$CABALLOS[$caballo4puesto] += $CARTA['avanza'];
		
		// Actualiza posiciones de los caballos
		$conn = New claseConexion();
		$sql = NULL;
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
		$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLOS['a'], $CABALLOS['b'], $CABALLOS['c'], $CABALLOS['d'], $idCarta);
		$query->execute();
		$query->close();
	}
}

// SI LA CARTA ES PARA POSICIÓN 99 
// ===============================
elseif($CARTA['posicion'] == 99)
{

	// controla que no haya mas de un 4 puesto
	$contador4Puesto = 0;
	foreach($POSICIONES as $llave => $valor)
	{
		if ($valor == 1)
		{
			$contador1Puesto++;
		}
	}

	// para las cartas de un color en particular
	if($CARTA['caballo'] != 'x' && $POSICIONES[$CARTA['caballo']] != 1 )
	{	
		$CABALLOS[$CARTA['caballo']] += $CARTA['avanza'];

		// obtengo la letra del primer caballo
		foreach( $POSICIONES as $llave => $valor )
		{
			if($valor == 1)
			{
				$caballo1puesto = $llave;
				break;
			}
		}
		
		if($CABALLOS[$CARTA['caballo']] >= ($CABALLOS[$caballo1puesto] - 4) ) 
		{
			$CABALLOS[$CARTA['caballo']] = ($CABALLOS[$caballo1puesto] - 4);
		}
		
		// Actualiza posiciones de los caballos
		$conn = New claseConexion();
		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
		$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLOS['a'], $CABALLOS['b'], $CABALLOS['c'], $CABALLOS['d'], $idCarta);
		$query->execute();
		$query->close();
	}
	elseif($CARTA['caballo'] != 'x' && $POSICIONES[$CARTA['caballo']] == 1 )
	{
		die('<div class="alert alert-danger" role="alert">Carta Ilegal -> Animal en el primer puesto.</div>');
		//die('ERROR -> caballo en primer puesto. VOLVLER ATRÁS.');
	}
	elseif($CARTA['caballo'] == 'x' && $contador1Puesto == 4)
	{
		die('<div class="alert alert-danger" role="alert">Carta Ilegal. Todos los animales en el primer puesto.</div>');
		//die('ERROR -> caballo en primer puesto. VOLVLER ATRÁS.');
	}
	else
	{
		if(!is_null($caballoSeleccionado))
		{
			$CABALLOS[$caballoSeleccionado] += $CARTA['avanza'];
			// obtengo la letra del primer caballo
			foreach( $POSICIONES as $llave => $valor )
			{
				if($valor == 1)
				{
					$caballo1puesto = $llave;
					break;
				}
			}
			if($CABALLOS[$caballoSeleccionado] >= ($CABALLOS[$caballo1puesto] + 1) ) 
			{
				$CABALLOS[$caballoSeleccionado] = ($CABALLOS[$caballo1puesto] + 1);
			}

			// Actualiza posiciones de los caballos
			$conn = New claseConexion();
			$sql = NULL;
			$sql = $conn->Open();
			$sql->select_db("estonoesunaweb_CDC");
			$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
			$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLOS['a'], $CABALLOS['b'], $CABALLOS['c'], $CABALLOS['d'], $idCarta);
			$query->execute();
			$query->close();
		}
		else
		{
			header("Location: ../o/?idCarta=$idCarta");
			exit;
			//die('ERROR -> Hay mas de un segundo puesto. VOLVER ATRÁS');
		}
	}
}





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

//die(var_dump($CABALLOS));
$ganador = 1000;
foreach($CABALLOS as $llave =>$valor)
{
	if ($valor >= 81 && $valor <= 500)
	{
		foreach($CABALLOS as $llave2=>$valor2)
		{
			if($valor2 == 1000)
			{
				$ganador -= 250;
			}
		}
		//$CABALLO['GANADOR'] = $llave;
		$CABALLOS[$llave] = $ganador;

		// Actualiza posiciones de los caballos
		$conn = New claseConexion();
		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
		$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLOS['a'], $CABALLOS['b'], $CABALLOS['c'], $CABALLOS['d'], $idCarta);
		$query->execute();
		$query->close();
		if($ganador == 750)
		{
			//header('Location: a.php?a=finalizarJuego');
		$conn = New claseConexion();
		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call set_partida_finalizada (?)');
		$query->bind_param('s', $_SESSION['idCDC']);
		$query->execute();
		$query->close();
		}
	}

	if ($valor >= 750)
	{
		$CABALLOS[$llave] = $valor >= 1000 ? 1000 : 750;

		// Actualiza posiciones de los caballos
		$conn = New claseConexion();
		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call set_caballo_carrera(?,?,?,?,?,?)');
		$query->bind_param('siiiii', $_SESSION['idCDC'],$CABALLOS['a'], $CABALLOS['b'], $CABALLOS['c'], $CABALLOS['d'], $idCarta);
		$query->execute();
		$query->close();
		
	}

}

//var_dump($proximoTurno);

if($_GET['jugar'] == 'jugar')
{
	header('Location: ../');
}

?>

