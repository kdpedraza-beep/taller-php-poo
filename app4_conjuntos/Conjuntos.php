<?php
class Conjuntos
{
    private array $a;
    private array $b;

    public function __construct(array $a, array $b)
    {
        $this->a = $this->sinRepetidos($a);
        $this->b = $this->sinRepetidos($b);
    }

    // Quita los números repetidos y ordena de menor a mayor
    private function sinRepetidos(array $lista): array
    {
        $resultado = [];
        foreach ($lista as $numero) {
            if (!in_array($numero, $resultado, true)) {
                $resultado[] = $numero;
            }
        }
        sort($resultado);
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

    // Unión: todos los elementos de A y de B
    public function union(): array
    {
        return $this->sinRepetidos(array_merge($this->a, $this->b));
    }

    // Intersección: los elementos que están en A y también en B
    public function interseccion(): array
    {
        $resultado = [];
        foreach ($this->a as $numero) {
            if (in_array($numero, $this->b, true)) {
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
            if (!in_array($numero, $this->b, true)) {
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
            if (!in_array($numero, $this->a, true)) {
                $resultado[] = $numero;
            }
        }
        return $resultado;
    }
}