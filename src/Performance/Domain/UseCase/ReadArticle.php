<?php

namespace Performance\Domain\UseCase;

use Performance\Domain\ArticleCounterRepository;
use Performance\Domain\ArticleRepository;

class ReadArticle
{
    /**
     * @var ArticleRepository
     */
	private $articleRepository;

    private $articleCounterRepository;

    public function __construct(ArticleRepository $articleRepository, ArticleCounterRepository $articleCounterRepository) {
        $this->articleRepository = $articleRepository;
        $this->articleCounterRepository = $articleCounterRepository;
    }

    public function execute($articleId, $authorId) {
        $this->articleCounterRepository->increaseByOne($authorId, $articleId);
    	return $this->articleRepository->findOneById($articleId);
    }
}