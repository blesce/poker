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

			$numero = $last->getNumero(true);

			if($numero === 14) {
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
				break;
			}
		}

		if(count($fullHouse)) {
			foreach($stack as $numero => $cartas) {
				if(count($cartas) >= 2) {
					$fullHouse = array_merge($fullHouse, array_slice($cartas, 0, 2));
					return $fullHouse;
				}
			}
		}

		return false;
	}

	public function buscarGanadores() {

		$jugadores = $this->mesa->getJugadores();

		foreach($jugadores as $jugador) {
			$cartas = array_merge($this->mesa->getCartas(), $jugador->getCartas());
			list($mejores, $juego) = $this->elegirMejores($cartas);
			$jugador->setMejores($mejores);
			$jugador->setJuego($juego);
		}

		$candidatos = [];

		foreach($jugadores as $jugador) {

			if(!count($candidatos)) {
				$candidatos[] = $jugador;
				continue;
			}

			$candidato = end($candidatos);

			if($jugador->getJuego() < $candidato->getJuego()) {
				$candidatos = [$jugador];
			} elseif($jugador->getJuego() === $candidato->getJuego()) {
				$candidatos[] = $jugador;
			}
		}

		$ganadores = [];

		if(count($candidatos) === 1) {
			$ganadores = $candidatos;
		} else {

			$candidato = reset($candidatos);

			switch($candidato->getJuego()) {

				case ESCALERA_REAL:
					foreach($candidatos as $candidato) {

						if(!count($ganadores)) {
							$ganadores[] = $candidato;
							continue;
						}

						$last = end($ganadores);

						if($candidato->getMejores()[0]->getNumero(true) > $last->getMejores()[0]->getNumero(true)) {
							$ganadores = [$candidato];
						} elseif($candidato->getMejores()[0]->getNumero(true) === $last->getMejores()[0]->getNumero(true)) {
							$ganadores[] = $candidato;
						}
					}
				break;

				case POKER:
					foreach($candidatos as $candidato) {

						if(!count($ganadores)) {
							$ganadores[] = $candidato;
							continue;
						}

						$last = end($ganadores);

						if($candidato->getMejores()[0]->getNumero(true) > $last->getMejores()[0]->getNumero(true)) {
							$ganadores = [$candidato];
						} elseif($candidato->getMejores()[0]->getNumero(true) === $last->getMejores()[0]->getNumero(true)) {
							if($candidato->getMejores()[4]->getNumero(true) > $last->getMejores()[4]->getNumero(true)) {
								$ganadores = [$candidato];
							} elseif($candidato->getMejores()[4]->getNumero(true) === $last->getMejores()[4]->getNumero(true)) {
								$ganadores[] = $candidato;
							}
						}
					}
				break;

				case FULL_HOUSE:
					foreach($candidatos as $candidato) {

						if(!count($ganadores)) {
							$ganadores[] = $candidato;
							continue;
						}

						$last = end($ganadores);

						if($candidato->getMejores()[0]->getNumero(true) > $last->getMejores()[0]->getNumero(true)) {
							$ganadores = [$candidato];
						} elseif($candidato->getMejores()[0]->getNumero(true) === $last->getMejores()[0]->getNumero(true)) {
							if($candidato->getMejores()[3]->getNumero(true) > $last->getMejores()[3]->getNumero(true)) {
								$ganadores = [$candidato];
							} elseif($candidato->getMejores()[3]->getNumero(true) === $last->getMejores()[3]->getNumero(true)) {
								$ganadores[] = $candidato;
							}
						}
					}
				break;

				case COLOR:
					foreach($candidatos as $candidato) {

						if(!count($ganadores)) {
							$ganadores[] = $candidato;
							continue;
						}

						$last = end($ganadores);

						for($i = 0; $i < 5; $i++) {

							$carta1 = $candidato->getMejores()[$i]->getNumero(true);
							$carta2 = $last->getMejores()[$i]->getNumero(true);

							if($carta1 !== $carta2) {

								if($carta1 > $carta2) {
									$ganadores = [$candidato];
								}

								break;
							} elseif($i === 4) {
								$ganadores[] = $candidato;
							}
						}
					}
				break;

				case ESCALERA:
					foreach($candidatos as $candidato) {

						if(!count($ganadores)) {
							$ganadores[] = $candidato;
							continue;
						}

						$last = end($ganadores);

						$carta1 = $candidato->getMejores()[0]->getNumero(true);
						$carta2 = $last->getMejores()[0]->getNumero(true);

						if($carta1 > $carta2) {
							$ganadores = [$candidato];
						} elseif($carta1 === $carta2) {
							$ganadores[] = $candidato;
						}
					}
				break;

				case PIERNA:
					$ganadores = $candidatos;
				break;

				case PARES:
					$ganadores = $candidatos;
				break;

				case PAR:
					$ganadores = $candidatos;
				break;

				case CARTA_ALTA:
					$ganadores = $candidatos;
				break;
			}
		}

		return $ganadores;
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
				return $this->buscarPierna();
			break;

			case PARES:
				return $this->buscarPares();
			break;

			case PAR:
				return $this->buscarPar();
			break;

			case CARTA_ALTA:
				return array_slice($this->cartas, 0, 5);
			break;
		}
	}

	private function buscarPar() {

		foreach($this->cartas as $carta) {
			$stack[$carta->getNumero()][] = $carta;
		}

		foreach($stack as $numero => $cartas) {
			if(count($cartas) === 2) {
				foreach($this->cartas as $carta) {
					if($carta->getNumero() !== $numero) {
						$cartas[] = $carta;
						if(count($cartas) === 5) {
							return $cartas;
						}
					}
				}
			}
		}

		return false;
	}

	private function buscarPares() {

		$pares = [];

		foreach($this->cartas as $carta) {
			$stack[$carta->getNumero()][] = $carta;
		}

		foreach($stack as $cartas) {
			if(count($cartas) === 2) {
				$pares = array_merge($pares, $cartas);
				if(count($pares) === 4) {
					foreach($this->cartas as $carta) {
						if($carta->getNumero() !== $pares[0]->getNumero() && $carta->getNumero() !== $pares[2]->getNumero()) {
							$pares[] = $carta;
							return $pares;
						}
					}
				}
			}
		}

		return false;
	}

	private function buscarPierna() {

		foreach($this->cartas as $carta) {
			$stack[$carta->getNumero()][] = $carta;
		}

		foreach($stack as $numero => $cartas) {
			if(count($cartas) === 3) {
				foreach($this->cartas as $carta) {
					if($carta->getNumero() !== $numero) {
						$cartas[] = $carta;
						if(count($cartas) === 5) {
							return $cartas;
						}
					}
				}
			}
		}

		return false;
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

	private function elegirMejores($cartas) {

		if(count($cartas) !== 7) {
			throw new Exception('No son 7 cartas');
		}

		usort($cartas, function($a, $b) {
			if($a->getNumero(true) > $b->getNumero(true)) {
				return 1;
			} elseif($a->getNumero(true) < $b->getNumero(true)) {
				return -1;
			} elseif($a->getPalo() < $b->getPalo()) {
				return 1;
			} else {
				return -1;
			}
		});

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
