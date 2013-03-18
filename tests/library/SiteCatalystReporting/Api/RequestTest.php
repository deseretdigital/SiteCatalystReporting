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

    public function getValidConfig()
    {
        $config = new Config();
        $config->username = 'test:Test Company';
        $config->reportSuiteId = 'testid';
        $config->secret = md5('test');

        return $config;
    }

    public function testConstructor()
    {
        $request = new Request();
        $this->assertNotNull($request->client);
        $this->assertInstanceOf('SiteCatalystReporting\SimpleRestClient', $request->client, "Request Client not correct type");
    }

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

    public function testBuildHeaders()
    {
        $config = $this->getValidConfig();
        $request = new Request();
        $request->setConfig($config);
        $request->buildNonce();

        $mock_client = \Mockery::mock('SiteCatalystReporting\SimpleRestClient');
        $mock_client->shouldReceive('setOption')
            ->with(CURLOPT_HTTPHEADER, array("X-WSSE: UsernameToken Username=\"{$config->username}\", PasswordDigest=\"{$request->digest}\", Nonce=\"{$request->nonce}\", Created=\"{$request->nonce_ts}\""))
            ->atLeast()
            ->once();

        $request->client = $mock_client;

        $request->buildHeaders();
    }

    public function testSend()
    {
        $request = new Request();
        $request->setConfig($this->getValidConfig());
        $request->setMethod(Request::METHOD_Report_QueueTrended);
        $request->json = "[]";

        $mock_client = \Mockery::mock('SiteCatalystReporting\SimpleRestClient');
        $mock_client->shouldReceive('setOption')->atLeast()->once()->andReturn(true);
        $mock_client->shouldReceive('postWebRequest')->atLeast()->once()->andReturn(true);
        $mock_client->shouldReceive('getStatusCode')->atLeast()->once()->andReturn(200);
        $mock_client->shouldReceive('getInfo')->atLeast()->once()->andReturn(array());
        $mock_client->shouldReceive('getWebResponse')->atLeast()->once()->andReturn('{"success":true}');

        $request->client = $mock_client;

        $response = $request->send();
        $this->assertInstanceOf('SiteCatalystReporting\Api\Response', $response);
    }

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}