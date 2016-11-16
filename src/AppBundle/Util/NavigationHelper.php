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
    static function getPaginationData($postCount, $currentPage, $itemsPerPage = 3)
    {
        // calculate the number of pagination items required
        // max 4 per page
        $paginationItemsRequired = ceil($postCount / $itemsPerPage);

        // The pagination selection only holds 7 items max.
        // Filter out all others
        $i = 1;
        $pagination['items'] = array();
        while ($i <= $paginationItemsRequired) {

            if ($i >= $currentPage - 3 && $i <= $currentPage + 3) {
                $pagination['items'][$i] = array(
                    'index' => $i
                );
            }
            $i++;
        }

        // Return null if there are no pagiation items
        // Causing pagination to be hidden
        if (empty($pagination['items'])) {
            return null;
        }

        // Define the easy page indices
        $pagination['first'] = 1;
        $pagination['last'] = $paginationItemsRequired;
        $pagination['current'] = $currentPage;

        // Check the next indice not greater than the amount of pages available
        if ($currentPage + 1 <= $paginationItemsRequired) {
            $pagination['next'] = $currentPage + 1;
        } else {
            $pagination['next'] = $currentPage;
        }

        // Check the previous indice is not less than the amount of pages available
        if ($currentPage - 1 != 0) {
            $pagination['previous'] = $currentPage - 1;
        } else {
            $pagination['previous'] = $currentPage;
        }

        return $pagination;

    }
}