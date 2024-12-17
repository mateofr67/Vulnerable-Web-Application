<!DOCTYPE html>
<html>
<head>
	<title>SQL Injection</title>
	<link rel="shortcut icon" href="../Resources/hmbct.png" />
</head>
<body>

	 <div style="background-color:#c9c9c9;padding:15px;">
      <button type="button" name="homeButton" onclick="location.href='../homepage.html';">Home Page</button>
      <button type="button" name="mainButton" onclick="location.href='sqlmainpage.html';">Main Page</button>
	</div>

	<div align="center">
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" >
		<p>John -> Doe</p>
		First name : <input type="text" name="firstname">
		<input type="submit" name="submit" value="Submit">
	</form>
	</div>
<?php 
// Include configuration file
$config = require_once 'config.php';

// Get database credentials from config file
$servername = $config['db_host'];
$username   = $config['db_user'];
$password   = $config['db_pass'];
$db         = $config['db_name'];

// Connect to the database
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if (isset($_POST["submit"])) {
    $firstname = trim($_POST["firstname"]);

    // Prepared statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT lastname FROM users WHERE firstname = ?");
    if ($stmt) {
        $stmt->bind_param("s", $firstname);
        $stmt->execute();
        $result = $stmt->get_result();

        // Display results
        if ($result->num_rows > 0) {
            echo "<div align='center'><h3>Results:</h3>";
            while ($row = $result->fetch_assoc()) {
                echo htmlspecialchars($row["lastname"]) . "<br>";
            }
            echo "</div>";
        } else {
            echo "<div align='center'>No results found.</div>";
        }
        $stmt->close();
    } else {
        echo "Error in query preparation: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
</body>
</html>
