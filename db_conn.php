<?php

class DBConnection
{
    private $host;
    private $port;
    private $database;
    private $user;
    private $password;
    private $connection;

    public function __construct($host, $database, $user, $password)
    {
        $this->host = $host;
        $this->port = 3306;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
    }

    public function connect()
    {
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->database}";
            $this->connection = new PDO($dsn, $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected to the database successfully!";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function disconnect()
    {
        $this->connection = null;
        echo "Disconnected from the database.";
    }

    public function insertAppointment($name, $prename, $email, $phone, $address, $date, $time)
    {
        $sql = "CREATE TABLE IF NOT EXISTS customer (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            prename VARCHAR(255),
            email VARCHAR(255),
            phone VARCHAR(255),
            address VARCHAR(255)
        )";
        $this->connection->exec($sql);

        $sql = "INSERT INTO customer (name, prename, email, phone, address) VALUES (:name, :prename, :email, :phone, :address)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':prename', $prename);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $res = $stmt->execute();
        print_r($res);
        echo "Customer successfully inserted!";

        $sql = "CREATE TABLE IF NOT EXISTS appointments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_id INT,
            date DATE,
            time TIME
        )";
        $this->connection->exec($sql);

        $sql = "INSERT INTO appointments (date, time, customer_id) VALUES (:date, :time, :customer_id)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':customer_id', $res);
        $stmt->execute();
        echo "Appointment successfully inserted!";
    }

    public function getAppointments($id = null)
    {
        $sql = "SELECT appointments.*, customer.name, customer.prename, customer.email, customer.phone, customer.address
            FROM appointments
            JOIN customer ON customer.id = appointments.customer_id";
        if ($id) {
            $sql .= " WHERE appointments.id = :id";
        }
        $stmt = $this->connection->prepare($sql);
        if ($id) {
            $stmt->bindParam(':id', $id);
        }
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
}
