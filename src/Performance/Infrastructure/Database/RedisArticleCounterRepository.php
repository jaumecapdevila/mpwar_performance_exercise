<?php

namespace Performance\Infrastructure\Database;


use Performance\Domain\ArticleCounterRepository;
use Silex\Application;

class RedisArticleCounterRepository implements ArticleCounterRepository
{

    public function __construct(Application $app)
    {

    }

    public function increaseByOne($articleId)
    {
        // TODO: Implement increaseByOne() method.
    }

    public function getTopFiveArticles()
    {
        // TODO: Implement getTopFiveArticles() method.
    }

    public function getTopFiveArticlesByUser($userId)
    {
        // TODO: Implement getTopFiveArticlesByUser() method.
    }
}