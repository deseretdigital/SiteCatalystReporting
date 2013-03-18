<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/17/13
 * Time: 2:31 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting\Api;

use SiteCatalystReporting\SimpleRestClient;


class RequestFactory
{
    /**
     * @return Request
     */
    public function newRequest()
    {
        return new Request();
    }
}