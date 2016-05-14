<?php

namespace Performance\Controller;

use Performance\Domain\UseCase\ListFiveMostViewedArticles;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController
{
    /**
     * @var \Twig_Environment
     */
    private $template;

    public function __construct(\Twig_Environment $templating, ListFiveMostViewedArticles $useCase)
    {
        $this->template = $templating;
        $this->useCase = $useCase;

    }

    public function get(Request $request)
    {
        $this->fixETag($request);
        $articles = $this->useCase->execute();
        $response = new Response($this->template->render('home.twig', ['articles' => $articles]));
        $response->setETag(md5($response->getContent()));
        $response->setPublic(); // make sure the response is public/cacheable
        $response->isNotModified($request);
        return $response;
    }

    private function fixETag(Request $request)
    {
        $oldETag = $request->headers->get('if_none_match');
        $etagWithoutGzip = str_replace('-gzip"', '"', $oldETag);
        $request->headers->set('if_none_match', $etagWithoutGzip);
    }
}
