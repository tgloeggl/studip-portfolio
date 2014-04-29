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
    static function pick(\SimpleCollection $collection, $data) {
        if (!is_array($data)) {
            return false;
        }
        
        var_dump($collection->toArray(), $data);
        var_dump(array_diff($collection, $data));
    }
}
