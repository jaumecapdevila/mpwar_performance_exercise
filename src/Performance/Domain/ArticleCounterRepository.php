<?php

namespace Performance\Domain;


interface ArticleCounterRepository
{

    public function increaseByOne($user, $articleId);

    public function getTopFiveArticles();

    public function getTopFiveArticlesByUser($user);

}