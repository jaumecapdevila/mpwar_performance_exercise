<?php


class ArticleCache implements Cac
{

    public function __construct()
    {
    }

    public static $allArticles = "allArticlesQuery";

    public static function userArticles($user) {
        return $user."ArticlesQuery";
    }
    
    

}