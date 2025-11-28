# Simple Email Server (PHP)

A lightweight, secure PHP email server using PHPMailer for sending emails via SMTP. Perfect for simple email sending needs in web applications.

## Features

- ✅ SMTP email sending via PHPMailer
- ✅ Simple configuration using `config.php`
- ✅ JSON API for easy integration
- ✅ Support for HTML emails
- ✅ File attachment support (base64 encoded)
- ✅ HTTPS enforcement (configurable)
- ✅ Comprehensive error handling
- ✅ Input validation and sanitization

## Requirements

- PHP >= 7.4
- PHPMailer library (included in repository)
- SMTP server credentials (Gmail, SendGrid, AWS SES, etc.)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/vascomartins/simple-email-server-php.git
cd simple-email-server-php
```

2. Configure your SMTP settings:
```bash
cp config.php.example config.php
```

Then edit `config.php` with your SMTP credentials:
```php
<?php

$config = [
    'host' => 'smtp.gmail.com',
    'username' => 'your-email@gmail.com',
    'password' => 'your-app-password',
    'port' => 465,
    'from' => 'your-email@gmail.com',
    'encryption' => 'ssl',
    'require_https' => filter_var('true', FILTER_VALIDATE_BOOLEAN),
];
```

**Important:** Never commit your `config.php` file with real credentials. The `config.php.example` file is provided as a template.

## Usage

### API Endpoint

Send a POST request to `server.php` with the following JSON structure:

```json
{
  "to": "recipient@example.com",
  "subject": "Email Subject",
  "body": "<h1>Hello World</h1><p>This is an HTML email.</p>",
  "attachments": [
    {
      "filename": "document.pdf",
      "content": "base64-encoded-content"
    }
  ]
}
```

### Example with cURL

```bash
curl -X POST https://your-domain.com/server.php \
  -H "Content-Type: application/json" \
  -d '{
    "to": "recipient@example.com",
    "subject": "Test Email",
    "body": "<p>This is a test email.</p>"
  }'
```

### Example with JavaScript (Fetch API)

```javascript
fetch('https://your-domain.com/server.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    to: 'recipient@example.com',
    subject: 'Test Email',
    body: '<p>This is a test email.</p>',
    attachments: [
      {
        filename: 'document.pdf',
        content: base64EncodedContent
      }
    ]
  })
})
.then(response => response.json())
.then(data => console.log('Success:', data))
.catch(error => console.error('Error:', error));
```

### Response Format

**Success (200):**
```json
{
  "success": true,
  "message": "Email sent successfully"
}
```

**Error (400/500):**
```json
{
  "error": "Error description",
  "message": "Detailed error message"
}
```

## Configuration

### Configuration Options

Edit `config.php` to set up your SMTP server:

| Option | Description | Required | Default |
|--------|-------------|----------|---------|
| `host` | SMTP server hostname | Yes | - |
| `username` | SMTP username/email | Yes | - |
| `password` | SMTP password/app password | Yes | - |
| `port` | SMTP server port | No | 465 |
| `from` | From email address | Yes | - |
| `encryption` | Encryption type (`ssl` or `tls`) | No | ssl |
| `require_https` | Enforce HTTPS connections | No | true |

### SMTP Providers

#### Gmail
```php
$config = [
    'host' => 'smtp.gmail.com',
    'username' => 'your-email@gmail.com',
    'password' => 'your-app-password',
    'port' => 465,
    'from' => 'your-email@gmail.com',
    'encryption' => 'ssl',
    'require_https' => filter_var('true', FILTER_VALIDATE_BOOLEAN),
];
```
**Note:** You'll need to use an [App Password](https://support.google.com/accounts/answer/185833) instead of your regular Gmail password.

#### SendGrid
```php
$config = [
    'host' => 'smtp.sendgrid.net',
    'username' => 'apikey',
    'password' => 'your-sendgrid-api-key',
    'port' => 587,
    'from' => 'your-email@example.com',
    'encryption' => 'tls',
    'require_https' => filter_var('true', FILTER_VALIDATE_BOOLEAN),
];
```

#### AWS SES
```php
$config = [
    'host' => 'email-smtp.us-east-1.amazonaws.com',
    'username' => 'your-aws-smtp-username',
    'password' => 'your-aws-smtp-password',
    'port' => 587,
    'from' => 'your-email@example.com',
    'encryption' => 'tls',
    'require_https' => filter_var('true', FILTER_VALIDATE_BOOLEAN),
];
```

## Security Considerations

1. **HTTPS**: The server enforces HTTPS by default. Set `require_https` to `false` in `config.php` to disable (not recommended for production).

2. **Configuration File**: Never commit your `config.php` file with real credentials. Consider:
   - Adding `config.php` to `.gitignore`
   - Using a `config.php.example` template
   - Using environment variables or a secure configuration management system

3. **Rate Limiting**: Consider implementing rate limiting in production to prevent abuse.

4. **Input Validation**: All inputs are validated and sanitized before processing.

5. **Error Messages**: Error messages are generic to avoid information leakage.

6. **File Permissions**: Ensure `config.php` has appropriate file permissions (e.g., `chmod 600 config.php`).

## Development

### Testing Locally

1. Set up a local development environment (XAMPP, MAMP, or PHP built-in server)
2. Fill in the configuration parameters in `config.php`
3. Test with a tool like Postman or cURL

### Using PHP Built-in Server

```bash
php -S localhost:8000
```

Then test with:
```bash
curl -X POST http://localhost:8000/server.php \
  -H "Content-Type: application/json" \
  -d '{"to":"test@example.com","subject":"Test","body":"Test body"}'
```

**Note:** For local testing, you may need to set `require_https` to `false` in `config.php`:
```php
'require_https' => filter_var('false', FILTER_VALIDATE_BOOLEAN),
```

## Project Structure

```
simple-email-server-php/
├── server.php              # Main API endpoint
├── config.php              # SMTP configuration (create from example)
├── config.php.example      # Configuration template
├── phpmailer/              # PHPMailer library
│   └── src/
│       ├── PHPMailer.php
│       ├── SMTP.php
│       └── Exception.php
├── README.md               # This file
└── LICENSE                 # MIT License
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Author

**Vasco Martins**

- GitHub: [@vascomartins](https://github.com/vascomartins)

## Acknowledgments

- [PHPMailer](https://github.com/PHPMailer/PHPMailer) - The email library powering this project
