<?php

/**
 * Simple Email Server
 * 
 * A lightweight PHP email server using PHPMailer for sending emails via SMTP.
 * 
 * @author Vasco Martins
 * @license MIT
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



// Configuration from environment variables or defaults
$config = [
    'host' =>  '',
    'username' =>  '',
    'password' =>  '',
    'port' => (int)( 465),
    'from' =>  '',
    'encryption' =>  'ssl',
    'require_https' => filter_var( 'true', FILTER_VALIDATE_BOOLEAN),
];

require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';


// Validate required configuration
$requiredConfig = ['host', 'username', 'password', 'from'];
foreach ($requiredConfig as $key) {
    if (empty($config[$key])) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Server configuration error',
            'message' => "Missing required configuration: {$key}"
        ]);
        exit;
    }
}

// Enforce HTTPS if required
if ($config['require_https'] && empty($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] != 443) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'HTTPS required']);
    exit;
}

// Set JSON response header
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use POST.']);
    exit;
}

// Parse incoming JSON
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

// Validate JSON input
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Invalid JSON',
        'message' => json_last_error_msg()
    ]);
    exit;
}

// Validate required fields
if (!$data || !isset($data['to'], $data['subject'], $data['body'])) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Missing required fields',
        'required' => ['to', 'subject', 'body']
    ]);
    exit;
}

// Validate and sanitize email address
$to = filter_var(trim($data['to']), FILTER_VALIDATE_EMAIL);
if (!$to) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email address']);
    exit;
}

// Sanitize subject and body
$subject = trim($data['subject']);
$body = $data['body'];

if (empty($subject)) {
    http_response_code(400);
    echo json_encode(['error' => 'Subject cannot be empty']);
    exit;
}

// Determine encryption type
$encryption = strtolower($config['encryption']);
$smtpSecure = PHPMailer::ENCRYPTION_STARTTLS;
if ($encryption === 'ssl' || $encryption === 'smtps') {
    $smtpSecure = PHPMailer::ENCRYPTION_SMTPS;
}

// Send email
try {
    $mail = new PHPMailer(true);
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = $config['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['username'];
    $mail->Password = $config['password'];
    $mail->SMTPSecure = $smtpSecure;
    $mail->Port = $config['port'];
    
    // Enable verbose debug output (optional, disable in production)
    // $mail->SMTPDebug = 2;
    // $mail->Debugoutput = 'error_log';
    
    // Email content
    $mail->setFrom($config['from']);
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->isHTML(true);
    
    // Handle attachments if provided
    if (isset($data['attachments']) && is_array($data['attachments'])) {
        foreach ($data['attachments'] as $attachment) {
            if (!isset($attachment['content']) || !isset($attachment['filename'])) {
                continue;
            }
            
            $decodedContent = base64_decode($attachment['content'], true);
            if ($decodedContent === false) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid base64 attachment content']);
                exit;
            }
            
            $mail->addStringAttachment(
                $decodedContent,
                $attachment['filename']
            );
        }
    }
    
    // Send email
    $mail->send();
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Email sent successfully'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to send email',
        'message' => $mail->ErrorInfo ?? $e->getMessage()
    ]);
    
    // Log error (in production, use proper logging)
    error_log("Email sending failed: " . ($mail->ErrorInfo ?? $e->getMessage()));
}
