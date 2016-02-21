<?php

define('A', 1);
define('J', 11);
define('Q', 12);
define('K', 13);

define('CORAZON', 0);
define('DIAMANTE', 1);
define('PICA', 2);
define('TREBOL', 3);

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
	$color = $dealer->buscarColor($cartas);

	$jugador->mostrarNombre($color);
	$jugador->mostrarCartas();
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
