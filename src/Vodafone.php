<?php
namespace MarinusJvv\Vodafone;

class Vodafone
{
    public function process($connections)
    {
        $mapper = new Mapper();
        foreach ($connections as $connection) {
            $mapper->mapConnection($connection);
        }
        return $mapper->getConnections();
    }
}