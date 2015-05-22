<?php
namespace MarinusJvv\Vodafone;

class Mapper
{
    private $connections = array();

    public function mapConnection($connection)
    {
        $this->connections[$connection[0]][$connection[1]] = $connection[2];
    }

    public function getConnections()
    {
        return $this->connections;
    }
}