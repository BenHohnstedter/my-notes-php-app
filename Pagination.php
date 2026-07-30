<?php
class Pagination
{
    public function getMaxPages($limit, $maxEntries): int
    {
        return (int)ceil($maxEntries/$limit); //maxPages
    }

    public function paginationLogic($curPage, $maxPages, $filteredArray):array
    {
        $returnButton = NULL;
        $forwardButton = NULL;

        if ($maxPages > 3) {
            $returnButton = 1;
            $forwardButton = 1;
        }

        if ($filteredArray['default'] == 1) {
            $returnButton = NULL;
        } elseif ($filteredArray['default'] == $maxPages) {
            $forwardButton = NULL;
        }

        $startPoint = $filteredArray['start']-1;
        $endPoint = $filteredArray['end']+1;

        if ($maxPages == 2) {
            if ($curPage == 1 OR $curPage === NULL) {
                $endPoint = $filteredArray['end'];
            } elseif ($curPage == 2) {
                $startPoint = $filteredArray['start'];
            }
        }

        return [
            'returnButton' => $returnButton,
            'forwardButton' => $forwardButton,
            'startPoint' => $startPoint,
            'endPoint' => $endPoint
        ];
    }
}