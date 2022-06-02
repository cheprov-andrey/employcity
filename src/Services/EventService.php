<?php

namespace App\Services;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class EventService
{
    public function addEvents(EntityManagerInterface $em, array $events): string
    {
        $em->beginTransaction();
        try {
            foreach ($events as $event) {
                $eventEntity = new Event();
                $eventEntity->setTitle($event['text']);
                $eventEntity->setText($event['body']);
                $eventEntity->setImage($event['image']);
                $em->persist($eventEntity);
            }

            $em->flush();
        $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw new \Exception('invalid data');
        }

        return 'ok';
    }

    public function findAll(EventRepository $eventRepository): array
    {
        return $eventRepository->findAll();
    }

    public function prepareEvents(RouterInterface $router, array $news): array
    {
        $result = [];
        /** @var Event $event */
        foreach ($news as $event) {
            $result[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'about' => mb_strimwidth($event->getText(), 0, 200, "..."),
                'link'  => $router->generate('getNews', ['id' => $event->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
                ];
        }

        return $result;
    }

    public function find(EventRepository $repository, int $id): ?Event
    {
        return $repository->find($id);
    }

    public function prepareEvent(Event $event): array
    {
        return [
            'title' => $event->getTitle(),
            'text' => $event->getText(),
            'image' => $event->getImage()
        ];
    }
}
