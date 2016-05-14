<?php

namespace Performance\Controller;

use Performance\Domain\UseCase\ListFiveMostViewedArticles;
use Performance\Domain\UseCase\ListFiveMostViewedArticlesPerUser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController
{
    /**
     * @var \Twig_Environment
     */
    private $template;

    /**
     * @var ListFiveMostViewedArticles
     */
    private $useCase;


    /**
     * @var ListFiveMostViewedArticlesPerUser
     */
    private $concreteUserUseCase;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct
    (
        \Twig_Environment $templating,
        ListFiveMostViewedArticles $useCase,
        ListFiveMostViewedArticlesPerUser $concreteUserUseCase,
        SessionInterface $session
    ) {
        $this->template = $templating;
        $this->useCase = $useCase;
        $this->concreteUserUseCase = $concreteUserUseCase;
        $this->session = $session;
    }

    public function get(Request $request)
    {

        $userArticles = [];
        $articles = $this->useCase->execute();

        if (!is_null($this->session)) {
            $authorId = $this->session->get('authorId');
            $userArticles = $this->concreteUserUseCase->execute($authorId);
        }

        $response = new Response($this->template->render('home.twig', ['articles' => $articles, 'userArticles' => $userArticles]));
        $response->setETag(md5($response->getContent()));
        $response->setPublic();
        $response->isNotModified($request);
        return $response;
    }
}
