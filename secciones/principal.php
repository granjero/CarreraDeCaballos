
<?php

$arrayTipoApuestas = array("1" => " A Ganador -> ", "2" => " Segundo -> ", "3" => " Bifecta -> ");
$arrayColoresCaballo = array(
	"a" => " 🐢 Verde"
	, "b" => " 🐪 Amarillo"
	, "c" => " 🦕 Azul"
	, "d" => " 🐖 Rojo"
	,"ab" => " 🐢 Verde - 🐪 Amarillo"
	,"ac" => " 🐢 Verde - 🦕 Azul"
	,"ad" => " 🐢 Verde - 🐖 Rojo"
	,"ba" => " 🐪 Amarillo - 🐢 Verde"
	,"bc" => " 🐪 Amarillo - 🦕 Azul"
	,"bd" => " 🐪 Amarillo - 🐖 Rojo"
	,"ca" => " 🦕 Azul - 🐢 Verde"
	,"cb" => " 🦕 Azul - 🐪 Amarillo"
	,"cd" => " 🦕 Azul - 🐖 Rojo"
	,"da" => " 🐖 Rojo - 🐢 Verde"
	,"db" => " 🐖 Rojo - 🐪 Amarillo"
	,"dc" => " 🐖 Rojo - 🦕 Azul"
	);

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
			<a class="btn btn-block btn-success" href="a/a.php?a=cierra_y_reparte" role="button">Cierra el Ingreso <br>de Participantes</a>
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
						<?php include_once('a/listaJugadores.php'); ?>
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
					<div class="btn-group btn-block">
					  <button type="button" class="btn btn-success btn-lg btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						💰💰💰 <?= number_format($_SESSION['dinero'], 0, ",", ".") ?> 💰💰💰
					  </button>
					  <div class="dropdown-menu">
							<a class="dropdown-item" href="#">Tus apuestas: </a>
						<?php
						$contadorApuestas = 0;
						foreach($_SESSION['apuestasJugador'] as $llave => $valor)
						{
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
								Ingresá tus apuestas (Al menos una). Cuando todos cierren sus apuestas se podrá continuar con el juego...
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
										<option value="1">1er Puesto</option>
										<option value="2">2do Puesto</option>
										<option value="3">Bifecta</option>
									</select>
								</div>
								<div id="perfecta" class="col-3">
									<label for="apuestaCaballo">Animal</label>
									<select id="apuestaCaballo" name="caballo" class="form-control form-control-lg" required>
										<option selected disabled>Seleccionar...</option>
										<option class="list-group-item-success" value="a">🐢 - Verde</option>
										<option class="list-group-item-warning" value="b">🐪 - Amarillo</option>
										<option class="list-group-item-info" value="c">🦕 - Azul</option>
										<option class="list-group-item-danger" value="d">🐖 - Rojo</option>
									</select>
								<!--</div>-->
								<!--<div id="bifecta">-->
									<label for="apuestaCaballoBifecta">Segundo Puesto Bifecta</label>
									<select id="apuestaCaballoBifecta" name="caballoBifecta" class="form-control form-control-lg" disabled required  onchange="checkBifecta()">
										<option value="0" selected disabled>Seleccionar...</option>
										<option class="list-group-item-success" value="a">🐢 - Verde</option>
										<option class="list-group-item-warning" value="b">🐪 - Amarillo</option>
										<option class="list-group-item-info" value="c">🦕 - Azul</option>
										<option class="list-group-item-danger" value="d">🐖 - Rojo</option>
									</select>
								</div>
								<div class="col-3">
									<label for="monto">Monto de la Apuesta</label>
									<input id="monto" type="number" name="monto" min="100" max="<?= $_SESSION['dinero'] ?>" step=50 class="form-control form-control-lg" required>
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
								<a href="a/a.php?a=cerrarApuestas" role="button" class="btn btn-lg btn-success btn-block <?= (isset($_SESSION['apuestasJugador']) ? '' : 'disabled' ) ?> ">Comenzar el Juego</a>
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
				<div class="row py-2">
					<div class="col-12">
						<ul class="list-group">
							<li class="list-group-item list-group-item-secondary text-center">Participantes</li>
							<?php include_once('a/listaJugadores.php'); ?>
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
						<div class="btn-group btn-block">
						  <button type="button" class="btn btn-success btn-lg btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							💰💰💰 <?= number_format($_SESSION['dinero'], 0, ",", ".") ?> 💰💰💰
						  </button>
						  <div class="dropdown-menu">
								<a class="dropdown-item" href="#">Tus apuestas: </a>
							<?php
							$contadorApuestas = 0;
							foreach($_SESSION['apuestasJugador'] as $llave => $valor)
							{
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
				<div class="row">
					<div class="col-6" id="botonComenzar">
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}


elseif ($_SESSION['juegoIniciado'])
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
						<div class="btn-group btn-block">
						  <button type="button" class="btn btn-success btn-lg btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							💰💰💰 <?= number_format($_SESSION['dinero'], 0, ",", ".") ?> 💰💰💰
						  </button>
						  <div class="dropdown-menu">
								<a class="dropdown-item" href="#">Tus apuestas: </a>
							<?php
							$contadorApuestas = 0;
							foreach($_SESSION['apuestasJugador'] as $llave => $valor)
							{
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
				<div id="decideCarta" class="row">
				</div>
				<div class="row py-2">
					<?php include_once('secciones/tablero.php'); ?>
				</div>
			</div>
		</div>
	</div>
<?php
}




elseif ($_SESSION['juegoFinalizado'])
{
	?>
	<div class="container-fluid">
		<div class="row py-2">
			<div class="col-12">
				<div class="row py-2">
						<?php include_once('a/listaJugadoresApuestas.php'); ?>
				</div>
			</div>
		</div>
	</div>
<?php
}
?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
			<h3 class="modal-title" id="exampleModalLabel">Suertes...</h3>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			</div>
			<div class="modal-body">
			<p class="lead">Las suertes se utilizan para hacer avanzar los animales por la pista. <br />  El animal que primero cruza la casilla 80 gana.</p>
			<hr>
			<h5>Suertes de Color 🐢 🐪 🦕 🐖 </h5>
			<p> +7 -> El animal de ese color avanza 7 casillas. </p>
			<p>+10 -> El animal de ese color avanza 10 casillas. </p>
			<p> 1ºX3 -> El animal del color correspondiente debe ser primero. Se triplica la distancia con el caballo que está en segundo lugar. </p>
			<p>+30 mx -> El animal del color correspondiente NO puede ser primero. Avanza como máximo 30 casillas pero tiene que quedar como mínimo 4 casillas detrás del caballo que está en primer lugar. </p>
			<hr>
			<h5>Suertes Incoloras 🃏 </h5>
			<p> +18mx -> Para cualquier animal que no esté en primer lugar. Avanza como máximo 18 casillas pero no puede pasar al primero por más de una casilla. </p>
			<p> Xº+13 -> El animal que está en posición X avanza 13 casillas. </p>
			</div>
		</div>
	</div>
</div>


<?php
function jsScripts()
{
	?>
	<script>
	//En el inicio habilita iniciar partida o unirse
	var check = function() {
		//console.log('keyup');
		if ((document.getElementById('nombre').value.length >= 2))
		{
			document.getElementById('boton1').removeAttribute('disabled', '');
		} else {
			document.getElementById('boton1').setAttribute('disabled', '');
		}
		if ((document.getElementById('idCDC').value.length == 10))
		{
			document.getElementById('boton2').removeAttribute('disabled', '');
			document.getElementById('boton1').setAttribute('disabled', '');
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
	if ($_SESSION['esperandoApuestas'] || $_SESSION['apuestasCerradas'] || $_SESSION['juegoIniciado'])
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
	

	<?php
	if ($_SESSION['apuestasCerradas'])
	{
	?>	
		window.setInterval(function(){
			botonComenzar();
			console.log("botonComenzar:w")
		}, 1000);
	<?php
	}
	?>	
		function botonComenzar()
		{
			//console.log('mano');
			$.ajax({
				url: 'a/botonComenzar.php',
				success: function(respuesta)
				{
					$("#botonComenzar").html(respuesta);
				}
			});
		}



	<?php
	if ($_SESSION['juegoIniciado'])
	{
	?>	
		window.setInterval(function(){
			statusPartida();
			getTurno();
			tablero();
		}, 1000);
	<?php
	}
	?>	
		function statusPartida()
		{
			//console.log('mano');
			$.ajax({
				url: 'a/statusPartida.php',
				success: function(respuesta)
				{
					//console.log(respuesta);
					if(respuesta == 2)
					{
						cerrarPartida();
					}
				}
			});
		}

		function cerrarPartida()
		{
			//console.log('mano');
			$.ajax({
				url: 'a/a.php?a=finalizarJuego',
				success: function(respuesta)
				{
					// ACA VIENE UNA REDIRECCION A
					console.log(respuesta);
					if(respuesta == 'cerrada')
					{
						 location.reload(); 
					}
				}
			});
		}

		function getTurno()
		{
			//console.log('mano');
			$.ajax({
				url: 'a/getTurno.php',
				success: function(respuesta)
				{
					//console.log('TURNO'+respuesta);
					$("#TURNO1").removeClass("bg-danger");
					$("#TURNO2").removeClass("bg-danger");
					$("#TURNO3").removeClass("bg-danger");
					$("#TURNO4").removeClass("bg-danger");
					$("#TURNO5").removeClass("bg-danger");
					$("#TURNO6").removeClass("bg-danger");
					$("#TURNO7").removeClass("bg-danger");
					$("#TURNO8").removeClass("bg-danger");
					$("#TURNO9").removeClass("bg-danger");
					$("#TURNO"+respuesta).addClass("bg-danger");
				}
			});
		}

		function tablero()
		{
			$.ajax({
			url: 'a/actualizaTablero.php',
				dataType: 'JSON',
				success: function(respuesta)
				{
					var anterior = 9999;
					var contador = 0;
					var contadorExtra = 0;
					var POSICIONES = [];
					for(var k in respuesta) {
						//console.log(k, respuesta[k]);
						if(anterior != respuesta[k])
						{
							contador += contadorExtra;
							contador++;
							contadorExtra = 0;
						}
						else
						{
							contadorExtra++;
						}
						POSICIONES[k] = contador;
						anterior = respuesta[k];
					}
					//console.log(POSICIONES);
					//console.log("a" + respuesta.a + " - b" + respuesta.b + " - c" + respuesta.c + " - d" + respuesta.d);
					$("[id^=cas]").removeClass("border-dark");

					$("#cas-"+respuesta.a).addClass("border-dark");
					$("#cas-"+respuesta.b).addClass("border-dark");
					$("#cas-"+respuesta.c).addClass("border-dark");
					$("#cas-"+respuesta.d).addClass("border-dark");

					var $VERDE = $( "<span class='verde'>🐢</span>" );
					var $AMARILLO = $( "<span class='amarillo'>🐪</span>" );
					var $AZUL = $( "<span class='azul'>🦕</span>" );
					var $ROJO = $( "<span class='rojo'>🐖</span>" );

					$(".verde").remove();
					$(".amarillo").remove();
					$(".azul").remove();
					$(".rojo").remove();
					
					$("#cas-"+respuesta.a).append($VERDE);
					$("#cas-"+respuesta.b).append($AMARILLO);
					$("#cas-"+respuesta.c).append($AZUL);
					$("#cas-"+respuesta.d).append($ROJO);


					var POSICIONEStablero = [];
					POSICIONEStablero["a"] = "<span class='aling-middle verde'>🐢</span>";
					POSICIONEStablero["b"] = "<span class='aling-middle amarillo'>🐪</span>"
					POSICIONEStablero["c"] = "<span class='aling-middle azul'>🦕</span>"
					POSICIONEStablero["d"] = "<span class='aling-middle rojo'>🐖</span>"
					for (var j in POSICIONES)
					{
						if(POSICIONES[j] == 1)
						{
							$("#PRIMERO").append(POSICIONEStablero[j])
						}

						if(POSICIONES[j] == 2)
						{
							$("#SEGUNDO").append(POSICIONEStablero[j])
						}

						if(POSICIONES[j] == 3)
						{
							$("#TERCERO").append(POSICIONEStablero[j])
						}

						if(POSICIONES[j] == 4)
						{
							$("#CUARTO").append(POSICIONEStablero[j])
						}
					}
				}
			});
		}


	//habilita el dropdown de la segunda puest cuando se selecciona BIFECTA como apuesta
	var bifecta = function() {
		//console.log('bifecta');
		if ((document.getElementById('tipoApuesta').value == 3))
		{
			document.getElementById('apuestaCaballoBifecta').removeAttribute('disabled', '');
		} else {
			document.getElementById('apuestaCaballoBifecta').setAttribute('disabled', '');
		}
	}
	//corrobora que l puesta sea diferent
	var checkBifecta = function() {
		//console.log('valor bifecta');
		if ((document.getElementById('apuestaCaballoBifecta').value == document.getElementById('apuestaCaballo').value))
		{
			alert("Primer y Segundo puesto deben ser diferentes!!!");
			document.getElementById('apuestaCaballoBifecta').value = "0"; 
		} 
	}

	function jugarCarta(idcarta)
	{
		console.log('jugar carta'+ idcarta);
		$.ajax({
			url: 'a/jugarCarta.php?idCarta=' + idcarta,
			success: function(respuesta)
			{
				$("#decideCarta").html(respuesta);
			}
		});
	}




	<?php
	if ($_SESSION['juegoFinalizado'])
	{
	?>	
		window.setInterval(function(){
			statusPartidaFinalizada();
		}, 1000);
	<?php
	}
	?>	
		function statusPartidaFinalizada()
		{
			//console.log('mano');
			$.ajax({
				url: 'a/statusPartida.php',
				success: function(respuesta)
				{
					//console.log(respuesta);
					if(respuesta == 1)
					{
						window.location = "a/a.php?a=ver_cartas"
					}
				}
			});
		}







	</script>
	<?php
}
?>
