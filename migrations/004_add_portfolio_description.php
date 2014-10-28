<?php
class AddPortfolioDescription extends Migration
{
    public function description ()
    {
        return 'add description for portfolios';
    }

    public function up ()
    {
        $db = DBManager::get();

        $db->exec("ALTER TABLE `portfolio_portfolios` ADD `description` text NULL AFTER `name`");
    }

    public function down ()
    {
        $db = DBManager::get();

        $db->exec("ALTER TABLE `portfolio_portfolios` DROP `description`");
    }
}
