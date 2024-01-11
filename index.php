<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral System</title>
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral System</title>
    <style>
        body {
            font-family: 'Verdana', sans-serif;
            background-color: #f0f9ff; 
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffff; 
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 10px;
        }

        #referrerSection {
            display: block;
        }

        #dismissPersonSection {
            display: none;
        }
        input[type="submit"] {
            width: 30%; 
            padding: 10px; 
            font-size: 14px; 
            background-color: #4CAF50;
            color: #fff; 
            display: block; 
            margin: 0 auto; 
        }
    </style>
    <script>
        function toggleSections() {
            var actionSelect = document.getElementById('action');
            var referrerSection = document.getElementById('referrerSection');
            var dismissPersonSection = document.getElementById('dismissPersonSection');

            if (actionSelect.value === 'add') {
                referrerSection.style.display = 'block';
                dismissPersonSection.style.display = 'none';
            } else if (actionSelect.value === 'dismiss') {
                referrerSection.style.display = 'none';
                dismissPersonSection.style.display = 'block';
            }
        }
    </script>
</head>

<body>
    <h1>Job Application Form</h1>

    <form id="applicationForm" action="process.php" method="post" novalidate>
        <label for="action">Action:</label>
        <select name="action" id="action" required onchange="toggleSections()">
            <option value="add">Add Person</option>
            <option value="dismiss">Dismiss\Remove Person</option>
        </select>

        <!-- Section for Add Person -->
        <div id="referrerSection">
            <label for="employee_type">Work experience:</label>
            <select name="employee_type" id="employee_type" required>
                <option value="Experienced">Experienced</option>
                <option value="Fresher">Fresher</option>
            </select>

            <label for="job_title">Job Applying For:</label>
            <select name="job_title" required>
                <option value="web_developer">Web Developer</option>
                <option value="fullstack_developer">Fullstack Developer</option>
                <option value="devops_engineer">DevOps Engineer</option>
            </select>

            <label for="username">Username:</label>
            <input type="text" name="username">

            <label for="email">Email:</label>
            <input type="email" name="email">

            <label for="phone_number">Phone Number:</label>
            <input type="tel" name="phone_number">

            <label for="address">Address:</label>
            <input type="text" name="address">

            <label for="dropdown">Select Referrer:</label>
            <?php
            // Include database connection or use a separate file for it

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

            // Fetch data from the database
            $sql = "SELECT id, name FROM user_data";
            $result = $conn->query($sql);

            // Check if records exist
            if ($result->num_rows > 0) {
                echo '<select name="dropdown" id="dropdown">';
                echo '<option value="None">None</option>';
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                }
                echo '</select>';
            } else {
                echo '<select name="dropdown" id="dropdown"><option value="None">None</option></select>';
            }

            // Close the database connection
            $conn->close();
            ?>
        </div>

        <!-- Section for Dismiss Person -->
        <div id="dismissPersonSection" style="display:none;">
            <label for="dismiss_dropdown">Select Employee Name:</label>
            <?php
            // Include database connection or use a separate file for it

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

            // Fetch data from the database
            $sql = "SELECT id, name FROM user_data";
            $result = $conn->query($sql);

            // Check if records exist
            if ($result->num_rows > 0) {
                echo '<select name="dismiss_dropdown" id="dismiss_dropdown">';
                // Create HTML dropdown list...
                echo '<option value="Not_selected">Not_selected</option>';
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                }
                echo '</select>';
            } else {
                // If no records found, display "None"
                echo '<select name="dismiss_dropdown" id="dismiss_dropdown"><option value="None">None</option></select>';
            }

            // Close the database connection
            $conn->close();
            ?>
        </div>

        <input type="submit" value="Submit">
    </form>

</body>

</html>