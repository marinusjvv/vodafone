<?php
use MarinusJvv\Vodafone\Mapper;

class MapperTest extends PHPUnit_Framework_TestCase
{
    public function testMapConnectionGivenConnectionsCorrectlyMapsConnections()
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
                'a' => 10,
                'd' => 100,
            ),
            'c' => array(
                'a' => 20,
            ),
            'd' => array(
                'b' => 100,
            ),
        );
        $this->assertEquals($expected, $mapper->getConnections());
    }

    public function testMapConnectionGivenInvalidConnectionsContinues()
    {
        $mapper = new Mapper();
        $mapper->mapConnection(array('a', 'b', '10'));
        $mapper->mapConnection(array('a', '20'));
        $mapper->mapConnection(array('a', 'c', 'd', '20'));
        $mapper->mapConnection(array('a', 'c', 'c'));
        $mapper->mapConnection(array('1', '2', '3'));
        $mapper->mapConnection(array('-', 'c', '10'));
        $mapper->mapConnection(array('a', '-', '10'));
        $mapper->mapConnection(array('a', 'c', -10));
        $mapper->mapConnection(array('a b', 'c', '100'));
        $mapper->mapConnection(array('b', 'd', 100));

        $expected = array(
            'a' => array(
                'b' => 10,
            ),
            'b' => array(
                'a' => 10,
                'd' => 100,
            ),
            'd' => array(
                'b' => 100,
            ),
            '1' => array(
                '2' => 3,
            ),
            '2' => array(
                '1' => 3,
            ),
        );
        $this->assertEquals($expected, $mapper->getConnections());
    }
}
