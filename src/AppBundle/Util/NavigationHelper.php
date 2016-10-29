<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 27/10/2016
 * Time: 8:50 AM
 */

namespace AppBundle\Util;

class NavigationHelper
{
    static function getPaginationData($postCount, $currentPage, $itemsPerPage = 4)
    {
        // calculate the number of pagination items required
        // max 4 per page
        $paginationItemsRequired = ceil($postCount / $itemsPerPage);

        $i = 1;
        while ($i <= $paginationItemsRequired) {
            $pagination['items'][$i] = array(
                'index' => $i
            );
            $i++;
        }

        $pagination['first'] = 1;
        $pagination['last'] = $paginationItemsRequired;
        $pagination['current'] = $currentPage;

        if (empty($pagination['items'])) {
            return null;
        }

        return $pagination;

    }
}