<?php

class ConnectionFactory
{
	public static function CreateConnection()
	{
                $servername = "localhost";
                $username = "admin";
                $password = "xxxx";
                $db = "dsgvo";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $db);

                if ($conn == false)
                        die("Connection failed: " . mysqli_connect_error());

		return $conn;
	}
}

?>
