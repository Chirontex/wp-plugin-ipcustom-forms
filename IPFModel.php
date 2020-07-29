<?php

class IPFModel
{

    public $db;
    public $db_name;
    public $db_prefix;

    public function __construct(string $database, string $prefix = '')
    {

        global $table_prefix;
        
        if ($database === DB_NAME) {

            global $wpdb;

            $this->db = $wpdb;
            $this->db_name = DB_NAME;
            $this->db_prefix = $table_prefix;

        } else {

            $this->db_name = $database;
            
            if (empty($prefix)) $this->db_prefix = $table_prefix;
            else $this->db_prefix = $prefix;

            $this->db = new wpdb(DB_USER, DB_PASSWORD, $this->db_name, DB_HOST);

            if (!empty($this->db->error)) wp_die($this->db->error);

        }

    }

    public function create_tables()
    {

        $subscribers_table = $this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db_prefix."form_subscribers` (`ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, `email` CHAR(50) NOT NULL, `datetime` DATETIME NOT NULL, PRIMARY KEY (`ID`), UNIQUE INDEX `email` (`email`)) COLLATE='utf8mb4_unicode_ci' AUTO_INCREMENT=0");

        $letters_table = $this->db->query("CREATE TABLE `".$this->db_prefix."form_letters` (`ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, `email` CHAR(30) NOT NULL, `name` CHAR(30) NOT NULL, `text` TEXT(10000) NOT NULL, `datetime` DATETIME NOT NULL, PRIMARY KEY (`ID`)) COLLATE='utf8mb4_unicode_ci' AUTO_INCREMENT=0");

        return $subscribers_table and $letters_table;

    }

    public function check_table(string $table)
    {

        $query = $this->db->query("SELECT * FROM ".$this->db_name.".".$this->db_prefix.$table." LIMIT 1");

        if ($query !== false) return true;
        else return false;

    }

    public function create_subscriber(string $email)
    {

        return $this->db->query($this->db->prepare("INSERT INTO ".$this->db_name.".".$this->db_prefix."form_subscribers ('email', 'datetime') VALUES ('%s', '%s')", $email, date('Y-m-d H:i:s')));

    }

    public function create_letter(string $email, string $name, string $text)
    {

        return $this->db->query($this->db->prepare("INSERT INTO ".$this->db_name.".".$this->db_prefix."form_letters ('email', 'name', 'text', 'datetime') VALUES ('%s', '%s', '%s', '%s')", $email, $name, $text, date('Y-m-d H:i:s')));

    }

}
