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
		echo('<br />');
	}

	public function mostrarNombre($color) {
		if($color) {
			echo('<font color="#ff0000">');
		}
		echo($this->nombre . '<br />');
		if($color) {
			echo('</font>');
		}
	}

	public function recibirCartas($cartas) {
		foreach($cartas as $carta) {
			$this->cartas[] = $carta;
		}
	}
}
?>
