<?php
class AddPortfolioTables extends DBMigration {

    public function description () {
        return 'initial tables for portfolio';
    }

    public function up () {
        $db = DBManager::get();
        
        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_tasksets` (
              `id` INT NOT NULL AUTO_INCREMENT ,
              `name` VARCHAR(255) NULL ,
              `user_id` VARCHAR(32) NULL COMMENT 'who created this set?' ,
              `chdate` INT NULL ,              
              `mkdate` INT NULL ,
              PRIMARY KEY (`id`)
            ) ENGINE = InnoDB;
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_tasks` (
              `id` INT NOT NULL AUTO_INCREMENT ,
              `taskset_id` INT NULL ,
              `user_id` VARCHAR(32) NULL ,
              `title` VARCHAR(255) NULL ,
              `content` MEDIUMTEXT NULL ,
              `allow_text` TINYINT(1) NULL DEFAULT 0 ,
              `allow_files` TINYINT(1) NULL DEFAULT 0 ,
              `chdate` INT NULL ,
              `mkdate` INT NULL ,
              PRIMARY KEY (`id`) ,
              INDEX `fk_portfolio_tasks_portfolio_tasksets1_idx` (`taskset_id` ASC)
            ) ENGINE = InnoDB;
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
              PRIMARY KEY (`id`) ,
              INDEX `fk_portfolio_tasks_users_portfolio_tasks_idx` (`portfolio_tasks_id` ASC)
            ) ENGINE = InnoDB;
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_task_user_files` (
              `id` INT NOT NULL AUTO_INCREMENT ,
              `portfolio_task_users_id` INT NULL ,
              `user_id` VARCHAR(32) NULL ,
              `file_id` VARCHAR(32) NULL ,
              `chdate` INT NULL ,
              `mkdate` INT NULL ,
              PRIMARY KEY (`id`) ,
              INDEX `fk_portfolio_tasks_users_files_portfolio_tasks_users1_idx` (`portfolio_task_users_id` ASC)
            ) ENGINE = InnoDB;
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_tasksets_studiengang_combos` (
              `portfolio_tasksets_id` INT NOT NULL ,
              `portfolio_studiengang_combos_id` INT NOT NULL ,
              PRIMARY KEY (`portfolio_tasksets_id`, `portfolio_studiengang_combos_id`) ,
              INDEX `fk_portfolio_tasksets_studiengang_combos_portfolio_tasksets_idx` (`portfolio_tasksets_id` ASC)
            ) ENGINE = InnoDB;
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_studiengang_combos` (
              `combo_id` INT NOT NULL ,
              `studiengang_id` VARCHAR(32) NOT NULL ,
              PRIMARY KEY (`combo_id`, `studiengang_id`) ,
              INDEX `fk_portfolio_studiengang_combos_portfolio_tasks_studiengang_idx` (`combo_id` ASC)
            ) ENGINE = InnoDB;
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_tags` (
              `id` INT NOT NULL AUTO_INCREMENT ,
              `user_id` VARCHAR(32) NULL ,
              `tag` VARCHAR(255) NULL ,
              PRIMARY KEY (`id`)
            ) ENGINE = InnoDB;
        ");


        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_tags_task_users` (
              `portfolio_task_users_id` INT NOT NULL ,
              `portfolio_tags_id` INT NOT NULL ,
              PRIMARY KEY (`portfolio_task_users_id`, `portfolio_tags_id`) ,
              INDEX `fk_portfolio_tags_task_users_portfolio_tags1_idx` (`portfolio_tags_id` ASC)
            ) ENGINE = InnoDB;
        ");


        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_permissions` (
              `portfolio_task_users_id` INT NOT NULL ,
              `user_id` VARCHAR(32) NULL ,
              `role` ENUM('supervisor','goal','fellow') NULL ,
              PRIMARY KEY (`portfolio_task_users_id`, `user_id`) 
            ) ENGINE = InnoDB;
        ");


        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_portfolios` (
              `id` INT NOT NULL AUTO_INCREMENT ,
              `name` VARCHAR(255) NULL ,
              `user_id` VARCHAR(32) NULL ,
              PRIMARY KEY (`id`)
            ) ENGINE = InnoDB;
        ");

        $db->exec("
            CREATE  TABLE IF NOT EXISTS `portfolio_portfolios_task_users` (
              `portfolio_portfolios_id` INT NOT NULL ,
              `portfolio_task_users_id` INT NOT NULL ,
              PRIMARY KEY (`portfolio_portfolios_id`, `portfolio_task_users_id`) ,
              INDEX `fk_portfolio_portfolios_task_users_portfolio_task_users1_idx` (`portfolio_task_users_id` ASC) 
            ) ENGINE = InnoDB;
        "); 
    }
    
    public function down () {
        $db = DBManager::get();
        $db->exec("DROP TABLE portfolio_portfolios_task_users");
        $db->exec("DROP TABLE portfolio_portfolios");
        $db->exec("DROP TABLE portfolio_permissions");
        $db->exec("DROP TABLE portfolio_tags_task_users");
        $db->exec("DROP TABLE portfolio_tags");
        $db->exec("DROP TABLE portfolio_studiengang_combos");
        $db->exec("DROP TABLE portfolio_tasksets_studiengang_combos");
        $db->exec("DROP TABLE portfolio_task_user_files");
        $db->exec("DROP TABLE portfolio_task_users");
        $db->exec("DROP TABLE portfolio_tasks");
        $db->exec("DROP TABLE portfolio_tasksets");
        
    }
}
