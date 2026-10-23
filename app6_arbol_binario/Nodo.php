<?php
class Nodo
{
    public string $valor;
    public ?Nodo $izquierda = null;
    public ?Nodo $derecha = null;

    public function __construct(string $valor)
    {
        $this->valor = $valor;
    }
}