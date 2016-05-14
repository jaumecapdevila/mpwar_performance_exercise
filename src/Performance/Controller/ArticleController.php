<?php

namespace Performance\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Performance\Domain\UseCase\ReadArticle;

class ArticleController
{
    /**
     * @var \Twig_Environment
     */
    private $template;

    /**
     * @var ReadArticle
     */
    private $useCase;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(\Twig_Environment $templating, ReadArticle $useCase, SessionInterface $session) {
        $this->template = $templating;
        $this->useCase = $useCase;
        $this->session = $session;
    }

    public function get($article_id)
    {
        $authorId = $this->session->get('author_id');

        $article = $this->useCase->execute($article_id, $authorId);

        if (!$article) {
            throw new HttpException(404, "Article $article_id does not exist.");
        }

        return new Response($this->template->render('article.twig', ['article' => $article]));
    }
}