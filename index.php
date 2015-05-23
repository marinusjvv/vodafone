<?php 

require_once dirname(__FILE__) . '/vendor/autoload.php';

use MarinusJvv\Vodafone\Vodafone;
use MarinusJvv\Vodafone\InputProcessor;

$index = new index();
$index->begin(@$argv[1]);

class index
{
    /**
     * @param string|null $location Location of CSV file containing paths
     */
    public function begin($location)
    {
        $processor = $this->getPathProcessor($location);
        echo "Please enter desired to and from device, as well as time limit in milliseconds. Format should be [Device From] [Device To] [Time]\n\n";
        while (true) {
            $this->process($processor);
        }
    }

    /**
     * Processes inputs, echo's outputs
     *
     * @param Vodafone $processor Processor to build paths
     */
    private function process($processor)
    {
        echo "Input: \n";
        try {
            $input = $this->recieveInput();
        } catch (MarinusJvv\Vodafone\Exceptions\InvalidInputException $e) {
            echo "Invalid input\n";
            return;
        }
        try {
            $returned = $processor->process($input['from'], $input['to'], $input['time']);
        } catch (MarinusJvv\Vodafone\Exceptions\ImpossiblePathException $e) {
            echo "Path not found\n";
            return;
        }
        $this->outputResults($returned);
    }

    /**
     * @throws MarinusJvv\Vodafone\Exceptions\InvalidInputException
     *
     * @return array
     */
    private function recieveInput()
    {
        $processor = new InputProcessor();
        return $processor->recieveInput();
    }

    /**
     * Show's the resulting path and time taken
     *
     * @var array $returned Data from processor
     */
    private function outputResults($returned)
    {
        $totalTime = 0;
        foreach ($returned as $device => $time) {
            echo $device . ' => ';
            $totalTime += $time;
        }
        echo $totalTime . "\n";
    }

    /**
     * @param string $location CSV file location
     *
     * @return Vodafone
     */
    private function getPathProcessor($location)
    {
        $this->checkLocationHasBeenEntered($location);
        try {
            return new Vodafone($location);
        } catch (MarinusJvv\Vodafone\Exceptions\FileNotFoundException $e) {
            exit('Please ensure that csv file exists and the script has permission to access it');
        }
    }

    /**
     * @param $location CSV file location
     */
    private function checkLocationHasBeenEntered($location)
    {
        if (empty($location) === true) {
            exit('Please include the location of the CSV as a parameter');
        }
    }
}