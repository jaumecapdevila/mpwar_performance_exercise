<?php

namespace Performance\Controller;

use Performance\Domain\UseCase\ReadArticle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    public function __construct(\Twig_Environment $templating, ReadArticle $useCase, SessionInterface $session)
    {
        $this->template = $templating;
        $this->useCase = $useCase;
        $this->session = $session;
    }

    public function get($articleId, Request $request)
    {
        $this->fixETag($request);
        $authorId = $this->session->get('authorId');

        $article = $this->useCase->execute($articleId, $authorId);

        if (!$article) {
            throw new HttpException(404, "Article $articleId does not exist.");
        }

        $response = new Response($this->template->render('article.twig', ['article' => $article]));
        $response->setETag(md5($response->getContent()));
        $response->setPublic();
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
