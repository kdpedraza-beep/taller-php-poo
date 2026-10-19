<?php
class Historial
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['historial'])) {
            $_SESSION['historial'] = [];
        }
    }

    public function agregar(string $operacion): void
    {
        $_SESSION['historial'][] = $operacion;
    }

    public function obtener(): array
    {
        return $_SESSION['historial'];
    }

    public function borrar(): void
    {
        $_SESSION['historial'] = [];
    }
}