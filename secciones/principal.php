
<div class="container-fluid">
<?php
if( !isset( $_SESSION['CDC'] ) )
{
	?>
	<form action="a/a.php" method="GET">
			<div class="row py-2">
				<div class="col-2">
					<input id="nombre" type="text" placeholder="Nombre..." name="nombre" onkeyup="check();">
				</div>
				<div class="col-2">
					<button id="boton1" type="submit" name="a" value="inicia" class="btn btn-primary" disabled>Iniciar Carrera</button>
				</div>
			</div>
			<div class="row py-2">
				<div class="col-2">
					<input id="idCDC" type="text" placeholder="ID carrera..." name="idCDC" oninput="check();">
				</div>
				<div class="col-2">
					<button id="boton2" type="submit" name="a" value="unirse" class="btn btn-primary" disabled>Unirse a una Carrera</button>
				</div>
			</div>
	</form>
	<?php
}
elseif ($_SESSION['esperandoJugadores'])
{
	?>

	<div class="row py-2">
		<div class="col-6">
			<div class="alert alert-primary" role="alert">
				Tu nombre: <?= $_SESSION['nombre'] ?> 
			</div>
		</div>
	</div>

	<div class="row py-2">
		<div class="col-6">
			<div class="alert alert-primary" role="alert">
				ID partida: <?= $_SESSION['idCDC'] ?> 
			</div>
		</div>
	</div>
	<div class="row py-2">
		<div class="col-6"></div>
	</div>
</div>

	<?php
}
function jsScripts()
{
	?>
	<script>
	var check = function() {
		console.log('keyup');
		if ((document.getElementById('nombre').value.length >= 2))
		{
			document.getElementById('boton1').removeAttribute('disabled', '');
		} else {
			document.getElementById('boton1').setAttribute('disabled', '');
		}
		if ((document.getElementById('idCDC').value.length == 10))
		{
			document.getElementById('boton2').removeAttribute('disabled', '');
		} else {
			document.getElementById('boton2').setAttribute('disabled', '');
		}
	}
	</script>
	<?php
}
?>
