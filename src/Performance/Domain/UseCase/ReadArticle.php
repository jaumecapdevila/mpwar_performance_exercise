<?php

namespace Performance\Domain\UseCase;

use Doctrine\Common\Cache\Cache;
use Performance\Domain\ArticleCounterRepository;
use Performance\Domain\ArticleRepository;
use Performance\Infrastructure\Cache\ArticleCache;

class ReadArticle
{
    /**
     * @var ArticleRepository
     */
	private $articleRepository;

    /**
     * @var ArticleCounterRepository
     */
    private $articleCounterRepository;

    /**
     * @var Cache
     */
    private $articleCache;

    public function __construct
    (
        ArticleRepository $articleRepository,
        ArticleCounterRepository $articleCounterRepository,
        Cache $articleCache
    ) {
        $this->articleRepository = $articleRepository;
        $this->articleCounterRepository = $articleCounterRepository;
        $this->articleCache = $articleCache;
    }

    public function execute($articleId) {

        $cacheKey = ArticleCache::articleKey($articleId);

        if($article = $this->articleCache->fetch($cacheKey)) {
            $article = unserialize($article);
            $this->articleCounterRepository->increaseByOne($article->getAuthor()->getId(), $articleId);
            return $article;
        }

        $article = $this->articleRepository->findOneById($articleId);
        $this->articleCounterRepository->increaseByOne($article->getAuthor()->getId(), $articleId);

        $this->articleCache->save($cacheKey, serialize($article));
    	
        return $article;
    }
}