<?php
class Calculadora
{
    private float $a;
    private float $b;

    public function __construct(float $a, float $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    public function sumar(): float
    {
        return $this->a + $this->b;
    }

    public function restar(): float
    {
        return $this->a - $this->b;
    }

    public function multiplicar(): float
    {
        return $this->a * $this->b;
    }

    public function dividir(): float
    {
        return $this->a / $this->b;
    }
        
    public function porcentaje(): float
    {
        return ($this->a * $this->b) / 100;
    }
}