<?php
class Binario
{
    private int $numero;

    public function __construct(int $numero)
    {
        $this->numero = $numero;
    }

    public function convertir(): string
    {
        if ($this->numero === 0) {
            return '0';
        }

        $n = abs($this->numero);
        $binario = '';

        while ($n > 0) {
            $binario = ($n % 2) . $binario;
            $n = intdiv($n, 2);
        }

        if ($this->numero < 0) {
            $binario = '-' . $binario;
        }
        return $binario;
    }
}