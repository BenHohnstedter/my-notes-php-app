<?php
class Filters
{
    protected Config $config;

    public function __construct()
    {
        $this->config = new Config();
    }

    public function filterPinnedState($param):?string
    {
        if ($param !== NULL AND $param != 0 AND $param != 1) {
            $param = 0;
        }
        return $param;
    }

    public function filterArrays($param, $array):?int
    {
        if (!isset($array[$param])) {
            $param = 0;
        }
        return $param;
    }

    public function filterPagination($page, $maxPages):array
    {
        if ($page < 1) {
            $page = 1;
        } elseif ($page > $maxPages) {
            $page = $maxPages;
        }

        if ($page == 1) {
            $start = 2;
            $end = 2;
        } elseif ($page == $maxPages) {
            $start = $maxPages-1;
            $end = $maxPages-1;
        } else {
            $start = $page;
            $end = $page;
        }

        return [
            'default' => $page,
            'start' => $start,
            'end' => $end,
        ];
    }
}