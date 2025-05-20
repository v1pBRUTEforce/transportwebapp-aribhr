<?php

namespace App\Controllers;

use __PHP_Incomplete_Class;
use App\Controller;
use App\Models\Logger;
use App\Models\Database;

class UserController extends Controller
{

    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function login()
{
    $error = [];
    
    if (isset($_POST["submit"])) {
        // Validate input
        $username = $_POST["username"] ?? '';
        $password = $_POST["password"] ?? '';
    
        if (empty($username) || empty($password)) {
            $error[] = "Username and password are required.";
        } else {
            // Sanitize inputs
            $username = htmlspecialchars($username);
            $password = htmlspecialchars($password);
    
            // Query to check user credentials
            $query = "SELECT nom FROM users WHERE nom = :username AND photo = :password";
            $params = [
                ':username' => $username,
                ':password' => $password // Example: Use proper hashing (e.g., bcrypt) in production
            ];
    
            try {
                // Execute query
                $stmt = $this->db->query($query, $params);
                
                // Fetch user data
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);
    
                if ($user) {
                    // Start session and store user data
                    session_start();
                    $_SESSION['user'] = $user;
    
                    // Check session timeout
                    $session_timeout = 15; // 30 minutes
                    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_timeout)) {
                        // Session expired, destroy session and redirect to login page
                        session_unset();     // Unset all session variables
                        session_destroy();   // Destroy the session
                        header("Location: login.php"); // Redirect to login page
                        exit;
                    }
                    
                    // Update last activity timestamp
                    $_SESSION['LAST_ACTIVITY'] = time(); 
    
                    // Redirect to the profile page or dashboard
                    header("Location: /profile");
                    exit();
                } else {
                    $error[] = "Invalid username or password.";
                }
            } catch (\PDOException $e) {
                $error[] = "Database error: " . $e->getMessage();
            }
        }
    }
    
    // Render login page with errors
    $this->render('login', ["error" => $error]);
}

public function logout()
{
    // Start session (if not started already)
    session_start();

    // Unset all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    // Optionally, redirect to a login page or homepage
    header("Location: /login");
    exit();
}


    public function register()
    {
        $error = [];
        $test = false;

        if (isset($_POST["submit"])) {
            // Validate input fields
            if (
                !isset($_POST["firstname"]) || empty($_POST["firstname"]) ||
                !isset($_POST["lastname"]) || empty($_POST["lastname"]) ||
                !isset($_POST["email"]) || empty($_POST["email"]) ||
                !isset($_POST["birthdate"]) || empty($_POST["birthdate"]) ||
                !isset($_POST["gender"]) || empty($_POST["gender"]) ||
                !isset($_POST["role"]) || empty($_POST["role"]) ||
                !isset($_POST["driver_license"]) || empty($_POST["driver_license"]) ||
                !isset($_POST["cin"]) || empty($_POST["cin"]) ||
                !isset($_POST["address"]) || empty($_POST["address"]) ||
                !isset($_POST["password"]) || empty($_POST["password"])
            ) {
                $error[] =  "Error: All fields are required.";
            } else {
                try {
                    // Sanitize input data
                    $firstname = htmlspecialchars($_POST["firstname"]);
                    $lastname = htmlspecialchars($_POST["lastname"]);
                    $email = htmlspecialchars($_POST["email"]);
                    $address = htmlspecialchars($_POST["address"]);
                    $birthdate = htmlspecialchars($_POST["birthdate"]);
                    $gender = htmlspecialchars($_POST["gender"]);
                    $role = htmlspecialchars($_POST["role"]);
                    $driverLicense = htmlspecialchars($_POST["driver_license"]);
                    $cin = htmlspecialchars($_POST["cin"]);
                    $password = htmlspecialchars($_POST["password"]);

                    // Prepare SQL query
                    $query = "INSERT INTO `users` (`nom`, `prenom`, `sexe`, `datenai`, `permit`, `cin`, `address`, `role`, `photo`) 
                              VALUES (:fn, :ln, :gender, :birthdate, :driverLicense, :cin, :address, :role, :photo)";

                    // Bind parameters
                    $params = [
                        ":fn" => $firstname,
                        ":ln" => $lastname,
                        ":gender" => $gender,
                        ":birthdate" => $birthdate,
                        ":driverLicense" => $driverLicense,
                        ":cin" => $cin,
                        ":address" => $address,
                        ":role" => $role,
                        ":photo" => $password // Assuming `photo` corresponds to `password`
                    ];

                    // Execute your SQL query
                    $stmt = $this->db->query($query, $params);

                    // Check if the query was successful
                    if ($stmt) {
                        // Registration successful
                        $error[] = "User registered successfully.";
                        $test = true;
                    } else {
                        // Failed to insert user
                        $error[] = "Failed to register user.";
                    }
                } catch (\PDOException $e) {
                    // Handle PDO exception
                    $error[] = "PDO Error: " . $e->getMessage();
                }
                if ($test) {
                    header("Location: /profile");
                    echo "Redirecting..."; // Check if this message is echoed
                    exit();
                } else {

                    $this->render('register', ["error" => $error]);
                }
            }
        } else {
            $this->render('register', ["error" => $error]);
        }

        // Render the register view with errors (if any)

    }







    public function Formulaire()
    {
        //$this->render('login');
    }
}
