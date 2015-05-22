<?php
namespace MarinusJvv\Vodafone;

class Mapper
{
    /**
     * @var array Array containing all the original connections.
     *
     * The format of the array looks like this:
     *  array(
     *      origin => array(
     *          destination => travel_time,
     *          destination2 => travel_time,
     *      ),
     *      origin2 => array(
     *          destination => travel_time,
     *      ),
     *  )
     */
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
