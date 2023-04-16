<?php

namespace App\Entity\Tests;

use App\Entity\Categories;
use PHPUnit\Framework\TestCase;

class CategoriesTest extends TestCase
{
    public function testGetName()
    {
        $category = new Categories();
        $category->setName('test');

        $this->assertEquals('test', $category->getName());
    }

}
