<?php
/**
 * Created by PhpStorm.
 * User: arimolzer
 * Date: 1/11/2016
 * Time: 10:57 AM
 */

namespace AppBundle\Util;


use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Constraints\DateTime;

class DateHelper
{

    /**
     * @param $comparisonDate
     * @return string
     */
    public function formatDateDifference($comparisonDate) {


        if (!$comparisonDate) {
            return new InvalidArgumentException("Method requires a DateTime object");
        }

        // Gets DateTime object based on the UNIX timestamp
        $now = new \DateTime(date("Y-m-d H:i:s"));

        // Get the difference of the two DateTime objects
        $dateDiff = date_diff($comparisonDate,$now);

        // Get the number of days.
        $days = (int)$dateDiff->format("%a");

        $differenceInDays = $days;

        // Calculate the difference in days
        // $differenceInDays = floor($dateDiff / (60 * 60 * 24));

        // Return the appropriate string verision of the date
        // Check if the difference should be displayed in day form
        if ($differenceInDays < 31) {
            return self::formatDaysToStringDays($differenceInDays);
        }

        // Check if the difference should be displayed in month form
        elseif ($differenceInDays <= 31 && $differenceInDays < 365) {
            return self::formatDaysToStringMonths($differenceInDays);
        }

        // Check if the difference should be displayed in year form
        elseif ($differenceInDays >= 365 ) {
            return self::formatDaysToStringYears($differenceInDays);
        }

        // If it gets to here, mathematics and logic have broken.
        return false;

    }

    /**
     * @param $days
     * @return string
     */
    protected function formatDaysToStringDays($days) {

        // Switch to return the appropriate text
        switch ($days) {
            case 0:
                return "Today";
            case 1:
                return "Yesterday";
            default:
                // Return formatted string (eg. '3 days Ago')
                return $days . " Days Ago";
        }
    }

    /**
     * @param $days
     * @return string
     */
    protected function formatDaysToStringMonths($days) {

        // Calculate the number of months
        $months = floor($days / 31);

        // Check if the amount of months is invalid - 0 months will error
        if (!$months) {
            Return new InvalidArgumentException("Invalid number of days for a month.");
        }

        // Format (eg. '3 Months Ago')
        $formattedMonthsString = $months . " Month" . ($months > 1 ? 's' : '') . " Ago";

        // Return formatted string
        return $formattedMonthsString;
    }

    /**
     * @param $days
     * @return string
     */
    protected function formatDaysToStringYears($days) {

        // Calculate the number of months
        $years = floor($days / 365);

        // Check if the amount of months is invalid - 0 years will error
        if (!$years) {
            Return new InvalidArgumentException("Invalid number of days for a year.");
        }

        // Format (eg. '2 Years Ago')
        $formattedYearsString = $years . " Year" . ($years > 1 ? 's' : '') . " Ago";

        // Return formatted string
        return $formattedYearsString;
    }

}