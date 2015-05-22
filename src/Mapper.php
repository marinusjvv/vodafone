<?php
namespace MarinusJvv\Vodafone;

class Mapper
{
    private $connections = array();

    /**
     * @param array $connection
     */
    public function mapConnection($connection)
    {
        $this->connections[$connection[0]][$connection[1]] = (int)$connection[2];
    }

    /**
     * @return array
     */
    public function getConnections()
    {
        return $this->connections;
    }
}
