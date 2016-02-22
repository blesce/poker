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

	private function buscarEscalera($cartas = false) {

		if(!$cartas) {
			$cartas = $this->cartas;
		}

		$escalera = [];

		foreach($cartas as $carta) {

			if(!count($escalera)) {
				$escalera[] = $carta;
				continue;
			}

			$last = end($escalera);

			if($carta->getNumero() === $last->getNumero()) {
				continue;
			}

			$numero = $last->getNumero();

			if($numero === 1) {
				$numero = 14;
				$as = $last;
			}

			if($carta->getNumero() === $numero - 1) {

				$escalera[] = $carta;

				if(count($escalera) === 5) {
					return $escalera;
				} elseif(count($escalera) === 4) {

					$first = reset($escalera);

					if($first->getNumero() === 5 && isset($as)) {
						$escalera[] = $as;
						return $escalera;
					}
				}
			} else {
				$escalera = [$carta];
			}
		}

		return false;
	}

	private function buscarEscaleraReal() {

		foreach($this->cartas as $carta) {
			$stack[$carta->getPalo()][] = $carta;
		}

		foreach($stack as $palo => $cartas) {
			if(count($cartas) >= 5) {
				$escaleraReal = $this->buscarEscalera($cartas);
				if($escaleraReal) {
					return $escaleraReal;
				}
			}
		}

		return false;
	}

	private function buscarFullHouse() {

		$fullHouse = [];

		foreach($this->cartas as $carta) {
			$stack[$carta->getNumero()][] = $carta;
		}

		foreach($stack as $numero => $cartas) {
			if(count($cartas) === 3) {
				$fullHouse = $cartas;
			}
		}

		if(count($fullHouse)) {
			foreach($stack as $numero => $cartas) {
				if(count($cartas) === 2) {
					$fullHouse = array_merge($fullHouse, $cartas);
					return $fullHouse;
				}
			}
		}

		return false;
	}

	private function buscarJuego($juego) {
		switch($juego) {

			case ESCALERA_REAL:
				return $this->buscarEscaleraReal();
			break;

			case POKER:
				return $this->buscarPoker();
			break;

			case FULL_HOUSE:
				return $this->buscarFullHouse();
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
				return array_slice($this->cartas, 0, 5);
			break;
		}
	}

	private function buscarPoker() {

		foreach($this->cartas as $carta) {
			$stack[$carta->getNumero()][] = $carta;
		}

		foreach($stack as $numero => $cartas) {
			if(count($cartas) === 4) {
				foreach($this->cartas as $carta) {
					if($carta->getNumero() !== $numero) {
						$cartas[] = $carta;
						return $cartas;
					}
				}
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
