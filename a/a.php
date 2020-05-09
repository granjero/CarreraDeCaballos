<?php
session_start();
$accion = filter_input(INPUT_GET,'a');
$chrs = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//die(var_dump($_GET));

switch($accion)
{
	case 'inicia':
		$_SESSION['CDC'] = TRUE;
		$_SESSION['nombre'] = filter_input(INPUT_GET,'nombre');
		$_SESSION['idCDC'] = substr(str_shuffle($chrs), 0, 10);

		include_once('./clases/conexion.php');

		$conn = new ClaseConexion:

		$sql = NULL; //
		$sql = $conn->Open();
		$sql->select_db("estonoesunaweb_indoorMatic");
		$query = $sql->prepare('call inicia_partida(?,?)');
		$query->bind_param('ss', $_SESSION['idCDC'], $_SESSION['nombre']);
		$query->execute();
		$query->close();
		$conexion->Close();

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
