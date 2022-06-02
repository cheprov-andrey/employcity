<?php

namespace App\Model;

use App\Services\EventService;
use App\Services\ParserService;
use App\Services\SerializeService;
use App\Utils\GuzzleAdapter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParserModel
{
    private ParserService $parserService;
    private SerializeService $serializeService;
    private EventService $eventService;
    private GuzzleAdapter $guzzleAdapter;
    private ParameterBagInterface $parameterBag;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ParserService $parserService,
        SerializeService $serializeService,
        EventService $eventService,
        GuzzleAdapter $guzzleAdapter,
        ParameterBagInterface $parameterBag,
        EntityManagerInterface $entityManager
    )
    {
        $this->parserService = $parserService;
        $this->serializeService = $serializeService;
        $this->eventService = $eventService;
        $this->guzzleAdapter = $guzzleAdapter;
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
    }

    public function parseAndSaveData(string $host): string
    {
        $content = $this->parserService->getNewsContent($this->guzzleAdapter, $host);
        $arrDataHead = $this->serializeService->serializeNewsHeadContent($this->parameterBag, $content, 'rbcMenu');
        $arrData = $this->parserService->getBodyNews($this->guzzleAdapter, $arrDataHead);
        $arrDataHead = $this->serializeService->serializeBodyNewsContent($this->parameterBag, $arrData);
        return $this->eventService->addEvents($this->entityManager, $arrDataHead);
    }


}
