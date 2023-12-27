<?php

namespace App\Services;
use SebastianBergmann\Type\VoidType;

class LinksGenerator
{

    /**
     * Guarda os links do HATEOAS
     */
    protected array $links = [];

    /**
     * Adiciona o link do HATEOAS
     */
    protected function add(string $tipo, string $url, string $rel):void
    {
        $this->links[] = [
            'type' => $tipo,
            'url' => $url,
            'rel' => $rel
        ];
    }

    /**
     * Adiciona um link do tipo get
     */
    public function get(string $url, string $rel)
    {
        $this->add('GET', $url, $rel);
    }
    
    /**
     * Adiciona um link do tipo post
     */
    public function post(string $url, string $rel)
    {
        $this->add('POST', $url, $rel);
    }
    
    /**
     * Adiciona um link do tipo put
     */
    public function put(string $url, string $rel)
    {
        $this->add('PUT', $url, $rel);
    }
    
    /**
     * Adiciona um link do tipo patch
     */
    public function patch(string $url, string $rel)
    {
        $this->add('PATCH', $url, $rel);
    }
    
    /**
     * Adiciona um link do tipo delete
     */
    public function delete(string $url, string $rel)
    {
        $this->add('DELETE', $url, $rel);
    }
    /**
     * Retorna um array com os links do hateoas
     */
    public function toArray():array
    {
        return $this->links;
    }
}
