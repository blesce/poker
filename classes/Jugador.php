<?php
class Jugador {

	private $cartas = [];
	private $nombre;

	public function __construct($nombre) {
		$this->nombre = $nombre;
	}

	public function getCartas() {
		return $this->cartas;
	}

	public function mostrarCartas() {
		foreach($this->cartas as $carta) {
			$carta->ver();
		}
	}

	public function mostrarNombre($juego) {

		$color = '#000000';

		switch($juego) {
			case ESCALERA_REAL:
				$color = '#FF0000';
			break;
			case POKER:
				$color = '#00FF00';
			break;
			case FULL_HOUSE:
				$color = '#0000FF';
			break;
			case COLOR:
				$color = '#FFFF00';
			break;
			case ESCALERA:
				$color = '#FF00FF';
			break;
			case PIERNA:
				$color = '#00FFFF';
			break;
			case PARES:
				$color = '#CCCCCC';
			break;
			case PAR:
				$color = '#CCCCCC';
			break;
			case CARTA_ALTA:
				$color = '#CCCCCC';
			break;
		}

		echo('<font color="' . $color . '">' . $this->nombre . '</font>');
	}

	public function recibirCartas($cartas) {
		foreach($cartas as $carta) {
			$this->cartas[] = $carta;
		}
	}
}
?>
