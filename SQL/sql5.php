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
		<p>Give me book's number and I give you book's name in my library.</p>
		Book's number : <input type="text" name="number">
		<input type="submit" name="submit" value="Submit">
		<!--<p>You hacked me again?
			   But I updated my code
			</p>
		-->
	</form>
	</div>

<?php
// Incluir el archivo de configuración de credenciales
$config = require_once 'config.php';

// Obtener las credenciales de la base de datos
$servername = $config['db_host'];
$username = $config['db_user'];
$password = $config['db_pass'];
$db = $config['db_name'];

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $db);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado
if (isset($_POST["submit"])) {
    $number = $_POST['number'];

    // Evitar inyección SQL: Validación del número de entrada
    if (preg_match("/[^0-9]/", $number)) {
        echo "Invalid input. Please enter a valid number.";
        exit;
    }

    // Usar una consulta preparada para prevenir la inyección SQL
    $stmt = $conn->prepare("SELECT bookname, authorname FROM books WHERE number = ?");
    if ($stmt) {
        // Vincular el parámetro (i = entero)
        $stmt->bind_param("i", $number);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $result = $stmt->get_result();

        // Verificar si se encontraron resultados
        if ($result->num_rows > 0) {
            // Mostrar los resultados
            while ($row = $result->fetch_assoc()) {
                echo "<hr>";
                echo $row['bookname'] . " ----> " . $row['authorname'];
            }
        } else {
            echo "0 result";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}

// Cerrar la conexión
$conn->close();
?>


</body>
</html>
