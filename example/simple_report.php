<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/17/13
 * Time: 12:39 AM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting;

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

date_default_timezone_set('America/Denver');

require_once __DIR__ . '/../vendor/autoload.php';

$config = null;

require_once 'config.php';

$report = new Report();
$report->setConfig($config);
$report->setRankedReport();

$description = new ReportDescription();
$description->date = '2013-03-01';
$description->setConfig($config);

$element = new ReportElement();
$element->id = 'page';
$element->top = 20;

$description->addElement($element);

$metric = new ReportMetric();
$metric->id = ReportMetric::METRIC_pageViews;

$description->addMetric($metric);

$metric = new ReportMetric();
$metric->id = ReportMetric::METRIC_totalPageViews;

$description->addMetric($metric);

$report->setDescription($description);

$data = $report->run();

var_dump($data);