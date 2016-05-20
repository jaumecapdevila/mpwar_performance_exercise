<?php

namespace Performance\Domain\UseCase;

use Performance\Domain\AuthorRepository;

class GetUserImage
{
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }
    public function execute($userId)
    {
        $author = $this->authorRepository->findOneById($userId);
        return $author->getImage();
    }
}
