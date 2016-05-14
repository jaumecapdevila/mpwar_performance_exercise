<?php

namespace Performance\Infrastructure\Cache;


class ArticleCache
{
    public static function articleKey($articleId)
    {
        return $articleId."Cache";
    }

}