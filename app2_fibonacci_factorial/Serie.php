<?php
class Serie
{
    private int $n;

    public function __construct(int $n)
    {
        $this->n = $n;
    }

    public function fibonacci(): array
    {
        $serie = [];
        $a = 0;
        $b = 1;

        for ($i = 0; $i < $this->n; $i++) {
            $serie[] = $a;
            $siguiente = $a + $b;
            $a = $b;
            $b = $siguiente;
        }
        return $serie;
    }

    public function factorial(): array
    {
        $serie = [];
        $resultado = 1;

        for ($i = 1; $i <= $this->n; $i++) {
            $resultado = $resultado * $i;
            $serie[] = $resultado;
        }
        return $serie;
    }
}