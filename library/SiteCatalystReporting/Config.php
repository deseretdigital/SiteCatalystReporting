<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/15/13
 * Time: 8:32 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting;

class Config
{
    public $username;
    public $secret;
    public $server = 'https://api.omniture.com';
    public $path = '/admin/1.3/rest/';
    public $reportSuiteId;


    public function validate()
    {
        if(!$this->username)
        {
            throw new \Exception("Missing Config username");
        }

        if(!$this->secret)
        {
            throw new \Exception("Missing Config secret");
        }

        if(!$this->server)
        {
            throw new \Exception("Missing Config server");
        }

        if(!$this->path)
        {
            throw new \Exception("Missing Config path");
        }

        if(!$this->reportSuiteId)
        {
            throw new \Exception("Missing Config reportSuiteId");
        }

        return true;
    }
}