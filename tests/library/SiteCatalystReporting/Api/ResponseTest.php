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

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruction()
    {
        $response = new Response();

        $this->assertNull($response->response_str);
        $this->assertNull($response->info);
        $this->assertNull($response->status_code);
    }
}