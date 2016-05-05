<?php

namespace Performance\Domain;


interface ArticleCounterRepository
{

    public function increaseByOne($articleId);

    public function getTopFiveArticles();

    public function getTopFiveArticlesByUser($userId);

}