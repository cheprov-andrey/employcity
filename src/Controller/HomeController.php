<?php

namespace App\Controller;

use App\Model\NewsModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route ("/", name="index", methods={"GET"})
     */
    public function index(NewsModel $model): Response
    {
        $results = $model->getEvents();
        $content = $this->renderView('index.html.twig', ['results' => $results]);
        return new Response($content);
    }

    /**
     * @Route ("/news/{id}", name="getNews", methods={"GET"})
     */
    public function getNews(NewsModel $model, int $id): Response
    {
        $result = $model->getNewsById($id);
        $content = $this->renderView('news.html.twig', ['result' => $result]);
        return new Response($content);
    }
}
