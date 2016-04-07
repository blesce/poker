<?php
class Carta {

	private $numero;
	private $palo;

	public function __construct($numero, $palo) {
		$this->numero = $numero;
		$this->palo = $palo;
	}

	public function getNumero($asMayor = false) {

		if($asMayor && $this->numero === 1) {
			return 14;
		}

		return $this->numero;
	}

	public function getPalo() {
		return $this->palo;
	}

	public function nombrarNumero() {
		switch($this->numero) {

			case 1:
				return 'A';
			break;

			case 11:
				return 'J';
			break;

			case 12:
				return 'Q';
			break;

			case 13:
				return 'K';
			break;

			default:
				return $this->numero;
		}
	}

	private function nombrarPalo() {
		switch($this->palo) {

			case CORAZON:
				return 'corazón';
			break;

			case DIAMANTE:
				return 'diamante';
			break;

			case PICA:
				return 'pica';
			break;

			case TREBOL:
				return 'trebol';
			break;
		}
	}

	public function ver($abreviado = false) {
		if($abreviado) {
			echo($this->nombrarNumero() . strtoupper(substr($this->nombrarPalo(), 0, 1)));
		} else {
			echo($this->nombrarNumero() . ' de ' . $this->nombrarPalo() . '<br />');
		}
	}
}
?>
