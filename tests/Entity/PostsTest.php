<?php

namespace App\Entity\Tests;

use App\Entity\Posts;
use PHPUnit\Framework\TestCase;

class PostsTest extends TestCase
{
    public function testGetTitle()
    {
        $post = new Posts();
        $post->setTitle('First test');

        $this->assertEquals('First test', $post->getTitle());
    }

    public function testGetSubtitle()
    {
        $post = new Posts();
        $post->setSubtitle('First test test');

        $this->assertEquals('First test test', $post->getSubtitle());
    }

    public function testGetContent()
    {
        $post = new Posts();
        $post->setContent('First test test');

        $this->assertEquals('First test test', $post->getContent());
    }
   
}
