<?php

namespace App\Utils\RbcSerializer;

use App\Utils\CrawlerAdapter;
use App\Utils\RBCSerializerFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RbcSerializer extends RBCSerializerFactory
{
    private array $config;
    private array $content;

    public function __construct(ParameterBagInterface $parameterBag, array $content, string $type)
    {
        if (!$parameterBag->has($type)) {
            throw new \Exception('undefined type parser');
        }

        $this->config = $parameterBag->get($type);
        $this->content = $content;
    }

    public function serializeContent(): array
    {
        $crawler = new CrawlerAdapter($this->content['body']);
        $content['image'] = $crawler->getImageByDivSelector($this->config['body']['image']);
        $convertedBody = $crawler->filter($this->config['body']['mainFilter'])->each(function (CrawlerAdapter $crawlerAdapter){
            $resultPost = '';
            $nodes = null;
            foreach ($this->config['body']['postBody'] as $postBody) {
                if (!$crawlerAdapter->isNull($postBody)) {
                    $nodes = $crawlerAdapter->filter($postBody);
                    break;
                }
            }

            foreach ($nodes->childrenNode($this->config['body']['postSelector']) as $post) {
                $resultPost .= $post->textContent;
            }

            return $resultPost;
        });

        if (!array_key_exists(0, $convertedBody)) {
            $test = 0;
        }

        $content['body'] = $convertedBody[0];
        return $content;
    }

    public function serializeHeadNews(): array
    {
        $crawler = new CrawlerAdapter($this->content[0]);
        return $crawler
            ->filter($this->config['body']['mainFilter'])
            ->each(function (CrawlerAdapter $crawlerAdapter, $i = 0) {
                $result = [];
                foreach ($crawlerAdapter->childrenNode($this->config['body']['postSelector']) as $link) {
                    $targetLink = $link->attributes['href']->value;
                    $needContinue = false;
                    foreach ($this->config['body']['excludeBySubString'] as $exclude) {
                        if (strpos($targetLink, $exclude) !== false) {
                            $needContinue = true;
                        }
                    }
                    if($needContinue) {
                        continue;
                    }

                    $result[$i]['href'] = $targetLink;
                    $result[$i]['text'] = trim($link->childNodes[1]->childNodes[1]->childNodes[1]->nodeValue);
                    $i++;
                    if ($i > 16) {
                        break;
                    }
                }

                return $result;
            })[0];
    }
}
