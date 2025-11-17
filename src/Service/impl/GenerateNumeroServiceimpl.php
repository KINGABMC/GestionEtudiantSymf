<?php
namespace App\Service\impl;
use App\Service\GenerateNumeroServiceInterface;

class GenerateNumeroService implements GenerateNumeroServiceInterface {
    public function generateNumerocompte(): string {
        $numero = 'COMPT-' . strtoupper(bin2hex(random_bytes(4)));
        return $numero;
    }
}