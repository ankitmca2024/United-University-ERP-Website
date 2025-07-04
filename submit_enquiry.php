$php_errormsg = '';
$php_errorfile = '';
$php_errorline = '';
$php_errorcontext = '';
$php_error = '';
$php_errorlevel = 0;
$php_errorfile = 'C:\Users\kingr\OneDrive\Desktop\ERP\submit_enquiry.php';
$php_errorline = 0;
$php_errorcontext = array();
$php_errorlevel = E_WARNING;
$php_errormsg = 'include_once(./config.php): Failed to open stream: No such file or directory';
$php_error = 'include_once(./config.php): Failed to open stream: No such file or directory';
if (!file_exists('./config.php')) {
    echo "<h1>Error: Configuration file not found</h1>";
    echo "<p>Please ensure that the 'config.php' file exists in the same directory as this script.</p>";
    exit;
}   
include_once('./config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';
    $errors = [];
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    if (empty($phone) || !preg_match('/^\d{10}$/', $phone)) {
        $errors[] = 'Valid phone number is required.';
    }
    if (empty($message)) {
        $errors[] = 'Message is required.';
    }
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO enquiries (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $message);
        if ($stmt->execute()) {
            echo "<h1>Enquiry Submitted Successfully</h1>";
            echo "<p>Thank you for your enquiry, $name. We will get back to you shortly.</p>";
        } else {
            echo "<h1>Error: Enquiry Submission Failed</h1>";
            echo "<p>Sorry, there was an error submitting your enquiry. Please try again later.</p>";
        }
    } else {
        echo "<h1>Error: Invalid Input</h1>";
        echo "<p>Please fix the following errors:</p>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
}   
else {
    echo "<h1>Error: Invalid Request Method</h1>";
    echo "<p>This script only accepts POST requests.</p>";
}   
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiry Submission</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Enquiry Submission</h1>
        <p>Your enquiry has been submitted successfully.</p>
    </div>
</body>
</html>