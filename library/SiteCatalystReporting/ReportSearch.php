<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jcarmony
 * Date: 3/16/13
 * Time: 8:49 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SiteCatalystReporting;

class ReportSearch
{
    const TYPE_AND = 'AND';
    const TYPE_OR = 'OR';
    const TYPE_NOT = 'NOT';

    public $type = 'AND';
    public $keywords = array();
    public $searches;

    public function addKeyword($keyword)
    {
        $this->keywords[] = $keyword;
    }

    public function setType($name)
    {
        $name = strtoupper($name);
        if($name != self::TYPE_AND && $name != self::TYPE_NOT && $name != self::TYPE_OR)
        {
            throw new ReportException("Invalid ReportSearch Type");
        }

        $this->type = $name;
    }

    public function AddSearch(ReportSearch $search)
    {
        if(!$this->searches)
        {
            $this->searches = array();
        }

        $this->searches[] = $search;
    }
}

