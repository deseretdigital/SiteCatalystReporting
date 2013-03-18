<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/17/13
 * Time: 1:32 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting;

use SiteCatalystReporting\Api\Request;
use SiteCatalystReporting\Api\Response;
use SiteCatalystReporting\Api\ResponseFactory;

class ReportTest extends \PHPUnit_Framework_TestCase
{
    /** Helpers **/

    /**
     * @return Config
     */
    public function getValidConfig()
    {
        $config = new Config();
        $config->username = 'test:Test Company';
        $config->reportSuiteId = 'testid';
        $config->secret = md5('test');

        return $config;
    }

    public function getMockRequestFactory($responses)
    {
        $mock_request = \Mockery::mock('SiteCatalystReporting\Api\Request');
        $mock_request->shouldIgnoreMissing();
        $mock_request->shouldReceive('send')->andReturnValues($responses);
        $mock_factory = \Mockery::mock('SiteCatalystReporting\Api\RequestFactory');
        $mock_factory->shouldReceive('newRequest')->andReturn($mock_request);

        return $mock_factory;
    }

    /**
     * We refactor the code later to have the request accept the straight json, not
     * a data object, so we're making sure our reports don't use the dataobject
     */
    public function testDoesNotCallSetDataObject()
    {
        $config = $this->getValidConfig();
        $report = new Report();
        $report->setConfig($config);
        $report->setTrendedReport();
        $report->poll_interval = 0;

        $responses = array();

        $response = new Response();
        $response->info = array();
        $response->response_str = '{"status":"queued","statusMsg":"Your report has been queued","reportID":129864028}';
        $response->status_code = 200;
        $responses[] = $response;

        $response = new Response();
        $response->info = array();
        $response->response_str = '{"status":"done","queue_time":"2013-03-17 15:09:13","report_type":"ranked","result_size":"1430","error_code":"0","error_msg":""}';
        $response->status_code = 200;
        $responses[] = $response;

        $response = new Response();
        $response->info = array();
        $response->response_str = '{"status":"done","queue_time":"2013-03-17 15:09:13","report_type":"ranked","result_size":"1430","error_code":"0","error_msg":""}';
        $response->status_code = 200;
        $responses[] = $response;

        $report->request_factory = $this->getMockRequestFactory($responses);

        $mock_description = \Mockery::mock('SiteCatalystReporting\ReportDescription');
        $mock_description->json = '{"test":"test"}';
        $mock_description->shouldReceive('setDataObject')->never();
        $mock_description->shouldReceive('validate')->andReturn(true);
        $mock_description->shouldReceive('toJson')->atLeast()->once()->andReturn($mock_description->json);
        $mock_description->config = $config;
        $mock_description->method = 'Report.GetReport';

        $report->setDescription($mock_description);
        $response = $report->run();
    }

    public function testConstruction()
    {
        $report = new Report();
        $this->assertInstanceOf('SiteCatalystReporting\Api\RequestFactory', $report->request_factory);
        $this->assertInstanceOf('SiteCatalystReporting\ReportDataFactory', $report->data_factory);

        // Test passing in your own

        $request_factory = new Api\RequestFactory();
        $data_factory = new ReportDataFactory();

        $report = new Report($request_factory, $data_factory);

        $this->assertEquals($request_factory, $report->request_factory);
        $this->assertEquals($data_factory, $report->data_factory);
    }

    public function testSetTrendedReport()
    {
        $report = new Report();
        $report->setTrendedReport();
        $this->assertEquals($report->method, 'Report.QueueTrended', "Failed to set report to trended");
    }

    public function testSetRankedReport()
    {
        $report = new Report();
        $report->setRankedReport();
        $this->assertEquals($report->method, 'Report.QueueRanked', "Failed to set report to ranked");
    }

    public function testSetOvertimeReport()
    {
        $report = new Report();
        $report->setOvertimeReport();
        $this->assertEquals($report->method, 'Report.QueueOvertime', "Failed to set report to overtime");
    }

    public function testSetDescription()
    {
        $mock_desc = \Mockery::mock('SiteCatalystReporting\ReportDescription');
        $mock_desc->shouldReceive('validate')->atLeast()->once()->andReturn(true);

        $report = new Report();
        $report->config = $this->getValidConfig();
        $report->setDescription($mock_desc);

        $this->assertEquals($mock_desc, $report->description);
    }

    public function testBuildRequest()
    {
        $report = new Report();
        $method = "Test.TestMethod";
        $json = '{"test":"test"}';
        $report->config = $this->getValidConfig();
        $request = $report->buildRequest($method, $json);
        $this->assertEquals($method, $request->method);
        $this->assertEquals($json, $request->json);
    }
}