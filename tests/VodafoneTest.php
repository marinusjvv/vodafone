<?php
use MarinusJvv\Vodafone\Vodafone;

class VodafoneTest extends PHPUnit_Framework_TestCase
{
    public function testBasicSetup()
    {
        $vodafone = new Vodafone();
        $this->assertTrue($vodafone->setupTest());
    }
}