<?php

namespace App\Utils;

use App\Interfaces\CrawlerAdapterInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Image;

class CrawlerAdapter implements CrawlerAdapterInterface
{
    private Crawler $crawler;
    private $childNode;

    public function __construct($content,  string $uri = null, string $baseHref = null)
    {
        $this->crawler = new Crawler($content, $uri, $baseHref);
    }

    public function filter(string $selector): CrawlerAdapter
    {
        $this->crawler = $this->crawler->filter($selector);
        return $this;
    }

    public function children(string $selector): CrawlerAdapter
    {
        $this->crawler = $this->crawler->children($selector);
        return $this;
    }

    public function each(\Closure $closure): array
    {
        $data = [];
        $nodes = [];
        $position = 0;
        while (true) {
             $node = $this->crawler->getNode($position);
            if (!$node) {
                break;
            }

            $nodes[] = $node;
            $position++;
        }

        foreach ($nodes as $i => $node) {
            $data[] = $closure($this->createSubCrawler($node), $i);
        }

        return $data;
    }

    public function isNull(string $selector)
    {
        $result = $this->crawler->filter($selector);
        return is_null($result->getNode(0));
    }

    public function childrenNode(string $selector)
    {
        return $this->getCrawler()->children($selector);
    }

    public function getImageByDivSelector(string $selector): string
    {
        if ($this->isNull($selector)) {
            return '';
        }

        return $this->crawler->filter($selector)->image()->getUri();
    }

    private function getCrawler(): Crawler
    {
        return $this->crawler;
    }

    private function createSubCrawler($nodes)
    {
        $crawlerAdapter = new static($nodes, $this->crawler->getUri(), $this->crawler->getBaseHref());
        $this->crawler = $crawlerAdapter->getCrawler();

        return $this;
    }
}
