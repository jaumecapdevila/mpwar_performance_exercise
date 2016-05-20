<?php

namespace Performance\Domain\UseCase;

use Performance\Domain\Author;
use Performance\Domain\AuthorRepository;

class SignUp
{
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function execute($username, $password, $image)
    {
        $author = Author::register($username, $password, $image);
        $this->authorRepository->save($author);
    }
}
