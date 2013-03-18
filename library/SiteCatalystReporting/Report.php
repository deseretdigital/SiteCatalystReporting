<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/16/13
 * Time: 7:34 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting;

use \SiteCatalystReporting\ReportDataFactory;

use \SiteCatalystReporting\Api\Request;
use \SiteCatalystReporting\Api\Response;
use \SiteCatalystReporting\Api\RequestFactory;

class Report
{
    public $poll_interval = 5; // Seconds
    public $timeout = 3600; // One Hour
    public $poll_count = 0;

    public $method;
    public $done = false;
    public $error = false;
    public $report_id;
    /**
     * @var ReportDescription
     */
    public $description;
    public $config;

    public $request_factory;
    public $data_factory;

    public function __construct($request_factory = null, $data_factory = null)
    {
        $this->request_factory = ($request_factory instanceof RequestFactory) ? $request_factory : new RequestFactory();
        $this->data_factory = ($data_factory instanceof ReportDataFactory) ? $data_factory : new ReportDataFactory();
    }


    public function setTrendedReport()
    {
        $this->setMethod('Report.QueueTrended');
    }

    public function setRankedReport()
    {
        $this->setMethod('Report.QueueRanked');
    }

    public function setOvertimeReport()
    {
        $this->setMethod('Report.QueueOvertime');
    }

    public function setConfig(Config $config)
    {
        $this->config = $config;
    }


    public function setMethod($name)
    {
        $this->method = $name;
    }

    public function setDescription(ReportDescription $description)
    {
        $description->validate();
        $this->description = $description;
    }


    /**
     * @param $method
     * @param $json
     * @return Request
     */
    public function buildRequest($method, $json)
    {
        $request = $this->request_factory->newRequest();
        $request->setConfig($this->config);
        $request->setMethod($method);
        $request->json = $json;

        return $request;
    }


    public function queueReport()
    {
        $request = $this->buildRequest($this->method, $this->description->toJson());
        $response = $request->send();

        if($response->status_code == 200)
        {
            $json = json_decode($response->response_str);

            if ($json->status == "queued")
            {
                $this->report_id = $json->reportID;
            }
            else if ($json->status == "failed" || strstr($json->status, "error") > 0)
            {
                throw new ReportException("Report failed to be inserted into the queue");
            }
        }
        else
        {
            throw new ReportException("Report Status Code: ".$response->status_code." with response: ".$response->response_str);
        }

        return true;
    }

    public function pollReport()
    {
        if($this->done || $this->error)
        {
            return true;
        }

        echo "Waiting {$this->poll_interval} seconds... ";
        sleep($this->poll_interval);
        echo "Done. Checking...\n";

        $request = $this->buildRequest("Report.GetStatus", '{"reportID":"'.$this->report_id.'"}');
        $response = $request->send();

        if ($response->status_code == 200)
        {
            $json = json_decode($response->response_str);

            if ($json->status=="done")
            {
                $this->done = true;
                return true;
            }
            else if ($json->status=="failed" || strstr($json->status, "error")>0)
            {
                throw new ReportException("Report Queue responded with the status of ".$json->status);
            }
        }
        else
        {
            throw new ReportException("PollReport returned with a non 200 status: ".$response->status_code." - ".$response->response_str);
        }

        return false;
    }

    public function getReport()
    {
        $request = $this->buildRequest("Report.GetReport", '{"reportID":"'.$this->report_id.'"}');
        $response = $request->send();

        if ($response->status_code == 200)
        {
            $data = $this->data_factory->newReportData($response->response_str);
        }
        else
        {
            throw new ReportException("Response returned ".$response->status_code." while getting report: ".$response->response_str);
        }

        return $data;
    }

    /**
     * @return ReportData
     */
    public function run()
    {
        $this->queueReport();
        while($this->pollReport() === false)
        {
            $this->poll_count++;
        }

        $data = $this->getReport();

        return $data;
    }
}