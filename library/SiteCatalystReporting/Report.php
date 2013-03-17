<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/16/13
 * Time: 7:34 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting;

class Report
{
    public $poll_interval = 5; // Seconds
    public $timeout = 3600; // One Hour

    public $method;
    public $description;

    public function setMethod($name)
    {
        $this->method = $name;
    }

    public function setDescription(ReportDescription $description)
    {
        $description->validate();
        $this->description = $description;
    }

    public function run()
    {
        
    }
}