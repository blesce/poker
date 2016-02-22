<?php
class Dealer {

	private $cartas;
	private $mazo;
	private $mesa;

	public function __construct($mesa) {
		$this->mazo = new Mazo(true);
		$this->mesa = $mesa;
	}

	private function buscarColor() {

		foreach($this->cartas as $carta) {
			$stack[$carta->getPalo()][] = $carta;
		}

		foreach($stack as $palo => $cartas) {
			if(count($cartas) >= 5) {
				return array_slice($cartas, 0, 5);
			}
		}

		return false;
	}

	private function buscarJuego($juego) {
		switch($juego) {

			case ESCALERA_REAL:
				// return $this->buscarEscaleraReal();
				return false;
			break;

			case POKER:
				// return $this->buscarPoker();
				return false;
			break;

			case FULL_HOUSE:
				// return $this->buscarFullHouse();
				return false;
			break;

			case COLOR:
				return $this->buscarColor();
			break;

			case ESCALERA:
				return $this->buscarEscalera();
			break;

			case PIERNA:
				// return $this->buscarPierna();
				return false;
			break;

			case PARES:
				// return $this->buscarPares();
				return false;
			break;

			case PAR:
				// return $this->buscarPar();
				return false;
			break;

			case CARTA_ALTA:
				// return $this->buscarCartaAlta();
				return false;
			break;

			default: // Quitar
				return array_slice($this->cartas, 0, 5);
		}
	}

	private function buscarEscalera() {

		$cartas = [];

		foreach($this->cartas as $carta) {

			if(!count($cartas)) {
				$cartas[] = $carta;
				continue;
			}

			$last = end($cartas);

			if($carta->getNumero() === $last->getNumero()) {
				continue;
			}

			$numero = $last->getNumero();

			if($numero === 1) {
				$numero = 14;
				$as = $last;
			}

			if($carta->getNumero() === $numero - 1) {

				$cartas[] = $carta;

				if(count($cartas) === 5) {
					return $cartas;
				} elseif(count($cartas) === 4) {

					$first = reset($cartas);

					if($first->getNumero() === 5 && isset($as)) {
						$cartas[] = $as;
						return $cartas;
					}
				}
			} else {
				$cartas = [$carta];
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

	public function elegirMejores($cartas) {

		if(count($cartas) !== 7) {
			throw new Exception('No son 7 cartas');
		}

		usort($cartas, function($a, $b) {
			if($a->getNumero() > $b->getNumero()) {
				return 1;
			} elseif($a->getNumero() < $b->getNumero()) {
				return -1;
			} elseif($a->getPalo() < $b->getPalo()) {
				return 1;
			} else {
				return -1;
			}
		});

		while(reset($cartas)->getNumero() === 1) {
			$cartas[] = array_shift($cartas);
		}

		$this->cartas = array_reverse($cartas);

		$mejores = false;
		while(!$mejores) {

			if(!isset($juego)) {
				$juego = ESCALERA_REAL;
			}

			$mejores = $this->buscarJuego($juego++);
		}

		return [$mejores, --$juego];
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
