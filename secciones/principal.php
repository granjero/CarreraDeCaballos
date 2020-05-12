
<?php
if( !isset( $_SESSION['CDC'] ) )
{
	?>
	<div class="container-fluid">
		<form action="a/a.php" method="GET">
			<div class="row py-2">
				<div class="col-2">
					<input id="nombre" type="text" placeholder="Tu Nombre..." name="nombre" onkeyup="check();">
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
	</div>
	<?php
}
elseif ($_SESSION['esperandoJugadores'])
{
	?>

<div class="container-fluid">
	<div class="row py-2">
		<div class="col-2">
			<ul class="list-group">
				<li class="list-group-item">JUGADORES:</li>
				<div id="listaJugadores"></div>
			</ul>
		<?php
			if ($_SESSION['admin'])
			{
		?>
		<div class="py-2">
			<a class="btn btn-block btn-success" href="./a/a.php?a=cierra_y_reparte" role="button">Reparte las Cartas</a>
			<!--<button id="boton3" type="submit" name="a" value="cierra_y_reparte" class="btn btn-success btn-block">Reparte las Cartas</button>-->
		</div>
		<?php
			}
		?>
		</div>
		<div class="col-10">
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
	</div>
</div>

	<?php
}


elseif ($_SESSION['esperandoApuestas'])
{
	?>

<div class="container-fluid">
	<div class="row py-2">
		<div class="col-2">
			<ul class="list-group">
				<li class="list-group-item list-group-item-secondary text-center">Participantes</li>
				<?php include_once('./a/listaJugadores.php'); ?>
			</ul>
			<div id="cartasEnMano"></div>
		</div>
		<div class="col-10">
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


	<?php
	if ($_SESSION['esperandoJugadores'])
	{
	?>	
		window.setInterval(function(){
			listaJugadores();
		}, 1000);
	<?php
	}
	?>	
		function listaJugadores()
		{
			console.log('listado');
			$.ajax({
				url: 'a/listaJugadores.php',
				success: function(respuesta)
				{
					$("#listaJugadores").html(respuesta);
				}
			});
		}


	<?php
	if ($_SESSION['esperandoApuestas'])
	{
	?>	
		window.setInterval(function(){
			cartasEnMano();
		}, 1000);
	<?php
	}
	?>	
		function cartasEnMano()
		{
			console.log('mano');
			$.ajax({
				url: 'a/cartasEnMano.php',
				success: function(respuesta)
				{
					$("#cartasEnMano").html(respuesta);
				}
			});
		}
	</script>
	<?php
}
?>
