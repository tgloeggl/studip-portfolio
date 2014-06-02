<?php
class AddPortfolioTables extends DBMigration {

    public function description () {
        return 'initial tables for portfolio';
    }

    public function up () {
        $db = DBManager::get();

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_portfolios` (
              `id` INT NOT NULL AUTO_INCREMENT ,
              `name` VARCHAR(255) NULL ,
              `user_id` VARCHAR(32) NULL COMMENT 'who created this set?' ,
              `chdate` INT NULL ,
              `mkdate` INT NULL ,
              `global` BOOL,
              PRIMARY KEY (`id`) )
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_portfolios_tasks` (
                `portfolio_portfolios_id` INT NOT NULL ,
                `portfolio_tasks_id` VARCHAR(45) NOT NULL ,
                PRIMARY KEY (`portfolio_portfolios_id`, `portfolio_tasks_id`) )
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_tasks` (
              `id` INT NOT NULL AUTO_INCREMENT ,
              `user_id` VARCHAR(32) NULL ,
              `title` VARCHAR(255) NULL ,
              `content` MEDIUMTEXT NULL ,
              `allow_text` TINYINT(1) NULL DEFAULT 0 ,
              `allow_files` TINYINT(1) NULL DEFAULT 0 ,
              `chdate` INT NULL ,
              `mkdate` INT NULL ,
              PRIMARY KEY (`id`) );
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_task_users` (
              `id` INT NOT NULL AUTO_INCREMENT ,
              `portfolio_tasks_id` INT NULL ,
              `user_id` VARCHAR(32) NULL ,
              `answer` MEDIUMTEXT NULL ,
              `feedback` MEDIUMTEXT NULL ,
              `goal` MEDIUMTEXT NULL ,
              `visible` TINYINT(1) NULL DEFAULT 1 ,
              `chdate` INT NULL ,
              `mkdate` INT NULL ,
              PRIMARY KEY (`id`) )
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_task_user_files` (
              `id` INT NOT NULL AUTO_INCREMENT ,
              `portfolio_task_users_id` INT NULL ,
              `dokument_id` varchar(32) NOT NULL,
              `type` enum('answer','feedback') NOT NULL DEFAULT 'answer',
              PRIMARY KEY (`id`) )
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_portfolios_studiengang_combos` (
              `combo_id` INT NOT NULL AUTO_INCREMENT ,
              `portfolios_id` INT NOT NULL,
              PRIMARY KEY (`combo_id`) )
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_studiengang_combos` (
              `combo_id` INT NOT NULL ,
              `studiengang_id` VARCHAR(32) NOT NULL ,
              `abschluss_id` VARCHAR(32) NOT NULL ,
              PRIMARY KEY (`combo_id`, `studiengang_id`, `abschluss_id`) )
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_tags` (
              `id` INT NOT NULL AUTO_INCREMENT ,
              `user_id` VARCHAR(32) NULL ,
              `tag` VARCHAR(255) NULL ,
              PRIMARY KEY (`id`) )
        ");


        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_tags_tasks` (
              `portfolio_tasks_id` INT NOT NULL ,
              `portfolio_tags_id` INT NOT NULL ,
              PRIMARY KEY (`portfolio_tasks_id`, `portfolio_tags_id`) ,
              INDEX `fk_portfolio_tags_tasks_portfolio_tags1_idx` (`portfolio_tags_id` ASC) )
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_permissions` (
              `portfolio_task_users_id` INT NOT NULL ,
              `user_id` VARCHAR(32) NULL ,
              `role` ENUM('tutor','followup-tutor','student') NULL ,
              PRIMARY KEY (`portfolio_task_users_id`, `user_id`)
            ) ENGINE = InnoDB;
        ");

        SimpleORMap::expireTableScheme();
    }

    public function down () {
        $db = DBManager::get();
        $db->exec("DROP TABLE IF EXISTS portfolio_portfolios_task_users");
        $db->exec("DROP TABLE IF EXISTS portfolio_portfolios");
        $db->exec("DROP TABLE IF EXISTS portfolio_permissions");
        $db->exec("DROP TABLE IF EXISTS portfolio_tags_tasks");
        $db->exec("DROP TABLE IF EXISTS portfolio_tags");
        $db->exec("DROP TABLE IF EXISTS portfolio_studiengang_combos");
        $db->exec("DROP TABLE IF EXISTS portfolio_portfolios_studiengang_combos");
        $db->exec("DROP TABLE IF EXISTS portfolio_task_user_files");
        $db->exec("DROP TABLE IF EXISTS portfolio_task_users");
        $db->exec("DROP TABLE IF EXISTS portfolio_tasks");

        SimpleORMap::expireTableScheme();
    }
}
