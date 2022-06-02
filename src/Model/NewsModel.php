<?php

namespace App\Model;

use App\Repository\EventRepository;
use App\Services\EventService;
use Symfony\Component\Routing\RouterInterface;

class NewsModel
{
    private EventService $eventService;
    private EventRepository $eventRepository;
    private RouterInterface $router;

    public function __construct(EventService $eventService, EventRepository $eventRepository, RouterInterface $router)
    {
        $this->eventService = $eventService;
        $this->eventRepository = $eventRepository;
        $this->router = $router;
    }

    public function getEvents(): array
    {
        $news = $this->eventService->findAll($this->eventRepository);
        return $this->eventService->prepareEvents($this->router, $news);
    }

    public function getNewsById(int $id): ?array
    {
        $event = $this->eventService->find($this->eventRepository, $id);
        if (is_null($event)) {
            throw new \Exception('undefined news');
        }

        return $this->eventService->prepareEvent($event);
    }
}
