<?php
class Dealer {

	private $mazo;
	private $mesa;

	public function __construct($mesa) {
		$this->mazo = new Mazo(true);
		$this->mesa = $mesa;
	}

	public function buscarColor($cartas) {

		if(count($cartas) !== 7) {
			throw new Exception('No son 7 cartas');
		}

		foreach($cartas as $carta) {
			$stack[$carta->getPalo()][] = $carta;
		}

		foreach($stack as $palo => $cartas) {
			if(count($cartas) >= 5) {
				// rsort($stack[$palo]);
				// return reset(array_chunk($stack, 5));
				return true;
			}
		}

		return false;
	}

	public function cartasComunitarias() {

		$this->mazo->quemarCarta();
		$this->mesa->recibirCartas($this->mazo->flop());

		$this->mazo->quemarCarta();
		$this->mesa->recibirCartas($this->mazo->turn());

		$this->mazo->quemarCarta();
		$this->mesa->recibirCartas($this->mazo->river());
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
