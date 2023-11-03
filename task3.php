<?php

/**
 * Class TableCreator
 * This class is used to create and manipulate a database table called Test.
 *
 * @final
 */
final class TableCreator
{
    private $pdo;

    /**
     * TableCreator constructor.
     * It initializes the database connection and calls the create and fill methods.
     */
    public function __construct()
    {

        // Get database credentials from environment variables
        $host = getenv('DB_HOST');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        $database = getenv('DB_NAME');

        // Initialize the database connection
        $this->pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);

        // Create the Test table
        $this->create();

        // Fill the Test table with random data
        $this->fill();
    }

    /**
     * Creates the Test table with specified fields.
     * This method is accessible only within the class.
     */
    private function create()
    {
        $query = "CREATE TABLE Test (
            id INT AUTO_INCREMENT PRIMARY KEY,
            script_name VARCHAR(25),
            start_time DATETIME,
            end_time DATETIME,
            result ENUM('normal', 'illegal', 'failed', 'success')
        )";

        $this->pdo->exec($query);
    }

    /**
     * Fills the Test table with random data.
     * This method is accessible only within the class.
     */
    private function fill()
    {
        $scriptNames = ['Script 1', 'Script 2', 'Script 3'];
        $results = ['normal', 'illegal', 'failed', 'success'];

        $stmt = $this->pdo->prepare("INSERT INTO Test (script_name, start_time, end_time, result) VALUES (?, ?, ?, ?)");

        for ($i = 0; $i < 10; $i++) {
            $scriptName = $scriptNames[array_rand($scriptNames)];
            $startTime = date('Y-m-d H:i:s', strtotime("-$i hours"));
            $endTime = date('Y-m-d H:i:s', strtotime("-$i hours + 30 minutes"));
            $result = $results[array_rand($results)];

            $stmt->execute([$scriptName, $startTime, $endTime, $result]);
        }
    }

    /**
     * Selects data from the Test table based on the specified criteria.
     * This method is accessible from outside the class.
     *
     * @return array An array of selected data rows.
     */
    public function get()
    {
        $query = "SELECT * FROM Test WHERE result IN ('normal', 'success')";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
