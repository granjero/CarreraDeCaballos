
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
				<li class="list-group-item list-group-item-secondary text-center">Participantes</li>
				<div id="listaJugadores"></div>
			</ul>
		<?php
			if ($_SESSION['admin'])
			{
		?>
		<div class="py-2">
			<a class="btn btn-block btn-success" href="./a/a.php?a=cierra_y_reparte" role="button">Sortea los Turnos <br> y <br> Reparte las Suertes</a>
			<!--<button id="boton3" type="submit" name="a" value="cierra_y_reparte" class="btn btn-success btn-block">Reparte las Cartas</button>-->
		</div>
		<?php
			}
		?>
		</div>
		<div class="col-10">
			<div class="row py-2">
				<div class="col-3">
					<div class="alert alert-primary" role="alert">
						Tu nombre: <?= $_SESSION['nombre'] ?> 
					</div>
				</div>
				<div class="col-3">
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

			<div class="row py-2">
				<div class="col-12">
					<ul class="list-group">
						<li class="list-group-item list-group-item-secondary text-center">Participantes</li>
						<?php include_once('./a/listaJugadores.php'); ?>
					</ul>
				</div>
			</div>


			<div id="cartasEnMano" class="row">
			</div>

		</div>
		<div class="col-10">
			<div class="row py-2">
				<div class="col-3">
					<div class="alert alert-primary" role="alert">
						Tu nombre: <?= $_SESSION['nombre'] ?> 
					</div>
				</div>
				<div class="col-3">

					<div class="btn-group">
					  <button type="button" class="btn btn-success btn-lg btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						💰💰💰 <?= number_format($_SESSION['dinero'], 0, ",", ".") ?> 💰💰💰
					  </button>
					  <div class="dropdown-menu">
							<a class="dropdown-item" href="#">Tus apuestas: </a>
						<?php
						$contadorApuestas = 0;
						foreach($_SESSION['apuestasJugador'] as $llave => $valor)
						{
							$arrayTipoApuestas = array("1" => " A Ganador", "2" => " Segundo", "3" => " Bifecta");
							$arrayColoresCaballo = array(
												"a" => " Verde"
												, "b" => " Amarillo"
												, "c" => " Azul"
												, "d" => " Rojo"
												,"ab" => " Verde Amarillo"
												,"ac" => " Verde Azul"
												,"ad" => " Verde Rojo"
												,"ba" => " Amarillo Verde"
												,"bc" => " Amarillo Azul"
												,"bd" => " Amarillo Rojo"
												,"ca" => " Azul Verde"
												,"cb" => " Azul Amarillo"
												,"cd" => " Azul Rojo"
												,"da" => " Rojo Verde"
												,"db" => " Rojo Amarillo"
												,"dc" => " Rojo Azul"
												);
							$textoApuesta = "$ ". $_SESSION['apuestasJugador'][$contadorApuestas]['monotoApuesta'];
							$textoApuesta .= $arrayTipoApuestas[$_SESSION['apuestasJugador'][$contadorApuestas]['tipoApuesta']];
							$textoApuesta .= $arrayColoresCaballo[$_SESSION['apuestasJugador'][$contadorApuestas]['caballo']] 
						?>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="#"><?= $textoApuesta ?></a>
						<?php
							$contadorApuestas++;
						}
						?>
					  </div>
					</div>

				</div>
			</div>
			<div class="row py-2">
				<div class="col-12">
					<div class="row">
						<div class="col-12">
							<div class="alert alert-info" role="alert">
								Ingresá tus apuestas...
							</div>
						</div>
					</div>
					<form action="a/apostar.php" method="POST">
						<div class="row">
							<!--<div class="form-group">-->
								<div class="col-3">
									<label for="tipoApuesta">Tipo de Apuesta</label>
									<select id="tipoApuesta" name="tipoApuesta" class="form-control form-control-lg" required onchange="bifecta()">
										<option selected disabled>Seleccionar...</option>
										<option value="1">Primer Puesto</option>
										<option value="2">Seguno Puesto</option>
										<option value="3">Bifecta</option>
									</select>
								</div>
								<div id="perfecta" class="col-3">
									<label for="apuestaCaballo">Animal</label>
									<select id="apuestaCaballo" name="caballo" class="form-control form-control-lg" required>
										<option selected disabled>Seleccionar...</option>
										<option value="a">Verde</option>
										<option value="b">Amarillo</option>
										<option value="c">Azul</option>
										<option value="d">Rojo</option>
									</select>
								<!--</div>-->
								<!--<div id="bifecta">-->
									<label for="apuestaCaballoBifecta">Segundo Puesto Bifecta</label>
									<select id="apuestaCaballoBifecta" name="caballoBifecta" class="form-control form-control-lg" disabled required  onchange="checkBifecta()">
										<option value="0" selected disabled>Seleccionar...</option>
										<option value="a">Verde</option>
										<option value="b">Amarillo</option>
										<option value="c">Azul</option>
										<option value="d">Rojo</option>
									</select>
								</div>
								<div class="col-3">
									<label for="monto">Monto de la Apuesta</label>
									<input id="monto" type="number" name="monto" step=50 class="form-control form-control-lg" required>
								</div>
							<!--</div>-->
						</div>
						<div class="row">
							<div class="col-3">
								<button id="botonApuesta" type="submit" name="apuesta" value="apuesta" class="btn btn-primary btn-lg btn-block">Apostar</button>
							</div>
						</div>
						<div class="row">
							<div class="col-3 py-2">
								<a href="a/a.php?a=cerrarApuestas" role="button" class="btn btn-lg btn-success btn-block <?= (isset($_SESSION['apuestasJugador']) ? '' : 'disabled' ) ?> ">Cerrar Apuestas</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

	<?php
}



elseif ($_SESSION['apuestasCerradas'])
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
				<div class="col-3">
					<div class="alert alert-primary" role="alert">
						Tu nombre: <?= $_SESSION['nombre'] ?> 
					</div>
				</div>
				<div class="col-3">

					<div class="btn-group">
					  <button type="button" class="btn btn-success btn-lg btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						💰💰💰 <?= number_format($_SESSION['dinero'], 0, ",", ".") ?> 💰💰💰
					  </button>
					  <div class="dropdown-menu">
							<a class="dropdown-item" href="#">Tus apuestas: </a>
						<?php
						$contadorApuestas = 0;
						foreach($_SESSION['apuestasJugador'] as $llave => $valor)
						{
							$arrayTipoApuestas = array("1" => " A Ganador", "2" => " Segundo", "3" => " Bifecta");
							$arrayColoresCaballo = array(
												"a" => " Verde"
												, "b" => " Amarillo"
												, "c" => " Azul"
												, "d" => " Rojo"
												,"ab" => " Verde Amarillo"
												,"ac" => " Verde Azul"
												,"ad" => " Verde Rojo"
												,"ba" => " Amarillo Verde"
												,"bc" => " Amarillo Azul"
												,"bd" => " Amarillo Rojo"
												,"ca" => " Azul Verde"
												,"cb" => " Azul Amarillo"
												,"cd" => " Azul Rojo"
												,"da" => " Rojo Verde"
												,"db" => " Rojo Amarillo"
												,"dc" => " Rojo Azul"
												);
							$textoApuesta = "$ ". $_SESSION['apuestasJugador'][$contadorApuestas]['monotoApuesta'];
							$textoApuesta .= $arrayTipoApuestas[$_SESSION['apuestasJugador'][$contadorApuestas]['tipoApuesta']];
							$textoApuesta .= $arrayColoresCaballo[$_SESSION['apuestasJugador'][$contadorApuestas]['caballo']] 
						?>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="#"><?= $textoApuesta ?></a>
						<?php
							$contadorApuestas++;
						}
						?>
					  </div>
					</div>

				</div>
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
			//console.log('listado');
			$.ajax({
				url: 'a/listaJugadores.php',
				success: function(respuesta)
				{
					$("#listaJugadores").html(respuesta);
				}
			});
		}


	<?php
	if ($_SESSION['esperandoApuestas'] || $_SESSION['apuestasCerradas'])
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
			//console.log('mano');
			$.ajax({
				url: 'a/cartasEnMano.php',
				success: function(respuesta)
				{
					$("#cartasEnMano").html(respuesta);
				}
			});
		}
	


	var bifecta = function() {
		//console.log('bifecta');
		if ((document.getElementById('tipoApuesta').value == 3))
		{
			document.getElementById('apuestaCaballoBifecta').removeAttribute('disabled', '');
		} else {
			document.getElementById('apuestaCaballoBifecta').setAttribute('disabled', '');
		}
	}


	var checkBifecta = function() {
		console.log('valor bifecta');
		if ((document.getElementById('apuestaCaballoBifecta').value == document.getElementById('apuestaCaballo').value))
		{
			alert("Primer y Segundo puesto deben ser diferentes!!!");
			document.getElementById('apuestaCaballoBifecta').value = "0"; 
		} 
	}

	</script>
	<?php
}
?>
