<?php
use MarinusJvv\Vodafone\Mapper;

class MapperTest extends PHPUnit_Framework_TestCase
{
    public function testMapConnectionsGivenConnectionsCorrectlyMapsConnections()
    {
        $mapper = new Mapper();
        $mapper->mapConnection(array('a', 'b', '10'));
        $mapper->mapConnection(array('a', 'c', '20'));
        $mapper->mapConnection(array('b', 'd', '100'));

        $expected = array(
            'a' => array(
                'b' => 10,
                'c' => 20,
            ),
            'b' => array(
                'd' => 100,
            ),
        );
        $this->assertEquals($expected, $mapper->getConnections());
    }
}