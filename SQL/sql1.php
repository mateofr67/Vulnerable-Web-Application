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
    // Database connection variables
    $servername = "localhost";
    $username = "root";
    $password = "your_secure_password_here"; // Set your database password
    $db = "1ccb8097d0e9ce9f154608be60224c7c"; // Your database name

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $db);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepared statement to prevent SQL Injection
    if (isset($_POST["submit"])) {
        $firstname = $_POST["firstname"];

        // Prepare the SQL query with placeholders
        $stmt = $conn->prepare("SELECT lastname FROM users WHERE firstname = ?");
        
        // Bind the parameter to the prepared statement (s = string)
        $stmt->bind_param("s", $firstname);
        
        // Execute the statement
        $stmt->execute();
        
        // Get the result of the query
        $result = $stmt->get_result();

        // Check if any rows were returned
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo $row["lastname"];
                echo "<br>";
            }
        } else {
            echo "0 results";
        }

        // Close the prepared statement
        $stmt->close();
    }

    // Close the database connection
    mysqli_close($conn);
?>
</body>
</html>
