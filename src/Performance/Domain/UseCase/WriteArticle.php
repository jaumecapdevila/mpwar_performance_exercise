<?php

namespace Performance\Domain\UseCase;

use Doctrine\Common\Cache\Cache;
use Performance\Domain\ArticleRepository;
use Performance\Domain\Article;
use Performance\Domain\AuthorRepository;
use Performance\Domain\Exception\Forbidden;
use Performance\Infrastructure\Cache\ArticleCache;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class WriteArticle
{
    /**
     * @var ArticleRepository
     */
	private $articleRepository;

    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    /**
     * @var SessionInterface
     */
	private $session;

    public function __construct
    (
        ArticleRepository $articleRepository,
        AuthorRepository $authorRepository,
        SessionInterface $session
    ) {
        $this->articleRepository = $articleRepository;
        $this->authorRepository = $authorRepository;
        $this->session = $session;
    }

    public function execute($title, $content) {
        $author = $this->getAuthor();
        $article = Article::write($title, $content, $author);
        $this->articleRepository->save($article);

        return $article;
    }

    private function getAuthor() {
        $authorId = $this->session->get('authorId');

        if (!$authorId) {
            throw new Forbidden('You must be logged in in order to write an article');
        }

        return $this->authorRepository->findOneById($authorId);
    }
}