<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "pctvs_system";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit();
}

$input = json_decode(file_get_contents("php://input"), true);
$email = trim($input["email"] ?? "");
$password = $input["password"] ?? "";

if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Email and password are required"]);
    exit();
}

// fetch user by email
$sql = "SELECT id, name, email, password, role FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // hashed password check
    if (password_verify($password, $row["password"])) {
        echo json_encode([
            "success" => true,
            "message" => "Login successful",
            "user" => [
                "id"    => $row["id"],
                "name"  => $row["name"],
                "email" => $row["email"],
                "role"  => $row["role"]
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User not found"]);
}

$stmt->close();
$conn->close();