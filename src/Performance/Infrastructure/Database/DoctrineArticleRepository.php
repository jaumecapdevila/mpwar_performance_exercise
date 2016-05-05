<?php

namespace Performance\Infrastructure\Database;

use Doctrine\ORM\EntityRepository;
use Performance\Domain\Article;
use Performance\Domain\ArticleRepository;

class DoctrineArticleRepository extends EntityRepository implements ArticleRepository
{

    const ARTICLE_ID_KEY = "id";

    public function save(Article $article)
    {
        $this->_em->persist($article);
        $this->_em->flush();
    }

    /**
     * @param $article_id
     * @return null|Article
     */
    public function findOneById($articleId)
    {
        return parent::findOneById($articleId);
    }

    public function getListByIds(array $articleIds)
    {

        if (empty($articleIds)) return [];

        $qb = $this->_em->createQueryBuilder();
        $qb->select("a")
            ->from("a", "Performance\\Domain\\Article")
            ->where($qb->expr()->eq(self::ARTICLE_ID_KEY, $articleIds[0]));

        for ($i = 1; $i <= 5; $i++) {
            $qb->orWhere($qb->expr()->eq(self::ARTICLE_ID_KEY, $articleIds[$i]));
        }

        return $qb->getResult();

    }
}