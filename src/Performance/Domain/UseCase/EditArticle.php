<?php

namespace Performance\Domain\UseCase;

use Doctrine\Common\Cache\Cache;
use Performance\Domain\ArticleRepository;
use Performance\Domain\AuthorRepository;
use Performance\Domain\Exception\Forbidden;
use Performance\Infrastructure\Cache\ArticleCache;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EditArticle
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

    /**
     * @var Cache
     */
    private $articleCache;

    public function __construct(
        ArticleRepository $articleRepository,
        AuthorRepository $authorRepository,
        SessionInterface $session,
        Cache $articleCache
    )
    {
        $this->articleRepository = $articleRepository;
        $this->authorRepository = $authorRepository;
        $this->session = $session;
        $this->articleCache = $articleCache;
    }

    public function execute($articleId, $title, $content)
    {
        $author = $this->getAuthor();
        $article = $this->articleRepository->findOneById($articleId);
        $article->edit($title, $content, $author);
        $this->articleRepository->save($article);
        $this->articleCache->delete(ArticleCache::articleKey($articleId));
        return $article;
    }

    private function getAuthor()
    {
        $authorId = $this->session->get('authorId');

        if (!$authorId) {
            throw new Forbidden('You must be logged in in order to write an article');
        }

        return $this->authorRepository->findOneById($authorId);
    }
}