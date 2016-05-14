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
     * @param $articleId
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
            ->from("Performance\\Domain\\Article", "a")
            ->where($qb->expr()->eq("a.".self::ARTICLE_ID_KEY, $articleIds[0]));

        array_shift($articleIds);

        foreach ($articleIds as $articleId) {
            $qb->orWhere($qb->expr()->eq("a.".self::ARTICLE_ID_KEY, $articleId));
        }

        return $qb->getQuery()->getResult();

    }
}