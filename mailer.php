// Import necessary PHPMailer classes.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include the Composer autoloader to load the PHPMailer classes.
require __DIR__ . "/vendor/autoload.php";

// Create a new instance of PHPMailer and enable exceptions.
$mail = new PHPMailer(true);

// Uncomment to enable verbose debug output.
// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

// Set the mailer to use SMTP (Simple Mail Transfer Protocol).
$mail->isSMTP();
$mail->SMTPAuth = true; // Enable SMTP authentication.

// SMTP server settings.
$mail->Host = "smtp.example.com"; // Specify the SMTP server to use.
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption.
$mail->Port = 587; // TCP port to connect to (587 is commonly used for STARTTLS).
$mail->Username = "your-user@example.com"; // SMTP username.
$mail->Password = "your-password"; // SMTP password.

// Specify that the email should be sent in HTML format.
$mail->isHtml(true);

// Return the configured PHPMailer instance.
return $mail;
