<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/16/13
 * Time: 10:08 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting;

class ReportDescriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testSetConfig()
    {
        $mock_config = \Mockery::mock('SiteCatalystReporting\Config');
        $description = new ReportDescription();
        $description->setConfig($mock_config);

        $this->assertEquals($mock_config, $description->config, "setConfig failed to correctly set config");
    }

    public function testAddMetric()
    {
        $mock_metric = \Mockery::mock('SiteCatalystReporting\ReportMetric');
        $desc = new ReportDescription();
        $desc->addMetric($mock_metric);

        $this->assertTrue(in_array($mock_metric, $desc->metrics), "Failed to add metric");
    }

    public function testAddElement()
    {
        $mock_el = \Mockery::mock('SiteCatalystReporting\ReportElement');
        $desc = new ReportDescription();
        $desc->addElement($mock_el);

        $this->assertTrue(in_array($mock_el, $desc->elements), "Failed to add element");
    }

    public function testValidateConfig()
    {
        $this->setExpectedException('SiteCatalystReporting\ReportException', 'No Config Set for Report');
        $desc = new ReportDescription();
        $desc->validate();
    }

    public function testValidateDate()
    {
        $this->setExpectedException('SiteCatalystReporting\ReportException');
        $desc = new ReportDescription();
        $desc->config = \Mockery::mock('SiteCatalystReporting\Config');
        $desc->validate();
    }

    public function testValidateDate2()
    {
        $this->setExpectedException('SiteCatalystReporting\ReportException');
        $desc = new ReportDescription();
        $desc->config = \Mockery::mock('SiteCatalystReporting\Config');
        $desc->date = '2013-01-01';
        $desc->dateFrom = '2013-01-01';
        $desc->validate();
    }

    public function testValidateDate3()
    {
        $this->setExpectedException('SiteCatalystReporting\ReportException');
        $desc = new ReportDescription();
        $desc->config = \Mockery::mock('SiteCatalystReporting\Config');
        $desc->date = '2013-01-01';
        $desc->dateTo = '2013-01-01';
        $desc->validate();
    }

    public function testValidateDateTo()
    {
        $this->setExpectedException('SiteCatalystReporting\ReportException');
        $desc = new ReportDescription();
        $desc->config = \Mockery::mock('SiteCatalystReporting\Config');
        $desc->dateTo = '2013-01-01';
        $desc->validate();
    }

    public function testValidateDateFrom()
    {
        $this->setExpectedException('SiteCatalystReporting\ReportException');
        $desc = new ReportDescription();
        $desc->config = \Mockery::mock('SiteCatalystReporting\Config');
        $desc->dateFrom = '2013-01-01';
        $desc->validate();
    }

    public function testValidateElements()
    {
        $this->setExpectedException('SiteCatalystReporting\ReportException');
        $desc = new ReportDescription();
        $desc->config = \Mockery::mock('SiteCatalystReporting\Config');
        $desc->date = '2013-01-01';
        $desc->validate();
    }

    public function testValidateMetrics()
    {
        $this->setExpectedException('SiteCatalystReporting\ReportException');
        $desc = new ReportDescription();
        $desc->config = \Mockery::mock('SiteCatalystReporting\Config');
        $desc->date = '2013-01-01';
        $desc->elements = array(new ReportElement());
        $desc->validate();
    }
}