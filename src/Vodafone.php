<?php
namespace MarinusJvv\Vodafone;

class Vodafone
{
    public function process($path)
    {
        $handle = fopen($path, 'r');
        return $this->mapConnections($handle);
    }

    private function mapConnections($handle)
    {
        $mapper = new Mapper();
        while (($connection = fgetcsv($handle)) !== FALSE) {
            $mapper->mapConnection($connection);
        }
        return $mapper->getConnections();
    }
}