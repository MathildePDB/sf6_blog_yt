<?php

namespace App\Entity\Tests;

use App\Entity\Comments;
use PHPUnit\Framework\TestCase;

class CommentsTest extends TestCase
{
    public function testGetRanking()
    {
        $comment = new Comments();
        $comment->setRanking('2');

        $this->assertEquals('2', $comment->getRanking());
    }

    public function testGetContent()
    {
        $comment = new Comments();
        $comment->setContent('first test');

        $this->assertEquals('first test', $comment->getContent());
    }

}
