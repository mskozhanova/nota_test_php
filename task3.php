<?php

/**
 * Class TableCreator.
 
 */

final class TableCreator
{

    /**
     * TableCreator constructor.
     * Executes the create and fill methods upon class instantiation.
     */

    public function __construct()
    {

        $this->create();
        $this->fill();

    }

    /**
     * create() method.
    
     */

    private function create()
    {
        // Open the SQLite database
       $db = new PDO('sqlite:tests.db');

       $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

       // Create the wiki_sections table if it doesn't exist

       $db->exec('CREATE TABLE IF NOT EXISTS tests (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            script_name  STRING,
            start_time datetime,
            end_time datetime,
            result STRING,
           

        )');

    }


    /**
     * fill() method.
     
     */
    private function fill()
    {
         

        $stmt = $db->prepare('INSERT INTO tests (script_name, start_time, end_time, result) VALUES (?, ?, ?, ?, ?)');

        $stmt->execute($stmt);
       
    }

    /**
     * get() method.
     * Selects data from the Test table based on the criterion.
     * @return array Fetched data from the Test table.
     * @access public
     */
    public function get()
    {
        $result =  SELECT * FROM tests WHERE result IN ('normal', 'success');
        
        return $result; // Placeholder for fetched data
    }
}
