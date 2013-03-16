<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/15/13
 * Time: 10:35 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting\Api;

use SiteCatalystReporting\Config;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testSetConfig()
    {

        $mock_config = \Mockery::mock('SiteCatalystReporting\Config[validate]');
        $mock_config->shouldReceive('validate')
            ->atLeast()
            ->once()
            ->andReturn(true);

        $request = new Request();
        $request->setConfig($mock_config);

    }

    public function testSetMethod()
    {
        $valid_names = array(
            'Report.QueueTrended'
            ,'Company.GetTokens'
        );

        $invalid_names = array(
            array()
            , new \stdClass()
            ,"blahblah"
            ,'carmonyisawesome'
        );

        foreach($valid_names as $name)
        {
            $ex = null;
            $request = new Request();
            try
            {
                $request->setMethod($name);
            }
            catch (\Exception $ex)
            {

            }

            $this->assertNull($ex, "Request::setMethod threw exception on a valid name");
            $this->assertEquals($name, $request->method, "Request::setMethod did not store name correctly");
        }

        foreach($invalid_names as $name)
        {
            $ex = null;
            $request = new Request();
            try
            {
                $request->setMethod($name);
            }
            catch (\Exception $ex)
            {

            }

            $this->assertNotNull($ex, "Request::setMethod did not throw a exception on an invalid name");
            $this->assertNull($request->method, "Request::setMethod has a name set when it shouldn't");
        }
    }

    public function testBuildNonce()
    {
        $mock_config = \Mockery::mock('SiteCatalystReporting\Config[validate]', array('validate' => true));
        $mock_config->secret = md5(rand(0,10000));

        $request = new Request();
        $request->setConfig($mock_config);
        $request->buildNonce();
        $nonce_ts = date('c');

        $this->assertEquals($nonce_ts, $request->nonce_ts, "Nonce Timestamp does not match");
        $this->assertNotNull($request->nonce, "Nonce is null");
        $this->assertNotNull($request->digest, "Digest is null");

        $nonce = $request->nonce;

        $digest = base64_encode(sha1($nonce.$nonce_ts.$request->config->secret));

        $this->assertEquals($digest, $request->digest, "Digest incorrectly calculated");
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}