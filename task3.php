<?
// Your code is here:
<?php
/**
 * Class TableCreator.
 * This class provides methods to create a table, fill it with random data, and retrieve specific data based on criteria.
 */
final class TableCreator
{
    private $pdo;

    /**
     * TableCreator constructor.
     * Executes the create and fill methods.
     */
    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=your_host;dbname=your_database", 'your_username', 'your_password');
        $this->create();
        $this->fill();
    }

    /**
     * Creates a table 'Test' with specified fields.
     * Accessible only within the class.
     */
    private function create()
    {
        $sql = "CREATE TABLE Test (
            id INT AUTO_INCREMENT PRIMARY KEY,
            script_name VARCHAR(25),
            start_time DATETIME,
            end_time DATETIME,
            result ENUM('normal', 'illegal', 'failed', 'success')
        )";

        $this->pdo->exec($sql);
    }

    /**
     * Fills the 'Test' table with random data.
     * Accessible only within the class.
     */
    private function fill()
    {
        $resultOptions = ['normal', 'illegal', 'failed', 'success'];

        $stmt = $this->pdo->prepare("INSERT INTO Test (script_name, start_time, end_time, result) VALUES (:script_name, :start_time, :end_time, :result)");

        for ($i = 0; $i < 10; $i++) {
            $startTime = date("Y-m-d H:i:s", mt_rand(1262055681, 1609454481));
            $endTime = date("Y-m-d H:i:s", mt_rand(strtotime($startTime), time()));
            $scriptName = "Script " . ($i + 1);
            $result = $resultOptions[array_rand($resultOptions)];

            $stmt->bindParam(':script_name', $scriptName);
            $stmt->bindParam(':start_time', $startTime);
            $stmt->bindParam(':end_time', $endTime);
            $stmt->bindParam(':result', $result);

            $stmt->execute();
        }
    }

    /**
     * Selects data from the 'Test' table based on the 'result' field.
     * Accessible from outside the class.
     * @return array Fetched data based on the criteria.
     */
    public function get()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Test WHERE result IN ('normal', 'success')");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
