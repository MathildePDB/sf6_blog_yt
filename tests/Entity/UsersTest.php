<?php

namespace App\Entity\Tests;

use App\Entity\Users;
use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    // email tests
    // valid email
    public function testGetEmail()
    {
        $user = new Users();
        $user->setEmail('test@example.com');

        $this->assertEquals('test@example.com', $user->getEmail());
    }

    // invalid email
    // public function testInvalidEmail()
    // {
    //    $user = new Users();

    //    $this->expectException(\InvalidArgumentException::class);
    //    $this->expectExceptionMessage('Le mail de test n\'est pas valide');

    //    $user->setEmail('test');
        
    // }

    // password tests
    // valid password
    public function testGetPassword()
    {
        $user = new Users();
        $user->setPassword('password');

        $this->assertEquals('password', $user->getPassword());
    }

    // too short password
    // public function tooShortPassword()
    // {
    //     $user = new Users();

    //     $this->expectException(\InvalidArgumentException::class);
    //     $this->expectExceptionMessage('Le mot de passe doit faire au moins 8 caractères');

    //     $user->setPassword('test');
    // }

    // invalid password
    // public function testInvalidPassword()
    // {
    //     $user = new Users();

    //     $this->expectException(\InvalidArgumentException::class);
    //     $this->expectExceptionMessage('Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial');

    //     $user->setPassword('test');
    // }

    // firstname test
    // valid firstname
    public function testGetFirstname()
    {
        $user = new Users();
        $user->setFirstname('John');

        $this->assertEquals('John', $user->getFirstname());
    }

    // invalid firstname
    // public function testInvalidFirstname()
    // {
    //     $user = new Users();

    //     $this->expectException(\InvalidArgumentException::class);
    //     $this->expectExceptionMessage('Le prénom doit faire au moins 3 caractères');

    //     $user->setFirstname('te');
    // }

    // lastname test
    // valid lastname
    public function testGetLastname()
    {
        $user = new Users();
        $user->setLastname('Doe');

        $this->assertEquals('Doe', $user->getLastname());
    }

    // invalid lastname
    // public function testInvalidLastname()
    // {
    //     $user = new Users();

    //     $this->expectException(\InvalidArgumentException::class);
    //     $this->expectExceptionMessage('Le nom doit faire au moins 3 caractères');

    //     $user->setLastname('te');
    // }
}
