<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/16/13
 * Time: 12:24 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting\Api;

use SiteCatalystReporting\SimpleRestClient;

class ResponseFactory
{
    /**
     * @param \SiteCatalystReporting\SimpleRestClient $client
     * @return \SiteCatalystReporting\Api\Response
     */
    public function buildResponseFromClient(SimpleRestClient $client)
    {
        $response = new Response();

        $response->status_code      = $client->getStatusCode();
        $response->info             = $client->getInfo();
        $response->response_str     = $client->getWebResponse();
        echo "\n".$response->response_str."\n";

        return $response;
    }
}