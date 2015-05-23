<?php

class IndexTest extends PHPUnit_Framework_TestCase
{
    public function testIndexMissingCsvLocationExitsScript()
    {
        $output = array();
        exec('php ' . dirname(__FILE__) . '/../index.php', $output);
        $this->assertEquals(
            'Please include the location of the CSV as a parameter',
            $output[0]
        );
    }

    public function testIndexIncorrectCsvLocationExitsScript()
    {
        $output = array();
        exec('php ' . dirname(__FILE__) . '/../index.php /bad/path', $output);
        $this->assertEquals(
            'Please ensure that csv file exists and the script has permission to access it',
            $output[0]
        );
    }

    public function testIndexGivenDifferentInputsGivesExpectedOutputs()
    {
        $descriptorspec = array(
           0 => array("pipe", "r"), 
           1 => array("pipe", "w"), 
           2 => array("pipe", "r")
        );
        $process = proc_open(
            'php ' . dirname(__FILE__) . '/../index.php ' . dirname(__FILE__) . '/data/complex_example.csv',
            $descriptorspec,
            $pipes,
            null,
            null
        );
        if (is_resource($process) === false) {
            // Force test to break
            $this->assertTrue(false);
        }

        fgets($pipes[1],4096);
        fgets($pipes[1],4096);
        fgets($pipes[1],4096);
        fwrite($pipes[0], "nonesense\n");
        $output = trim(fgets($pipes[1], 4096));
        $this->assertEquals('Invalid input', $output);

        fgets($pipes[1],4096);
        fwrite($pipes[0], "A F 1000\n");
        $output = trim(fgets($pipes[1], 4096));
        $this->assertEquals(
            'Path not found',
            trim($output)
        );

        fgets($pipes[1],4096);
        fwrite($pipes[0], "A F 1200\n");
        $output = trim(fgets($pipes[1], 4096));
        $this->assertEquals(
            'A => B => D => E => F => 1120',
            trim($output)
        );

        fgets($pipes[1],4096);
        fwrite($pipes[0], "A D 100\n");
        $output = trim(fgets($pipes[1], 4096));
        $this->assertEquals(
            'A => C => D => 50',
            trim($output)
        );

        fgets($pipes[1],4096);
        fwrite($pipes[0], "E A 400\n");
        $output = trim(fgets($pipes[1], 4096));
        $this->assertEquals(
            'E => D => B => A => 120',
            trim($output)
        );

        fgets($pipes[1],4096);
        fwrite($pipes[0], "E A 80\n");
        $output = trim(fgets($pipes[1], 4096));
        $this->assertEquals(
            'E => D => C => A => 60',
            trim($output)
        );
            
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $return_value = proc_close($process);
    }
}
