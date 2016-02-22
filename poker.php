<?php
include('debug.php');

include_once('define.php');
include_once('classes/Carta.php');
include_once('classes/Dealer.php');
include_once('classes/Jugador.php');
include_once('classes/Mazo.php');
include_once('classes/Mesa.php');

$cantidadDeJugadores = 6;

try {
	$mesa = new Mesa($cantidadDeJugadores);
} catch(Exception $e) {
	exit($e->getMessage());
}

$dealer = new Dealer($mesa);

$cartasPorJugador = 2;
try {
	$dealer->repartir($cartasPorJugador);
} catch(Exception $e) {
	exit($e->getMessage());
}

$dealer->cartasComunitarias();

$mesa->mostrarCartas();

$jugadores = $mesa->getJugadores();
foreach($jugadores as $jugador) {

	$cartas = array_merge($mesa->getCartas(), $jugador->getCartas());
	list($mejores, $juego) = $dealer->elegirMejores($cartas);

	$jugador->mostrarNombre($juego);
	echo('<br />');
	$jugador->mostrarCartas();
	foreach($mejores as $carta) {
		$carta->ver(true);
		echo(' ');
	}
	echo('<br />');
	echo('<br />');
}

// $ganadores = $mesa->buscarGanadores();

// var_dump($ganadores);




// $mazo = new Mazo();

// $jugador = new Jugador();
// $jugador->recibirCarta($mazo->repartir());
// $jugador->recibirCarta($mazo->repartir());

// $cartas = $jugador->getCartas();
// for($i = 0; $i < count($cartas); $i++) {
// 	$cartas[$i]->getValor();
// }
//var_dump($cartas);
?>
