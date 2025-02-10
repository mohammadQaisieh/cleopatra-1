<!DOCTYPE html>
<html lang="en">
<?php 
    include("./config.php");
    session_start();

    // Include PHPMailer classes
    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // User is not logged in, redirect to login page
        header("Location: login.php");
        exit();
    }

    // Initialize variables for the contact form
    $name = '';
    $email = '';
    $subject = '';
    $message = '';
    $error = '';
    $success = '';

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve and sanitize user input
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        // Basic validation
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $error = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        } else {
            // Proceed to send the email using PHPMailer
            $mail = new PHPMailer; //From email address and name 
            $mail->From = $email; 
            $mail->FromName = $name; //To address and name 
            $mail->addAddress("recepient1@example.com", "Recepient Name");//Recipient name is optional
            $mail->isHTML(true); 
            $mail->Subject = "Subject Text"; 
            $mail->Body = "<i>Mail body in HTML</i>";
            if(!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo; 
            } 
            else { 
                echo "Message has been sent successfully"; 
            }
        connect();
        $stmt = "INSERT INTO contacts (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message');";
        print_r($stmt);
        $results = query($stmt);
        check($results);
    
    }
}
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
  <link rel="shortcut icon" href="./img/fav.png" type="image/x-icon">  
  <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">  
  <title>Welcome To Cleopatra</title>
  
</head>
<body class="bg-gray-100">

<!-- Test database connection -->
<?php 
// connect();
// $results = query("SELECT * FROM customers;");
// while ($row = mysqli_fetch_array($results)) {
//     echo "<pre>";
//     print_r($row);
//     echo "</pre>";
// }
?>  


<?php 
   include("./navbar.php");
?>

<!-- Start wrapper -->
<div class="h-screen flex flex-row flex-wrap">

<?php 
   include("./sidebar.php");
?>


  <!-- Start content -->
  <div class="bg-gray-100 flex-1 p-6 md:mt-16"> 

    <!-- Contact Us Form Start -->
    <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-semibold mb-6 text-center">Contact Us</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger mb-4">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success mb-4">
                <?= htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form action="index.php" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name<span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" class="w-full px-3 py-2 border rounded" required value="<?= htmlspecialchars($name); ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email<span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded" required value="<?= htmlspecialchars($email); ?>">
            </div>
            <div class="mb-4">
                <label for="subject" class="block text-gray-700">Subject<span class="text-red-500">*</span></label>
                <input type="text" name="subject" id="subject" class="w-full px-3 py-2 border rounded" required value="<?= htmlspecialchars($subject); ?>">
            </div>
            <div class="mb-4">
                <label for="message" class="block text-gray-700">Message<span class="text-red-500">*</span></label>
                <textarea name="message" id="message" rows="5" class="w-full px-3 py-2 border rounded" required><?= htmlspecialchars($message); ?></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 transition duration-300">Send Message</button>
            </div>
        </form>
    </div>
    <!-- Contact Us Form End -->

  </div>
  <!-- End content -->

</div>
<!-- End wrapper -->

<!-- Script -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="js/scripts.js"></script>
<!-- End script -->

</body>
</html>
