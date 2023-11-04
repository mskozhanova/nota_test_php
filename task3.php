/**
 * The TableCreator class creates and manages the 'Test' table, and provides methods for populating and retrieving data.
 *
 * @final This class is marked as 'final' to prevent inheritance.
 */
final class TableCreator
{
    private $pdo; // PDO instance for database connection

    /**
     * Constructor for the TableCreator class.
     *
     * This constructor initializes the database connection and calls the 'create' and 'fill' methods.
     *
     * @param string $databaseHost     Database host name.
     * @param string $databaseName     Database name.
     * @param string $databaseUser     Database user.
     * @param string $databasePassword Database user's password.
     */
    public function __construct($databaseHost, $databaseName, $databaseUser, $databasePassword)
    {
        try {
            $this->pdo = new PDO("mysql:host=$databaseHost;dbname=$databaseName", $databaseUser, $databasePassword);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->create();
            $this->fill();
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Create the 'Test' table with specified fields.
     *
     * This method is accessible only within the class.
     */
    private function create()
    {
        $sql = "CREATE TABLE IF NOT EXISTS Test (
            id INT AUTO_INCREMENT PRIMARY KEY,
            script_name VARCHAR(25),
            start_time DATETIME,
            end_time DATETIME,
            result ENUM('normal', 'illegal', 'failed', 'success')
        )";

        $this->pdo->exec($sql);
    }

    /**
     * Fill the 'Test' table with random data.
     *
     * This method is accessible only within the class.
     */
   private function fill()
{
    $scriptNames = ["ScriptA", "ScriptB", "ScriptC", "ScriptD", "ScriptE"];
    $results = ["normal", "illegal", "failed", "success"];

    $stmt = $this->pdo->prepare("INSERT INTO Test (script_name, start_time, end_time, result) VALUES (?, ?, ?, ?");

    for ($i = 0; $i < 50; $i++) {
        $scriptName = $scriptNames[array_rand($scriptNames)];
        $startTime = date("Y-m-d H:i:s", strtotime("-" . mt_rand(1, 30) . " days"));
        $endTime = date("Y-m-d H:i:s", strtotime("-" . mt_rand(0, 5) . " days"));
        $result = $results[array_rand($results)];

        $stmt->execute([$scriptName, $startTime, $endTime, $result]);
    }
}

    /**
     * Retrieve data from the 'Test' table based on the 'result' criterion.
     *
     * This method is accessible from outside the class.
     *
     * @param string $criterion The result criterion ('normal' or 'success').
     *
     * @return array An array of rows that match the criterion.
     */
    public function get($criterion)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Test WHERE result = ?");
        $stmt->execute([$criterion]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Usage:
// $tableCreator = new TableCreator('your_database_host', 'your_database_name', 'your_database_user', 'your_database_password');
// $normalResults = $tableCreator->get('normal');
