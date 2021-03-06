<?php

namespace Performance\Domain\UseCase;


use Performance\Domain\ArticleCounterRepository;
use Performance\Domain\ArticleRepository;

class ListFiveMostViewedArticlesPerUser
{
    public function __construct
    (
        ArticleRepository $articleRepository,
        ArticleCounterRepository $articleCounterRepository
    )
    {
        $this->articleRepository = $articleRepository;
        $this->articleCounterRepository = $articleCounterRepository;
    }
    
    public function execute($user = null)
    {
        $mostViewedArticleIds = $this->articleCounterRepository->getTopFiveArticles();

        $mostViewedArticles = $this->articleRepository->getListByIds($mostViewedArticleIds);

        $orderedMostViewedArticles = [];

        $orderCounter = 0;
        foreach ($mostViewedArticleIds as $articleId) {
            $article = $this->searchArticleById($articleId, $mostViewedArticles);

            if ($article == null) break;

            $orderedMostViewedArticles[$orderCounter] = $this->searchArticleById($articleId, $mostViewedArticles);
            $orderCounter++;
        }

        return $orderedMostViewedArticles;

        
    }

    private function searchArticleById($articleId, array $articles)
    {
        foreach ($articles as $article) {
            if ($article->getId() == $articleId) return $article;
        }

        return null;
    }
    
}