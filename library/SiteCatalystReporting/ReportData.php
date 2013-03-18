<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/17/13
 * Time: 3:05 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting;

class ReportData
{
    public $json;

    public function __construct($json = null)
    {
        $this->json = $json;
    }
}