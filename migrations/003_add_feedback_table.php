<?php

/**
 * File - description
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License
 * version 3 as published by the Free Software Foundation.
 *
 * @author      Till Glöggler <tgloeggl@uos.de>
 * @license     https://www.gnu.org/licenses/agpl-3.0.html AGPL version 3
 */
class AddFeedbackTable extends DBMigration
{

    public function description ()
    {
        return 'add seaparate table for feedback';
    }

    public function up ()
    {
        $db = DBManager::get();

        $db->exec("CREATE TABLE IF NOT EXISTS `portfolio_task_user_feedback` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `portfolio_task_users_id` int(11) NOT NULL,
            `user_id` varchar(32) NOT NULL,
            `feedback` mediumtext NOT NULL,
            `mkdate` int NOT NULL,
            `chdate` int NOT NULL
        )");

        $db->exec("ALTER TABLE `portfolio_task_users` DROP feedback");
    }

    public function down ()
    {
        $db = DBManager::get();

        $db->exec("DROP TABLE `portfolio_task_user_feedback`");

        $db->exec("ALTER TABLE `portfolio_task_users`
            ADD `feedback` mediumtext NULL AFTER `answer`");
    }
}


