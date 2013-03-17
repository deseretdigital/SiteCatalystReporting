<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/16/13
 * Time: 8:32 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting;

class ReportMetric
{
    const METRIC_mobileViews = 'mobileViews';
    const METRIC_mobileVisits = 'mobileVisits';
    const METRIC_mobileVisitorsDaily = 'mobileVisitorsDaily';

    const METRIC_pageViews = 'pageViews';
    const METRIC_totalPageViews = 'totalPageViews';

    const METRIC_visits = 'visits';
    const METRIC_totalVisits = 'totalVisits';
    const METRIC_averageTimeSpentOnSite = 'averageTimeSpentOnSite';
    const METRIC_averageVisitDepth = 'averageVisitDepth';

    const METRIC_visitors = 'visitors';
    const METRIC_visitorsNew = 'visitorsNew';
    const METRIC_visitorsHourly = 'visitorsHourly';
    const METRIC_totalVisitorsHourly = 'totalVisitorsHourly';
    const METRIC_visitorsDaily = 'visitorsDaily';
    const METRIC_totalVisitorsDaily = 'totalVisitorsDaily';
    const METRIC_visitorsWeekly = 'visitorsWeekly';
    const METRIC_totalVisitorsWeekly = 'totalVisitorsWeekly';
    const METRIC_visitorsMonthly = 'visitorsMonthly';
    const METRIC_totalVisitorsMonthly = 'totalVisitorsMonthly';
    const METRIC_visitorsQuarterly = 'visitorsQuarterly';
    const METRIC_totalVisitorsQuarterly = 'totalVisitorsQuarterly';
    const METRIC_visitorsYearly = 'visitorsYearly';
    const METRIC_totalVisitorsYearly = 'totalVisitorsYearly';

    const METRIC_videoViews = 'videoViews';
    const METRIC_videoVisits = 'videoVisits';
    const METRIC_videoVisitorsDaily = 'videoVisitorsDaily';

    const METRIC_averagePageDepth = 'averagePageDepth';
    const METRIC_averageTimeSpentOnPage = 'averageTimeSpentOnPage';
    const METRIC_entries = 'entries';
    const METRIC_exits = 'exits';
    const METRIC_reloads = 'reloads';
    const METRIC_singleAccess = 'singleAccess';

    const METRIC_bots = 'bots';


    public $id;
    public $segment;
    public $segmentID;
}