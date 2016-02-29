<?php
class Mesa {

	private $cartas = [];
	private $jugadores = [];

	public function __construct($cantidadDeJugadores) {

		if($cantidadDeJugadores < 2) {
			throw new Exception('Poca gente');
		} elseif($cantidadDeJugadores > 9) {
			throw new Exception('Mucha gente');
		}

		for($i = 0; $i < $cantidadDeJugadores; $i++) {
			$this->jugadores[] = new Jugador('Jugador ' . ($i + 1));
		}
	}

	public function getCartas() {
		return $this->cartas;
	}

	public function getJugadores() {
		return $this->jugadores;
	}

	public function mostrarCartas() {
		echo('Cartas comunitarias<br />');
		foreach($this->cartas as $carta) {
			$carta->ver();
		}
		echo('<br />');
	}

	public function recibirCartas($cartas) {
		foreach($cartas as $carta) {
			$this->cartas[] = $carta;
		}
	}

	public function repartir($cantidadDeCartas) {
		for($i = 0; $i < $cantidadDeCartas; $i++) {
			foreach($this->jugadores as $jugador) {
				$jugador->recibirCarta($this->mazo->repartirUna());
			}
		}
	}
}
?>
