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

    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}