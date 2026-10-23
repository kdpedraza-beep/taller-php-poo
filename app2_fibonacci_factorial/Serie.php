<?php
class Serie
{
    private int $n;

    public function __construct(int $n)
    {
        $this->n = $n;
    }

    // Muestra los primeros n términos: 0, 1, 1, 2, 3, 5...
    public function fibonacci(): string
    {
        $texto = '';
        $a = 0;
        $b = 1;

        for ($i = 0; $i < $this->n; $i++) {
            if ($i > 0) {
                $texto = $texto . ', ';
            }
            $texto = $texto . $a;

            $siguiente = $a + $b;
            $a = $b;
            $b = $siguiente;
        }
        return $texto;
    }

    // Muestra los factoriales de 1 hasta n: 1, 2, 6, 24...
    public function factorial(): string
    {
        $texto = '';
        $resultado = 1;

        for ($i = 1; $i <= $this->n; $i++) {
            $resultado = $resultado * $i;

            if ($i > 1) {
                $texto = $texto . ', ';
            }
            $texto = $texto . $resultado;
        }
        return $texto;
    }
}