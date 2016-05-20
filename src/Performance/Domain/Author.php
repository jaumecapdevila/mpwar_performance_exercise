<?php

namespace Performance\Domain;

class Author
{
    private $id;
    private $username;
    private $password;
    private $image;

    public static function register($username, $password, $image)
    {
        $author = new Author();
        $author->username = $username;
        $author->password = password_hash($password, PASSWORD_DEFAULT);
        $author->image = $image;

        return $author;
    }

    public static function fromArray($authorArray)
    {
        $author = new Author();
        $author->id = $authorArray['id'];
        $author->username = $authorArray['username'];
        $author->password = $authorArray['password'];
        $author->image = $authorArray['image'];

        return $author;
    }

    public function verifyPassword($plainTextPassword)
    {
        return password_verify($plainTextPassword, $this->password);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getImage()
    {
        return $this->image;
    }
}
