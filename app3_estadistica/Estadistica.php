<?php
class Estadistica
{
    private array $numeros;

    public function __construct(array $numeros)
    {
        $this->numeros = $numeros;
    }

    
    public function promedio(): float
    {
        return array_sum($this->numeros) / count($this->numeros);
    }

    
    public function mediana(): float
    {
        $ordenados = $this->numeros;
        sort($ordenados);

        $total = count($ordenados);
        $mitad = intdiv($total, 2);

        if ($total % 2 === 1) {
            return $ordenados[$mitad];
        }
        return ($ordenados[$mitad - 1] + $ordenados[$mitad]) / 2;
    }

    
    public function moda(): array
    {
        $conteo = [];
        foreach ($this->numeros as $numero) {
            $clave = (string) $numero;
            if (!isset($conteo[$clave])) {
                $conteo[$clave] = 0;
            }
            $conteo[$clave]++;
        }

        $maximo = max($conteo);
        if ($maximo === 1) {
            return [];
        }

        $moda = [];
        foreach ($conteo as $valor => $veces) {
            if ($veces === $maximo) {
                $moda[] = $valor;
            }
        }
        return $moda;
    }
}