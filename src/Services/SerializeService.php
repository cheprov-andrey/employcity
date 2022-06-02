<?php

namespace App\Services;

use App\Utils\RbcSerializer\RbcSerializer;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SerializeService
{
    /**
     * @throws Exception
     */
    public function serializeNewsHeadContent(ParameterBagInterface $parameterBag, string $content, string $type): array
    {
        $serializer = new RbcSerializer($parameterBag, [$content], $type);
        return $serializer->serializeHeadNews();
    }

    /**
     * @throws Exception
     */
    public function serializeBodyNewsContent(ParameterBagInterface $parameterBag, array $content): array
    {
        foreach ($content as $key => $bodyContent) {
            $arrHost = parse_url($bodyContent['href']);
            $arrDomain = explode('.', $arrHost['host']);
            if ($arrDomain[0] === 'www') {
                $type = $arrDomain[1];
            } else {
                $type = $arrDomain[0];
            }

            $result = new RbcSerializer($parameterBag, $bodyContent, $type);
            $content[$key] = array_merge($content[$key], $result->serializeContent());
        }

        return $content;
    }
}
