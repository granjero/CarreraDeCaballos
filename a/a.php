<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$accion = filter_input(INPUT_GET,'a');
$chrs = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//die(var_dump($_GET));

switch($accion)
{
	case 'inicia':
		$_SESSION['CDC'] = TRUE;
		$_SESSION['nombre'] = filter_input(INPUT_GET,'nombre');
		$_SESSION['idCDC'] = substr(str_shuffle($chrs), 0, 10);
		$_SESSION['esperandoJugadores'] = TRUE;
		include_once('../clases/conexion.php');

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
			die('Ya existe ERROR');
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
			//$query->bind_result($existe)
			//$query->fetch();
			$query->close();
		}
		
		header('Location: ../');
		break;
		
	case 'unirse':
		$_SESSION['CDC'] = TRUE;
		$_SESSION['nombre'] = filter_input(INPUT_GET,'nombre');
		$_SESSION['idCDC'] = filter_input(INPUT_GET,'idCDC');
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
