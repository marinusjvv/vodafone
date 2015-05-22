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

    public function testIndexGivenInputGivesExpectedOutput()
    {
        $this->markTestIncomplete();
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

        fwrite($pipes[0], "a b 200\n");    // send start
        echo fgets($pipes[1],4096); //get answer
            
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $return_value = proc_close($process);  //stop test_gen.php
        echo ("Returned:".$return_value."\n");
    }
}
