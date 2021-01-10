<?php

class TodoTest extends PHPUnit_Framework_TestCase
{
    public function testLoginUser() {
        include __DIR__ . '/../app/TestUser.php';
        $expectedStatus = 201;
        $this->assertEquals(201, checkLoginUser("Karan", "karanparwani.parwani102@gmail.com"));
    } 

}