<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "jawapeleket";

$users_table = "users";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Function 1: Fetch All rows from a table
function FetchAll($table) {
    global $conn;
    $table = mysqli_real_escape_string($conn, $table);
    $sql = "SELECT * FROM `$table`";
    $result = mysqli_query($conn, $sql);

    if (!$result) return false;

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Function 2: Fetch specific row(s) with a filter
function FetchSpecific($table, $filter, $rowsNeeded = "*") {
    global $conn;
    $table = mysqli_real_escape_string($conn, $table);
    // 💡 DO NOT escape $filter so you can pass custom stuff like "id = 1" or "username = 'meow'"
    $sql = "SELECT $rowsNeeded FROM `$table` WHERE $filter";
    $result = mysqli_query($conn, $sql);

    if (!$result) return false;

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Function 3: Check if user with username + password exists
function UserCheck($username, $password) {
    global $conn;
	global $users_table;
    $username = mysqli_real_escape_string($conn, $username);
	
    $sql = "SELECT * FROM $users_table WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) == 0) {
        return "Username Salah";
    }

    $user = mysqli_fetch_assoc($result);
    
    // Use password_verify() if you're using password_hash()
    if (password_verify($password, $user['password'])) {
        return $user; // login success, return user info UwU
    }
	
	$test = password_hash($password, PASSWORD_DEFAULT);
	$test2 = $user['password'];
    return "Password Salah!<br>$test<br>$test2"; // login failed nya~
}

function createUser($username, $password) {
	global $conn;
	global $users_table;
    // Hash the password for safety! 🔐
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL meow~
    $stmt = $conn->prepare("INSERT INTO $users_table (username, password) VALUES (?, ?)");
	
    if (!$stmt) {
        return ["success" => false, "error" => "Prepare failed: " . $conn->error];
    }
	
	if (FetchSpecific($users_table, "username = '$username'")) {
		return ["success" => false, "error" => "Username already exists nya~! 😿"];
	}

    // Bind parameters nya~
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute and handle result
    if ($stmt->execute()) {
        return ["success" => true, "message" => "User created successfully~! 🎉"];
    } else {
        return ["success" => false, "error" => "Execute failed: " . $stmt->error];
    }
}

// start session safely
function beginSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Log in a user
function loginUser($username) {
    session_start();
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
}

// Log out a user
function logoutUser() {
    session_start();
    session_unset();
    session_destroy();
}

// Check if user is logged in
function isUserLoggedIn() {
    session_start();
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Get current username
function getCurrentUsername() {
    session_start();
    return $_SESSION['username'] ?? null;
}

function redirectTo($page) {
  header("Location: ../?p=" . urlencode($page));
  exit;
}
?>
