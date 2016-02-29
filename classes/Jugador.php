<?php
class Jugador {

	private $cartas = [];
	private $juego;
	private $mejores;
	private $nombre;

	public function __construct($nombre) {
		$this->nombre = $nombre;
	}

	public function getCartas() {
		return $this->cartas;
	}

	public function getJuego() {
		return $this->juego;
	}

	public function getMejores() {
		return $this->mejores;
	}

	public function mostrarCartas() {
		foreach($this->cartas as $carta) {
			$carta->ver();
		}
	}

	public function mostrarNombre($ganador = false) {

		$color = '#000000';

		if($ganador) {
			$color = '#ff0000';
		}

		// switch($this->juego) {
		// 	case ESCALERA_REAL:
		// 		$color = '#00ff00';
		// 	break;
		// 	case POKER:
		// 		$color = '#00cc26';
		// 	break;
		// 	case FULL_HOUSE:
		// 		$color = '#00994c';
		// 	break;
		// 	case COLOR:
		// 		$color = '#006672';
		// 	break;
		// 	case ESCALERA:
		// 		$color = '#003398';
		// 	break;
		// 	case PIERNA:
		// 		$color = '#0000bf';
		// 	break;
		// 	case PARES:
		// 		$color = '#00007f';
		// 	break;
		// 	case PAR:
		// 		$color = '#00003f';
		// 	break;
		// 	case CARTA_ALTA:
		// 		$color = '#000000';
		// 	break;
		// }

		echo('<font color="' . $color . '">' . $this->nombre . '</font> (' . $this->juego . ')');
	}

	public function recibirCartas($cartas) {
		foreach($cartas as $carta) {
			$this->cartas[] = $carta;
		}
	}

	public function setJuego($juego) {
		$this->juego = $juego;
	}

	public function setMejores($mejores) {
		$this->mejores = $mejores;
	}
}
?>
