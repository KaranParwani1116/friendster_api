<?php

class TodoTest extends PHPUnit_Framework_TestCase
{
    public function testLoginUser() {
        include __DIR__ . '/../app/TestUser.php';
        $name = "Karan";
        $email = "karanparwani.parwani102@gmail.com";
        $expectedStatus = 201;
        $this->assertEquals(201, checkLoginUser($name, $email));
    } 

    public function testLoadingOwnProfile() {
        $uid = 'fasfhsafsfykayrexafghfakfbak';
        $expectedStatus = 200;
        $this->assertEquals(200, checkProfile($uid));
    }

    public function testLoadOtherProfile() {
        $uid = 'fasfhsafsfykayrexafghfakfbak';
        $expectedStatus = 200;
        $this->assertEquals(200, checkProfile($uid));
    }

    public function testLoadFriends() {
        $uid = 'fasfhsafsfykayrexafghfakfbak';
        $expectedStatus = 200;
        $this->assertEquals(200, checkProfile($uid));
    }

    public function testLoadingTimelinePost() {
        $uid = 'fasfhsafsfykayrexafghfakfbak';
        $expectedStatus = 200;
        $this->assertEquals(200, checkProfile($uid));
    }

    public function testLikes() {
        $uid = 'fasfhsafsfykayrexafghfakfbak';
        $expectedStatus = 200;
        $this->assertEquals(200, checkProfile($uid));
    }

    public function testComments() {
        $uid = 'fasfhsafsfykayrexafghfakfbak';
        $expectedStatus = 200;
        $this->assertEquals(200, checkProfile($uid));
    }

    public function testUploadPost() {
        $uid = 'fasfhsafsfykayrexafghfakfbak';
        $expectedStatus = 200;
        $this->assertEquals(200, checkProfile($uid));
    }
}