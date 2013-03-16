<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/15/13
 * Time: 10:28 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting\Api;

use SiteCatalystReporting\Config;

class Request
{
    /**
     * @var SiteCatalystReporting\Config
     */
    public $config;

    public function __construct()
    {

    }

    public function setConfig($config)
    {
        if($config instanceof Config === false)
        {
            throw new \Exception("Invalid Config Object Passed");
        }
        $config->validate();
        $this->config = $config;
    }
}