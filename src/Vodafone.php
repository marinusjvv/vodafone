<?php
namespace MarinusJvv\Vodafone;

class Vodafone
{
    private $connections = array();

    public function mapConnections($connections)
    {
        foreach ($connections as $connection) {
            $this->connections[$connection[0]][$connection[1]] = $connection[2];
        }
    }

    public function getConnections()
    {
        return $this->connections;
    }
}