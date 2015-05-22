<?php
use MarinusJvv\Vodafone\Vodafone;

class VodafoneTest extends PHPUnit_Framework_TestCase
{
    public function testMapConnectionsGivenConnectionsCorrectlyMapsConnections()
    {
        $vodafone = new Vodafone();

        $connections = array(
            0 => array('a', 'b', '10'),
            1 => array('a', 'c', '20'),
            2 => array('b', 'd', '100'),
        );

        $expected = array(
            'a' => array(
                'b' => 10,
                'c' => 20,
            ),
            'b' => array(
                'd' => 100,
            ),
        );
        $this->assertEquals($expected, $vodafone->process($connections));
    }
}