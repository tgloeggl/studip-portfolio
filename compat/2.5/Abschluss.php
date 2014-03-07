<?php
namespace Portfolio;

class Abschluss extends \SimpleORMap
{
    function __construct()
    {
        $this->db_table = 'abschluss';
        
        parent::__construct();
    }
}