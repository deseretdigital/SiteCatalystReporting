<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/15/13
 * Time: 8:48 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function assertExceptionMessageContains($message_str, $function)
    {
        $e = null;
        try
        {
            $function();
        }
        catch (\Exception $e)
        {
            // Do nothing
        }

        $actual_message = '';
        if($e instanceof \Exception)
        {
            $actual_message = $e->getMessage();
        }

        $this->assertEquals(true, (stripos($actual_message, $message_str) !== false), "Exception Message of '{$actual_message}' does not contain the string '{$message_str}'");
    }

    public function testConstruction()
    {
        $config = new Config();
        $this->assertEquals($config->server, "https://api.omniture.com", "Config Server not set correctly in constructor");
        $this->assertEquals($config->path, "/admin/1.3/rest/", "Config Path not set correctly in constructor");
    }

    public function testValidate()
    {
        // Build a good, clean config
        $config = new Config();
        $config->reportSuiteId = 'test_suite_id';
        $config->secret = '12345';
        $config->username = 'justin:Justin Carmony Website';



        $bad_config = clone $config;
        unset($bad_config->reportSuiteId);
        $this->assertExceptionMessageContains("reportSuiteId", function() use ($bad_config){
           $bad_config->validate();
        });

        $bad_config = clone $config;
        unset($bad_config->secret);
        $this->assertExceptionMessageContains("secret", function() use ($bad_config){
            $bad_config->validate();
        });

        $bad_config = clone $config;
        unset($bad_config->username);
        $this->assertExceptionMessageContains("username", function() use ($bad_config){
            $bad_config->validate();
        });

        $bad_config = clone $config;
        unset($bad_config->server);
        $this->assertExceptionMessageContains("server", function() use ($bad_config){
            $bad_config->validate();
        });

        $bad_config = clone $config;
        unset($bad_config->path);
        $this->assertExceptionMessageContains("path", function() use ($bad_config){
            $bad_config->validate();
        });

        $e = null;
        try
        {
            $config->validate();
        }
        catch(\Exception $ex)
        {
            // Do Nothing
        }

        $this->assertEquals(null, $e, "Config failed to validate properly and threw an exception");
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}