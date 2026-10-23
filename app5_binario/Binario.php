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
        
        if ($this->numero == 0) {
            return '0';
        }

        
        $n = $this->numero;
        if ($n < 0) {
            $n = $n * -1;
        }

        $binario = '';
        while ($n > 0) {
            $residuo = $n % 2;
            $binario = $residuo . $binario;   // el residuo nuevo va al inicio
            $n = (int) ($n / 2);              // se queda solo con la parte entera
        }

        // Si el número era negativo, se agrega el signo
        if ($this->numero < 0) {
            $binario = '-' . $binario;
        }
        return $binario;
    }
}