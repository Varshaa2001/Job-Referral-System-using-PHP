<?php
$servername = "localhost";  
$username = "root";      
$password = "Varshaa@114";    
$dbname = "refer";          

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $action = $_POST['action'];

        // Process the action
        switch ($action) {
            case 'add':
                $referrerName = $_POST['dropdown'];
                $employee_type = $_POST['employee_type'];
                $referralName = $_POST['username'];
                $username = $_POST["username"];
                $email = $_POST["email"];
                $job_title = $_POST["job_title"];
                $address = $_POST["address"];
                $phone_number = $_POST["phone_number"];
    
                if(!empty($employee_type) && !empty($referrerName) && !empty($referralName) && !empty($username) && !empty($email) && !empty($job_title) && !empty($address) && !empty($phone_number))
                {
                    addPerson($conn, $employee_type, $referrerName, $referralName, $username, $email, $job_title, $address, $phone_number);
                    echo "Successfully added $referralName.";
                    $error_status = "false";
                }
                else{
                    $error_status = "true";
                    echo '<script>alert("Warning: Insufficient data entry to Add Person");</script>';
                   
                }
                break;

            case 'dismiss':
                $personName = $_POST['dismiss_dropdown'];
                if(isset($personName) && ($personName != "Not_selected")) {
                    dismissPerson($conn, $personName);
                    echo "Successfully removed $personName.";
                    $error_status = "false";
                }
                else {
                    $error_status = "true";
                    echo '<script>alert("Warning: Insufficient data entry to Dismiss Person");</script>';
                }
                break;

            default:
                // Invalid action
                echo "Invalid action.";
                exit;
        }

        // Display points
        if($error_status == "false"){
            displayPoints($conn);
            echo '<br><br><button><a href="index.php">Go back to home page</a></button>';
        }
        else{
            echo '<span style="font-size: larger;">Form submission failed !</span><br>';
            echo '<br><br><button><a href="index.php">Go back to home page</a></button>';
        }
} else {
    echo "Invalid request method.<br>";
    echo '<br><br><button><a href="index.php">Go back to home page</a></button>';
    exit;
}

function addPerson($conn, $employee_type, $referrerName, $referralName, $username, $email, $job_title, $address, $phone_number) {
    
    if ($referrerName != "None") {
        $referrerId = getPersonId($conn, $referrerName);
        $sql = "INSERT INTO user_data (name, points, username, email, job_title, address, phone_number, employee_type, referrer_id) VALUES ('$referralName', 0, '$username', '$email', '$job_title', '$address', '$phone_number', '$employee_type', $referrerId)";
        $conn->query($sql);

        $sql = "UPDATE user_data SET points = points + 10 WHERE id = '$referrerId'";
        $conn->query($sql);

        $ref_id = getReferrer_id($conn, $referrerId);

        $sql = "UPDATE user_data SET points = points + 20 WHERE id = '$ref_id'";
        $conn->query($sql);
    } else {
        $sql = "INSERT INTO user_data (name, points, username, email, job_title, address, phone_number, employee_type, referrer_id) VALUES ('$referralName', 0, '$username', '$email', '$job_title', '$address', '$phone_number', '$employee_type', 0)";
        $conn->query($sql);
    }
}

function dismissPerson($conn, $personName) {

    $personId = getPersonId($conn, $personName);
    $referrer_id = getReferrer_id_withName($conn, $personName);

    if ($referrer_id !== NULL && $referrer_id !== 0) {
        $sql = "UPDATE user_data SET points = points - 7 WHERE id = '$referrer_id'";
        $conn->query($sql);

        $ref_ID = getReferrer_id($conn, $referrer_id);
        $sql = "UPDATE user_data SET points = points - 8 WHERE id = '$ref_ID'";
        $conn->query($sql);
    }
    $sql = "DELETE FROM user_data WHERE id = '$personId'";
    $conn->query($sql);
}

function getPersonId($conn, $personName) {
    $sql = "SELECT id FROM user_data WHERE name = '$personName'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    } else {
        $sql = "INSERT INTO user_data (name) VALUES ('$personName')";
        $conn->query($sql);
        return $conn->insert_id;
    }
}
function getReferrer_id($conn, $id) {
    $result = $conn->query("SELECT referrer_id FROM user_data WHERE id = $id");

    if ($result && $result->num_rows > 0) {
        $referrer = $result->fetch_assoc();
        return $referrer['referrer_id'];
    } else {
        return null;  
    }
}


function getReferrer_id_withName($conn, $name) {
    $result = $conn->query("SELECT referrer_id FROM user_data WHERE name = '$name'");
    $referrer = $result->fetch_assoc();
    return $referrer['referrer_id'];
}

function displayPoints($conn) {
    $sql = "SELECT name, points FROM user_data";
    $result = $conn->query($sql);

    echo "<h2>Points Summary</h2>";
    echo "<ul>";

    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['name']}: {$row['points']} points</li>";
    }
    echo "</ul>";
}

$conn->close();
?>
