<?php
/**
 * Helper.php - description
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License
 * version 3 as published by the Free Software Foundation.
 *
 * @author      Till Glöggler <tgloeggl@uos.de>
 * @license     https://www.gnu.org/licenses/agpl-3.0.html AGPL version 3
 */

namespace Portfolio;

class Helper
{
    /**
     * This method returns a diff for the two passed array, listing added 
     * and deleted elements
     * 
     * @param array   $previous  this is how the list looked before the change
     * @param array   $current   this is how the list looks now
     * 
     * @return array  an array containing the removed elements at 'deleted'
     *                and the added elements at 'added'
     */
    static function pick($previous, $current) {
        if (!is_array($previous) || !is_array($current)) {
            return false;
        }

        return array(
            'deleted' => array_unique(array_udiff($previous, $current, array('self', 'udiff_compare'))),
            'added'    => array_unique(array_udiff($current, $previous, array('self', 'udiff_compare')))
        );
    }

    private static function udiff_compare($a, $b)
    {
        return strcmp(serialize($a), serialize($b));
    }
}
