<?php

namespace App\Entity\Tests;

use App\Entity\Contacts;
use PHPUnit\Framework\TestCase;

class ContactsTest extends TestCase
{
    public function testGetFirstname()
    {
        $contact = new Contacts();
        $contact->setFirstname('Test');

        $this->assertEquals('Test', $contact->getFirstname());
    }

    public function testGetLastname()
    {
        $contact = new Contacts();
        $contact->setLastname('Test');

        $this->assertEquals('Test', $contact->getLastname());
    }

    public function testGetContactEmail()
    {
        $contact = new Contacts();
        $contact->setEmail('test@mail.com');

        $this->assertEquals('test@mail.com', $contact->getEmail());
    }

    public function testGetSubject()
    {
        $contact = new Contacts();
        $contact->setSubject('test');

        $this->assertEquals('test', $contact->getSubject());
    }

    public function testGetContent()
    {
        $contact = new Contacts();
        $contact->setContent('First test');

        $this->assertEquals('First test', $contact->getContent());
    }
}
