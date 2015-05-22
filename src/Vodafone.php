<?php
namespace MarinusJvv\Vodafone;

class Vodafone
{
    public function process($path)
    {
        $reader = new CsvReader();
        $mapper = $reader->read($path);
        return $mapper->getConnections();
    }
}