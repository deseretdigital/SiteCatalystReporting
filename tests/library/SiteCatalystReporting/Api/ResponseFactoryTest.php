<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/16/13
 * Time: 12:26 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting\Api;

use SiteCatalystReporting\Config;

class ResponseFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildResponseFromClient()
    {
        $mock_client = \Mockery::mock('SiteCatalystReporting\SimpleRestClient');
        $mock_client->shouldReceive('getStatusCode')
            ->atLeast()
            ->once()
            ->andReturn(200);

        $mock_client->shouldReceive('getInfo')
            ->atLeast()
            ->once()
            ->andReturn(array('key' => 'value'));

        $mock_client->shouldReceive('getWebResponse')
            ->atLeast()
            ->once()
            ->andReturn('{"success":true}');

        $factory = new ResponseFactory();
        $response = $factory->buildResponseFromClient($mock_client);

        $this->assertInstanceOf('SiteCatalystReporting\Api\Response', $response, "build response from client did not return a response object");
        $this->assertEquals(200, $response->status_code, "Response Factory failed to set the status code");
        $this->assertEquals(array('key' => 'value'), $response->info, "Response factory failed to set the info");
        $this->assertEquals('{"success":true}', $response->response_str, "Response factory failed to set response_str");
    }
}