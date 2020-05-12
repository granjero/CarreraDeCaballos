<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$accion = filter_input(INPUT_GET,'a');
$chrs = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//die(var_dump($_GET));

include_once('../clases/conexion.php');

switch($accion)
{
	case 'inicia':
		// Inicia la partida -> SET CDC, admin, nombre, idCDC, esperandoJugadores
		$_SESSION['CDC'] = TRUE;
		$_SESSION['admin'] = TRUE;
		$_SESSION['nombre'] = filter_input(INPUT_GET,'nombre');
		$_SESSION['idCDC'] = substr(str_shuffle($chrs), 0, 10);
		$_SESSION['esperandoJugadores'] = TRUE;
		
		$conn = New claseConexion();
		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call check_partida_existe(?)');
		$query->bind_param('s', $_SESSION['idCDC']);
		$query->execute();
		$query->bind_result($existe);
		$query->fetch();
		$query->close();
		//die(var_dump($existe));
		
		if($existe)
		{
			session_destroy();
			session_unset();
			die('Ya existe ID de la partida >>> ERROR');
		}
		else
		{
			$conn = New claseConexion();
			$sql = NULL; //
			$sql = $conn->Open();
			$sql->select_db("estonoesunaweb_CDC");
			$query = $sql->prepare('call inicia_partida(?,?)');
			$query->bind_param('ss', $_SESSION['idCDC'], $_SESSION['nombre']);
			$query->execute();
			$query->close();
		}
		
		header('Location: ../');
		break;
		
	case 'unirse':
		// Jugador se une a la partida -> SET CDC, nombre, idCDC, esperandoJugadores
		$_SESSION['CDC'] = TRUE;
		$_SESSION['admin'] = FALSE;
		$_SESSION['nombre'] = filter_input(INPUT_GET,'nombre');
		$_SESSION['idCDC'] = filter_input(INPUT_GET,'idCDC');
		$_SESSION['esperandoJugadores'] = TRUE;
		
		//include_once('../clases/conexion.php');
		$conn = New claseConexion();
		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call check_nombre_jugador_existe(?,?)');
		$query->bind_param('ss', $_SESSION['idCDC'], $_SESSION['nombre']);
		$query->execute();
		$query->bind_result($jugExiste);
		$query->fetch();
		$query->close();
		//die(var_dump($jugExiste));
		
		if($jugExiste)
		{
			session_destroy();
			session_unset();
			die('Ya hay un ' . $_SESSION['nombre'] . ' en la partida >>> ERROR');
		}
		else
		{
			$conn = New claseConexion();
			$sql = NULL; //
			$sql = $conn->Open();
			$sql->select_db("estonoesunaweb_CDC");
			$query = $sql->prepare('call une_a_partida(?,?)');
			$query->bind_param('ss', $_SESSION['idCDC'], $_SESSION['nombre']);
			$query->execute();
			$query->close();
		}
		header('Location: ../');
		break;
		
	case 'cierra_y_reparte':
		// Una vez que están los jugadores se cierra a partida y se reparten las cartas.
		//include_once('../clases/conexion.php');
		$_SESSION['esperandoJugadores'] = FALSE;
		$_SESSION['esperandoApuestas'] = TRUE;
		$conn = New claseConexion();
		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_CDC");
		$query = $sql->prepare('call cierra_ingreso_jugadores (?)');
		$query->bind_param('s', $_SESSION['idCDC']);
		$query->execute();
		$query->close();
		//include_once('a/reparteCartas.php');
		
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
		header('Location: ../');
		break;

	case 'ver_cartas':
		$_SESSION['esperandoJugadores'] = FALSE;
		$_SESSION['esperandoApuestas'] = TRUE;
		header('Location: ../');
		break;


	case 'salir':
		session_unset();
		session_destroy();
		header('Location: ../');
		break;
		
	default:
		echo "NADA";
		break;
}


?>
