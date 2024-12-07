<?php
// E-posta adresi ve başlık ayarları
$to = "support@mulebu.com"; // Alıcı e-posta adresi
$subject_prefix = "Contact Form: "; // E-posta konu ön eki

// Formdan gelen verileri al
$name = isset($_POST['cf-name']) ? strip_tags(trim($_POST['cf-name'])) : '';
$email = isset($_POST['cf-email']) ? strip_tags(trim($_POST['cf-email'])) : '';
$subject = isset($_POST['cf-subject']) ? strip_tags(trim($_POST['cf-subject'])) : 'No Subject';
$message = isset($_POST['cf-message']) ? strip_tags(trim($_POST['cf-message'])) : '';
$botcheck = isset($_POST['cf-botcheck']) ? $_POST['cf-botcheck'] : '';

// Bot kontrolü
if (!empty($botcheck)) {
    echo json_encode(['status' => 'error', 'message' => 'Bot detected.']);
    exit;
}

// E-posta adresi doğrulama
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
    exit;
}

// E-posta içeriği
$body = "Name: $name\n";
$body .= "Email: $email\n";
$body .= "Subject: $subject\n\n";
$body .= "Message:\n$message\n";

// E-posta başlıkları
$headers = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";

// E-postayı gönder
if (mail($to, $subject_prefix . $subject, $body, $headers)) {
    echo json_encode(['status' => 'success', 'message' => 'Your message has been sent successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to send your message. Please try again later.']);
}
?>
