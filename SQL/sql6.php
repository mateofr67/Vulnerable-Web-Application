<!DOCTYPE html>
<html>
<head>
	<title>SQL Injection</title>
</head>
<body>

	 <div style="background-color:#c9c9c9;padding:15px;">
      <button type="button" name="homeButton" onclick="location.href='../homepage.html';">Home Page</button>
      <button type="button" name="mainButton" onclick="location.href='sqlmainpage.html';">Main Page</button>
    </div>
    <div align="center">
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="get" >
		<p>Give me book's number and I give you...</p>
		Book's number : <input type="text" name="number">
		<input type="submit" name="submit" value="Submit">
	</form>
	</div>
	<!--Admin password is in the secret table. I hope, anyone doesn't see it.-->
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
if (isset($_GET["submit"])) {
    $number = $_GET['number'];

    // Validación del número de entrada para evitar inyección SQL (solo números)
 if (preg_match("/\D/", $number)) {
    echo "Invalid input. Please enter a valid number.";
    exit;
}

    // Usar una consulta preparada para evitar la inyección SQL
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
            echo "<hr><pre>There is a book with this index.</pre>";
        } else {
            echo "Not found!";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error in query preparation: " . $conn->error;
    }
}

// Cerrar la conexión
$conn->close();
?>

</body>
</html>
