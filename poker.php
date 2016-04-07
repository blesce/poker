<?php
include('debug.php');

include_once('define.php');
include_once('classes/Carta.php');
include_once('classes/Dealer.php');
include_once('classes/Jugador.php');
include_once('classes/Mazo.php');
include_once('classes/Mesa.php');


$stats = ['A' => 0];
for($i = 0; $i < 10000; $i++) {

	$mazo = new Mazo(true);
	$mazo->dameCarta(new Carta(A, PICA));
	$mazo->dameCarta(new Carta(A, DIAMANTE));
	$cartas = $mazo->flop();

	$letras = [];
	foreach($cartas as $carta) {
		$letras[] = $carta->nombrarNumero();
	}

	if(in_array('A', $letras)) {
		$stats['A']++;
	}

	// if(in_array('K', $letras) && !in_array('A', $letras)) {
	// 	$stats['K']++;
	// }
}
ver($stats);
exit();


ob_start();
while(true) {

	ob_clean();

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

	$ganadores = $dealer->buscarGanadores();

	$jugadores = $mesa->getJugadores();
	foreach($jugadores as $jugador) {
		$jugador->mostrarNombre(in_array($jugador, $ganadores));
		echo('<br />');
		$jugador->mostrarCartas();
		echo('<br />');
		// foreach($mejores as $carta) {
		// 	$carta->ver(true);
		// 	echo(' ');
		// }
		// echo('<br />');
		// echo('<br />');
	}

	// Hack para debug
	$last = end($ganadores);
	if($last->getJuego() === 4) {
		$mismoJuego = 0;
		foreach($jugadores as $jugador) {
			if($jugador->getJuego() === 4) {
				$mismoJuego++;
				if($mismoJuego === 4 && count($ganadores) === 2) {
					ob_end_flush();
					exit();
				}
			}
		}
	}
}



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
