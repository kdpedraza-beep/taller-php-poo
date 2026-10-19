<?php
require_once 'Nodo.php';

class ArbolBinario
{
    private ?Nodo $raiz = null;

    // Usa preorden e inorden si están; si no, el par de recorridos que haya
    public function construir(?array $pre, ?array $in, ?array $post): void
    {
        if ($pre !== null && $in !== null) {
            $this->raiz = $this->desdePreIn($pre, $in);
        } elseif ($post !== null && $in !== null) {
            $this->raiz = $this->desdePostIn($post, $in);
        } else {
            $this->raiz = $this->desdePrePost($pre, $post);
        }
    }

    public function getRaiz(): ?Nodo
    {
        return $this->raiz;
    }

    

    private function desdePreIn(array $pre, array $in): ?Nodo
    {
        if (count($pre) === 0) {
            return null;
        }

        // La raíz es el primer elemento del preorden
        $nodo = new Nodo($pre[0]);
        $pos = array_search($nodo->valor, $in, true);
        if ($pos === false) {
            return $nodo;
        }

        $inIzq = array_slice($in, 0, $pos);
        $inDer = array_slice($in, $pos + 1);
        $cantIzq = count($inIzq);

        $nodo->izquierda = $this->desdePreIn(array_slice($pre, 1, $cantIzq), $inIzq);
        $nodo->derecha = $this->desdePreIn(array_slice($pre, 1 + $cantIzq), $inDer);
        return $nodo;
    }

    private function desdePostIn(array $post, array $in): ?Nodo
    {
        if (count($post) === 0) {
            return null;
        }

        // La raíz es el último elemento del postorden
        $nodo = new Nodo($post[count($post) - 1]);
        $pos = array_search($nodo->valor, $in, true);
        if ($pos === false) {
            return $nodo;
        }

        $inIzq = array_slice($in, 0, $pos);
        $inDer = array_slice($in, $pos + 1);
        $cantIzq = count($inIzq);

        $nodo->izquierda = $this->desdePostIn(array_slice($post, 0, $cantIzq), $inIzq);
        $nodo->derecha = $this->desdePostIn(array_slice($post, $cantIzq, count($inDer)), $inDer);
        return $nodo;
    }

    // Supone que cada nodo tiene 0 o 2 hijos
    private function desdePrePost(array $pre, array $post): ?Nodo
    {
        if (count($pre) === 0) {
            return null;
        }

        $nodo = new Nodo($pre[0]);
        if (count($pre) === 1) {
            return $nodo;
        }

        // La raíz del lado izquierdo es el segundo elemento del preorden
        $pos = array_search($pre[1], $post, true);
        if ($pos === false) {
            return $nodo;
        }
        $cantIzq = $pos + 1;

        $nodo->izquierda = $this->desdePrePost(
            array_slice($pre, 1, $cantIzq),
            array_slice($post, 0, $cantIzq)
        );
        $nodo->derecha = $this->desdePrePost(
            array_slice($pre, 1 + $cantIzq),
            array_slice($post, $cantIzq, count($post) - 1 - $cantIzq)
        );
        return $nodo;
    }



    public function preorden(): array
    {
        return $this->recorrerPre($this->raiz);
    }

    public function inorden(): array
    {
        return $this->recorrerIn($this->raiz);
    }

    public function postorden(): array
    {
        return $this->recorrerPost($this->raiz);
    }

    private function recorrerPre(?Nodo $nodo): array
    {
        if ($nodo === null) {
            return [];
        }
        return array_merge([$nodo->valor], $this->recorrerPre($nodo->izquierda), $this->recorrerPre($nodo->derecha));
    }

    private function recorrerIn(?Nodo $nodo): array
    {
        if ($nodo === null) {
            return [];
        }
        return array_merge($this->recorrerIn($nodo->izquierda), [$nodo->valor], $this->recorrerIn($nodo->derecha));
    }

    private function recorrerPost(?Nodo $nodo): array
    {
        if ($nodo === null) {
            return [];
        }
        return array_merge($this->recorrerPost($nodo->izquierda), $this->recorrerPost($nodo->derecha), [$nodo->valor]);
    }
}