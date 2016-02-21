<?php
class Mazo {

	private $cartas = [];

	public function __construct($mezclado) {

		for($i = 0; $i < 4; $i++) {
			for($j = 1; $j <= 13; $j++) {
				$this->cartas[] = new Carta($j, $i);
			}
		}

		if($mezclado) {
			$this->mezclar();
		}
	}

	public function flop() {
		return $this->repartir(3);
	}

	private function mezclar() {
		shuffle($this->cartas);
	}

	public function quemarCarta() {
		$this->repartir(1);
	}

	public function repartir($cantidad) {

		if(count($this->cartas) < $cantidad) {
			throw new Exception('Me quedé sin cartas');
		}

		$cartas = [];

		for($i = 0; $i < $cantidad; $i++) {
			$cartas[] = array_shift($this->cartas);
		}

		return $cartas;
	}

	public function river() {
		return $this->repartir(1);
	}

	public function turn() {
		return $this->repartir(1);
	}
	// public function __ver() {
	// 	for($i = 0; $i < count($this->cartas); $i++) {
	// 		echo($this->cartas[$i]->getValor() . '<br />');
	// 	}
	// }
}
?>
