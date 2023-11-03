<?php
/**
 * The TableCreator class is used to create and manipulate a table named 'Test' with specific fields
 * and data within a database. It prevents subclassing and provides methods to create the table,
 * fill it with random data, and select data based on certain criteria.
 */
final class TableCreator {
    private $db;

    /**
     * TableCreator constructor.
     *
     * The constructor initializes the database connection and executes the 'create' and 'fill' methods.
     */
    public function __construct() {
        // Initialize the database connection
        $this->db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
        
        // Execute the 'create' method to create the table
        $this->create();
        
        // Execute the 'fill' method to fill the table with random data
        $this->fill();
    }

    /**
     * Creates the 'Test' table with specific fields.
     * Accessible only within the class.
     */
    private function create() {
        $createTableSQL = "
            CREATE TABLE IF NOT EXISTS Test (
                id INT AUTO_INCREMENT PRIMARY KEY,
                script_name VARCHAR(25),
                start_time DATETIME,
                end_time DATETIME,
                result ENUM('normal', 'illegal', 'failed', 'success')
            )
        ";

        $this->db->exec($createTableSQL);
    }

    /**
     * Fills the 'Test' table with random data.
     * Accessible only within the class.
     */
    private function fill() {
        $insertDataSQL = "INSERT INTO Test (script_name, start_time, end_time, result) VALUES (:script_name, :start_time, :end_time, :result)";
        $stmt = $this->db->prepare($insertDataSQL);
        
        // Generate random data and insert into the table
        for ($i = 1; $i <= 10; $i++) {
            $scriptName = "Script{$i}";
            $startTime = date('Y-m-d H:i:s', rand(1, time()));
            $endTime = date('Y-m-d H:i:s', rand(time(), time() + 3600));
            $result = ['normal', 'illegal', 'failed', 'success'][array_rand(['normal', 'illegal', 'failed', 'success'])];

            $stmt->execute(['script_name' => $scriptName, 'start_time' => $startTime, 'end_time' => $endTime, 'result' => $result]);
        }
    }

    /**
     * Selects data from the 'Test' table based on the 'result' criteria.
     * Accessible from outside the class.
     *
     * @return array An array of rows matching the criteria.
     */
    public function get() {
        $getResultsSQL = "SELECT * FROM Test WHERE result IN ('normal', 'success')";
        $stmt = $this->db->query($getResultsSQL);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
