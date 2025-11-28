# Simple Email Server (PHP)

A lightweight, secure PHP email server using PHPMailer for sending emails via SMTP. Perfect for simple email sending needs in web applications.

## Features

- ✅ SMTP email sending via PHPMailer
- ✅ Secure configuration using environment variables
- ✅ JSON API for easy integration
- ✅ Support for HTML emails
- ✅ File attachment support (base64 encoded)
- ✅ HTTPS enforcement (configurable)
- ✅ Comprehensive error handling
- ✅ Input validation and sanitization

## Requirements

- PHP >= 7.4
- Composer
- SMTP server credentials (Gmail, SendGrid, AWS SES, etc.)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/vascomartins/simple-email-server-php.git
cd simple-email-server-php
```

2. Install dependencies:
```bash
composer install
```

3. Configure your SMTP settings:
```bash
cp .env.example .env
```

Edit `.env` with your SMTP credentials:
```env
SMTP_HOST=smtp.gmail.com
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=your-app-password
SMTP_PORT=465
SMTP_FROM=your-email@gmail.com
SMTP_ENCRYPTION=ssl
REQUIRE_HTTPS=true
```

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

### Environment Variables

| Variable | Description | Required | Default |
|----------|-------------|----------|---------|
| `SMTP_HOST` | SMTP server hostname | Yes | - |
| `SMTP_USERNAME` | SMTP username/email | Yes | - |
| `SMTP_PASSWORD` | SMTP password/app password | Yes | - |
| `SMTP_PORT` | SMTP server port | No | 465 |
| `SMTP_FROM` | From email address | Yes | - |
| `SMTP_ENCRYPTION` | Encryption type (`ssl` or `tls`) | No | ssl |
| `REQUIRE_HTTPS` | Enforce HTTPS connections | No | true |

### SMTP Providers

#### Gmail
```env
SMTP_HOST=smtp.gmail.com
SMTP_PORT=465
SMTP_ENCRYPTION=ssl
```
**Note:** You'll need to use an [App Password](https://support.google.com/accounts/answer/185833) instead of your regular Gmail password.

#### SendGrid
```env
SMTP_HOST=smtp.sendgrid.net
SMTP_PORT=587
SMTP_ENCRYPTION=tls
SMTP_USERNAME=apikey
SMTP_PASSWORD=your-sendgrid-api-key
```

#### AWS SES
```env
SMTP_HOST=email-smtp.us-east-1.amazonaws.com
SMTP_PORT=587
SMTP_ENCRYPTION=tls
```

## Security Considerations

1. **HTTPS**: The server enforces HTTPS by default. Set `REQUIRE_HTTPS=false` in `.env` to disable (not recommended for production).

2. **Environment Variables**: Never commit your `.env` file. It's already in `.gitignore`.

3. **Rate Limiting**: Consider implementing rate limiting in production to prevent abuse.

4. **Input Validation**: All inputs are validated and sanitized before processing.

5. **Error Messages**: Error messages are generic to avoid information leakage.

## Development

### Testing Locally

1. Set up a local development environment (XAMPP, MAMP, or PHP built-in server)
2. Configure your `.env` file
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

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Author

**Vasco Martins**

- GitHub: [@vascomartins](https://github.com/vascomartins)

## Acknowledgments

- [PHPMailer](https://github.com/PHPMailer/PHPMailer) - The email library powering this project

