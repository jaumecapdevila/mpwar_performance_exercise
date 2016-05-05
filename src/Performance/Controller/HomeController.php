<?php

namespace Performance\Controller;

use Performance\Domain\UseCase\ListArticles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    /**
     * @var \Twig_Environment
     */
    private $template;

    public function __construct(\Twig_Environment $templating, ListArticles $useCase, Request $request)
    {
        $this->template = $templating;
        $this->useCase = $useCase;
        $this->request = $request;
    }

    public function get()
    {
        $articles = $this->useCase->execute();
        $response = new Response($this->template->render('home.twig', ['articles' => $articles]));
        $response->setETag(md5($response->getContent()));
        $response->setPublic();
        $response->isNotModified($request);
        return $response;
    }
}
