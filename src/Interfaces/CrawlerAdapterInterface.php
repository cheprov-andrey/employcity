<?php

namespace App\Interfaces;

use App\Utils\CrawlerAdapter;

interface CrawlerAdapterInterface
{
    public function filter(string $selector): CrawlerAdapter;
    public function children(string $selector): CrawlerAdapter;
    public function each(\Closure $closure): array;
    public function childrenNode(string $selector);
}
