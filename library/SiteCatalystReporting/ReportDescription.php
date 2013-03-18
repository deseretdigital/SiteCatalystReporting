<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/16/13
 * Time: 7:45 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting;

class ReportDescription
{
    public $reportSuiteID;
    public $date;
    public $dateTo;
    public $dateFrom;
    public $dateGranularity;
    public $metrics = array();
    public $elements = array();
    public $sortBy;
    public $segmant_id;

    public $config;

    public function toJson()
    {
        $obj = new \stdClass();

        $exclude_members = array('config');

        foreach($this as $k => $v)
        {
            if(!in_array($k, $exclude_members))
            {
                $obj->$k = $v;
            }
        }

        $final = new \stdClass();
        $final->reportDescription = $obj;

        return json_encode($final);
    }

    public function setConfig(Config $config)
    {
        $this->config = $config;
        $this->reportSuiteID = $config->reportSuiteId;
    }

    public function addMetric(ReportMetric $metric)
    {
        $this->metrics[] = $metric;
    }

    public function addElement(ReportElement $element)
    {
        $this->elements[] = $element;
    }

    public function validate()
    {
        if(!$this->config)
        {
            throw new ReportException("No Config Set for Report");
        }

        if(!$this->date)
        {
            if(!$this->dateTo || !$this->dateFrom)
            {
                throw new ReportException("You must supply both dateTo and dateFrom, or just date");
            }
        }
        else
        {
            if($this->dateTo || $this->dateFrom)
            {
                throw new ReportException("If you supply a date, you cannot supply a dateTo or dateFrom as well");
            }
        }

        if(count($this->elements) <= 0)
        {
            throw new ReportException("You must have at least one Element");
        }

        if(count($this->metrics) <= 0)
        {
            throw new ReportException("You must have at least one Metric");
        }
    }
}