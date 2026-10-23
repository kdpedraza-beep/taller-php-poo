<?php
class Estadistica
{
    private array $numeros;

    public function __construct(array $numeros)
    {
        $this->numeros = $numeros;
    }

    // Promedio
    public function promedio(): float
    {
        $suma = 0;
        foreach ($this->numeros as $numero) {
            $suma = $suma + $numero;
        }
        return $suma / count($this->numeros);
    }

    // Mediana: el valor del centro de los números ordenados
    public function mediana(): float
    {
        // Se ordena una copia de los números (método de la burbuja)
        $orden = $this->numeros;
        $total = count($orden);

        for ($i = 0; $i < $total - 1; $i++) {
            for ($j = 0; $j < $total - 1 - $i; $j++) {
                if ($orden[$j] > $orden[$j + 1]) {
                    $auxiliar = $orden[$j];
                    $orden[$j] = $orden[$j + 1];
                    $orden[$j + 1] = $auxiliar;
                }
            }
        }

        $mitad = (int) ($total / 2);
        if ($total % 2 == 1) {
            return $orden[$mitad];
        }
        return ($orden[$mitad - 1] + $orden[$mitad]) / 2;
    }

    // Moda:
    public function moda(): array
    {
        $total = count($this->numeros);
        $mayor = 0;

        // 1) Se busca cuántas veces se repite el que más se repite
        for ($i = 0; $i < $total; $i++) {
            $veces = 0;
            for ($j = 0; $j < $total; $j++) {
                if ($this->numeros[$i] == $this->numeros[$j]) {
                    $veces++;
                }
            }
            if ($veces > $mayor) {
                $mayor = $veces;
            }
        }

        // Si ninguno se repite, no hay moda
        if ($mayor == 1) {
            return [];
        }

        //  Se guardan los números que se repiten esa cantidad de veces, sin duplicarlos
        $moda = [];
        for ($i = 0; $i < $total; $i++) {
            $veces = 0;
            for ($j = 0; $j < $total; $j++) {
                if ($this->numeros[$i] == $this->numeros[$j]) {
                    $veces++;
                }
            }

            if ($veces == $mayor) {
                $yaEsta = false;
                foreach ($moda as $valor) {
                    if ($valor == $this->numeros[$i]) {
                        $yaEsta = true;
                    }
                }
                if (!$yaEsta) {
                    $moda[] = $this->numeros[$i];
                }
            }
        }
        return $moda;
    }
}