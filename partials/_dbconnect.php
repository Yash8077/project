<?php
// Define database connection constants
define('server', 'mysql-e5335f3-samyak6117-1410.e.aivencloud.com'); // Corrected the server definition
define('port', '19710');
define('user', 'avnadmin'); // Database username
define('password', 'AVNS_XZ15jOYbriFGCUimmJg'); // Database password
define('database', 'defaultdb'); // Database name
define('ca_cert', _DIR_ . '/../ca.pem'); // Correct the path to your CA certificate

// Initialize the MySQL connection
$conn = mysqli_init();

// Enable SSL (optional, only if you're using SSL/TLS)
mysqli_ssl_set($conn, NULL, NULL, ca_cert, NULL, NULL);

// Connect to the database
if (!mysqli_real_connect($conn, server, user, password, database, port)) {
    die("Error: " . mysqli_connect_error());
}

?>
