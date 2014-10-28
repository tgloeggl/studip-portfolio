<?php
class CloseTask extends DBMigration
{

    public function description ()
    {
        return 'initial tables for portfolio';
    }

    public function up ()
    {
        $db = DBManager::get();

        $db->exec("ALTER TABLE `portfolio_task_users`
            ADD `closed` tinyint(1) NULL DEFAULT '0' AFTER `visible`");
    }

    public function down ()
    {
        $db = DBManager::get();

        $db->exec("ALTER TABLE `portfolio_task_users`
            DROP `closed`");
    }
}
