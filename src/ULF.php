<?php


namespace ULF\Core;

class ULF
{

    public array $config;
    public array $routes;

    public function run(){

        $this->config = (new KeyBuilder('../config'))->build();
        $this->routes = route();

    }

    public function config(){

        return $this->config;
    }

    public function routes(){

        return $this->routes;

    }

}