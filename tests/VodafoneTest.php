<?php
use MarinusJvv\Vodafone\Vodafone;

class VodafoneTest extends PHPUnit_Framework_TestCase
{
    public function testProcessGivenPathGetsCorrectlyFormedPaths()
    {
        $vodafone = new Vodafone();

        $path = dirname(__FILE__) . '/data/example.csv';
        $expected = array(
            'a' => array(
                'b' => 10,
                'c' => 20,
            ),
            'b' => array(
                'd' => 100,
            ),
        );
        $this->assertEquals($expected, $vodafone->process($path));
    }
}