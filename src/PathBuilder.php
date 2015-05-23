<?php
namespace MarinusJvv\Vodafone;

use MarinusJvv\Vodafone\Exceptions\ImpossiblePathException;

class PathBuilder
{
    /**
     * @var array Final resulting path
     */
    private $result;

    /**
     * @var array Mappings to be used in determining path
     */
    private $mappings;

    /**
     * @var int Maximum travel time
     */
    private $maxDuration;

    /**
     * Sets default values for use in execution of script.
     *
     * @param array $mappings Mappings to be used in determining path
     * @param int $maxDuration Maximum travel time
     */
    public function __construct($mappings, $maxDuration)
    {
        $this->mappings = $mappings;
        $this->maxDuration = $maxDuration;
    }

    /**
     * Builds path using from and to location.
     *
     * @param string $origin Original location
     * @param string $destination Final destination
     *
     * @throws ImpossiblePathException
     * 
     * @return array
     */
    public function build($origin, $destination)
    {
        $path = array($origin => 0);
        $this->getPaths($origin, $destination, $path);
        $this->checkIfPathHasBeenFound();
        return $this->result;
    }

    /**
     * Gets paths from current destination. Removes previous path if it's a dead
     * end.
     *
     * @param string $from Current starting location
     * @param string $destination Final destination
     * @param array &$path Current path taken
     *
     * @return void
     */
    private function getPaths($from, $destination, &$path)
    {
        $totalTime = $this->getTotalTime($path);
        if ($this->doesNextDeviceExist($from) === false || $this->isTimeOverLimit($totalTime) === true) {
            array_pop($path);
            return;
        }
        foreach ($this->mappings[$from] as $to => $time) {
            $path[$to] = $time;
            $this->writeDataIfComplete($to, $destination, $time, $totalTime, $path);
            $this->getPaths($to, $destination, $path);
        }
        array_pop($path);
    }

    /**
     * Gets the current total travelled time.
     *
     * @param array $path The current travelled path
     *
     * @return integer
     */
    private function getTotalTime($path)
    {
        $totalTime = 0;
        foreach ($path as $time) {
            $totalTime += $time;
        }
        return $totalTime;
    }

    /**
     * Is there a device connected to this one?
     *
     * @param string $from Current device
     *
     * @return bool
     */
    private function doesNextDeviceExist($from)
    {
        return array_key_exists($from, $this->mappings) === true;
    }

    /**
     * Is the current travelled time over the limit?
     *
     * @param integer $totalTime Current travelled time
     */
    private function isTimeOverLimit($totalTime)
    {
        return $totalTime > $this->maxDuration;
    }

    /*
     * Has the answer already been set?
     * 
     * @return bool
     */
    private function hasPathBeenFound()
    {
        return empty($this->result) === false;
    }

    /*
     * Writes the data to the result variable if the final destination is reached.
     *
     * @param string $to Current destination to be checked
     * @param string $destination Final destination
     * @param int $time Time from current origin to current destination
     * @param int $totalTime Time from origin to current destination
     * @param array $path Current path taken
     */
    private function writeDataIfComplete($to, $destination, $time, $totalTime, $path)
    {
        if ($this->isDestinationFinalAndWithinTimeLimit($to, $destination, $time, $totalTime) === true) {
            if ($this->hasPathBeenFound() === false) {
                $this->result = $path;
            }
        }
    }

    /**
     * Checks to see if we have reached the final destination within the alloted time
     *
     * @param string $to Current destination
     * @param string $destination Final destination
     * @param integer $time Time from current origin to current destination
     * @param integer $totalTime Time from origin to current destination
     *
     * @return bool
     */
    private function isDestinationFinalAndWithinTimeLimit($to, $destination, $time, $totalTime)
    {
        return $to === $destination
            && $this->isTimeOverLimit($time + $totalTime) === false;
    }

    /**
     * Checks to see if we have found a path to the destination yet
     *
     * @throws MarinusJvv\Vodafone\Exceptions\ImpossiblePathException
     */
    private function checkIfPathHasBeenFound()
    {
        if ($this->hasPathBeenFound() === false) {
            throw new ImpossiblePathException();
        }
    }
}
