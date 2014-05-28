<?php
/**
 * UserController - Short description for file
 *
 * Long description for file (if any)...
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 3 of
 * the License, or (at your option) any later version.
 *
 * @author      Till Glöggler <tgloeggl@uos.de>
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GPL version 3
 * @category    Stud.IP
 */
class UserController extends PortfolioPluginController
{

    public function search_action()
    {
        $search_term = '%'. studip_utf8decode(urldecode(Request::get('term'))) .'%';
        
        foreach (User::findBySQL("(username LIKE ? OR Vorname LIKE ? OR Nachname LIKE ?) AND " . get_vis_query(), 
                array($search_term, $search_term, $search_term)) as $user) {
            $users[] = array(
                'fullname' => get_fullname($user->id) .' ('. $user->username .')',
                'username' => $user->username
            );
        }
        
        $this->render_json($users);
    }
}
