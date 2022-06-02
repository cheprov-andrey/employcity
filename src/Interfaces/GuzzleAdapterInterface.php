<?php

namespace App\Interfaces;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface GuzzleAdapterInterface
{
    public function request(string $method, string $url, array $options = []): ResponseInterface;
    public function getStatusCode(ResponseInterface $response): int;
    public function getContent(ResponseInterface $response): string;
}
