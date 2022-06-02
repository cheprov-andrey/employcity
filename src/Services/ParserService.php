<?php

namespace App\Services;

use App\Utils\GuzzleAdapter;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ParserService
{
    public function getNewsContent(GuzzleAdapter $client, string $url): string
    {
        $response = $client->request('GET', $url);
        if ($response->getStatusCode() !== Response::HTTP_OK) {
              throw new Exception('Request execution error');
        }

        return $response->getContent();
    }

    public function getBodyNews(GuzzleAdapter $client, array $headNews): array
    {
        foreach ($headNews as $key => $news) {
            $headNews[$key]['body'] = $this->getNewsContent($client, $news['href']);
        }

       return $headNews;
    }
}
