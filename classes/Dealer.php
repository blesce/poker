<?php
class Dealer {

	private $mazo;
	private $mesa;

	public function __construct($mesa) {
		$this->mazo = new Mazo(true);
		$this->mesa = $mesa;
	}

	public function buscarColor($cartas) {

		$this->contarCartas($cartas, 7);

		foreach($cartas as $carta) {
			$stack[$carta->getPalo()][] = $carta;
		}

		foreach($stack as $palo => $cartas) {
			if(count($cartas) >= 5) {
				usort($cartas, function($a, $b) {
					return ($a->getNumero() > $b->getNumero()) ? 1 : -1;
				});
				if(reset($cartas)->getNumero() === 1) {
					$cartas[] = array_shift($cartas);
				}
				return array_reverse($cartas);
			}
		}

		return false;
	}

	public function buscarEscalera($cartas) {

		$this->contarCartas($cartas, 7);

		// Sin implementar
	}

	public function cartasComunitarias() {

		$this->mazo->quemarCarta();
		$this->mesa->recibirCartas($this->mazo->flop());

		$this->mazo->quemarCarta();
		$this->mesa->recibirCartas($this->mazo->turn());

		$this->mazo->quemarCarta();
		$this->mesa->recibirCartas($this->mazo->river());
	}

	private function contarCartas($cartas, $cantidad) {
		if(count($cartas) === $cantidad) {
			return true;
		}
		throw new Exception('No son ' . $cantidad . ' cartas');
	}

	public function repartir($cartasPorJugador) {
		for($i = 0; $i < $cartasPorJugador; $i++) {
			foreach($this->mesa->getJugadores() as $jugador) {
				$jugador->recibirCartas($this->mazo->repartir(1));
			}
		}
	}
}
?>
