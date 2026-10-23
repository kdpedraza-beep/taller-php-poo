<?php
class Conjuntos
{
    private array $a;
    private array $b;

    public function __construct(array $a, array $b)
    {
        $this->a = $this->quitarRepetidos($a);
        $this->b = $this->quitarRepetidos($b);
    }

    // Indica si un número está dentro de una lista
    private function contiene(array $lista, int $numero): bool
    {
        foreach ($lista as $valor) {
            if ($valor == $numero) {
                return true;
            }
        }
        return false;
    }

    // Un conjunto no tiene elementos repetidos
    private function quitarRepetidos(array $lista): array
    {
        $resultado = [];
        foreach ($lista as $numero) {
            if (!$this->contiene($resultado, $numero)) {
                $resultado[] = $numero;
            }
        }
        return $resultado;
    }

    public function getA(): array
    {
        return $this->a;
    }

    public function getB(): array
    {
        return $this->b;
    }

    // Unión: todos los elementos de A y los de B que no estén en A
    public function union(): array
    {
        $resultado = $this->a;
        foreach ($this->b as $numero) {
            if (!$this->contiene($resultado, $numero)) {
                $resultado[] = $numero;
            }
        }
        return $resultado;
    }

    // Intersección: los elementos de A que también están en B
    public function interseccion(): array
    {
        $resultado = [];
        foreach ($this->a as $numero) {
            if ($this->contiene($this->b, $numero)) {
                $resultado[] = $numero;
            }
        }
        return $resultado;
    }

    // A - B: los elementos de A que no están en B
    public function aMenosB(): array
    {
        $resultado = [];
        foreach ($this->a as $numero) {
            if (!$this->contiene($this->b, $numero)) {
                $resultado[] = $numero;
            }
        }
        return $resultado;
    }

    // B - A: los elementos de B que no están en A
    public function bMenosA(): array
    {
        $resultado = [];
        foreach ($this->b as $numero) {
            if (!$this->contiene($this->a, $numero)) {
                $resultado[] = $numero;
            }
        }
        return $resultado;
    }
}