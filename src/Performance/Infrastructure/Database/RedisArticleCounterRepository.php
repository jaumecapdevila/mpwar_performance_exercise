<?php

namespace Performance\Infrastructure\Database;


use Performance\Domain\ArticleCounterRepository;
use Silex\Application;

class RedisArticleCounterRepository implements ArticleCounterRepository
{

    const ARTICLES_KEY = "articles";

    /**
     * @var \Predis\Client
     */
    private $client;

    public function __construct(\Predis\Client $client)
    {
        $this->client = $client;
    }

    public function increaseByOne($user, $articleId)
    {
        $this->client->zincrby($this->ARTICLES_BY_USER_KEY($user), 1, $articleId);
        $this->client->zincrby(self::ARTICLES_KEY, 1, $articleId);
    }

    public function getTopFiveArticles()
    {
        $this->client->zrevrange(self::ARTICLES_KEY, 0, 5);
    }

    public function getTopFiveArticlesByUser($user)
    {
        $this->client->zrevrange(self::ARTICLES_BY_USER_KEY($user), 0, 5);

    }

    private function ARTICLES_BY_USER_KEY($user) {
        return $user . self::ARTICLES_KEY;
    }
}