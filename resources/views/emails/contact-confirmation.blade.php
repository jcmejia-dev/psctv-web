<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for contacting PSCTV</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            margin: 20px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #FFD700 0%, #D4AF37 50%, #B8860B 100%);
            color: #000000;
            padding: 30px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            margin: -30px -30px 30px -30px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
        }
        .content {
            margin-bottom: 30px;
        }
        .welcome-message {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .info-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #FFD700;
            margin: 20px 0;
        }
        .services {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .service-item {
            background: linear-gradient(135deg, #FFD700 0%, #D4AF37 100%);
            color: #000000;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            font-weight: 600;
        }
        .contact-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .contact-icon {
            width: 20px;
            margin-right: 10px;
            color: #FFD700;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            height: 60px;
            width: 60px;
            border-radius: 50%;
            border: 3px solid #FFD700;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #FFD700 0%, #D4AF37 50%, #B8860B 100%);
            color: #000000;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 700;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="PSCTV Logo">
            </div>
            <h1>Thank You for Contacting PSCTV!</h1>
            <p>Your message has been received successfully</p>
        </div>

        <div class="content">
            <div class="welcome-message">
                Dear {{ $name }},
            </div>

            <p>Thank you for reaching out to PSCTV! We have received your inquiry about our <strong>{{ $service }}</strong> services and appreciate your interest in our premium cable and fiber internet solutions.</p>

            <div class="info-box">
                <h3 style="margin-top: 0; color: #FFD700;">What happens next?</h3>
                <p>Our customer service team will review your message and contact you within 24 hours to discuss your specific needs and provide you with the best solutions for your home or business.</p>
            </div>

            <div class="services">
                <div class="service-item">High-Speed Internet</div>
                <div class="service-item">Cable Television</div>
                <div class="service-item">Digital Phone</div>
                <div class="service-item">Bundle Packages</div>
            </div>

            <div class="contact-info">
                <h3 style="margin-top: 0; color: #FFD700;">Need immediate assistance?</h3>
                <div class="contact-item">
                    <span class="contact-icon">📞</span>
                    <span>Call us: +639328476222</span>
                </div>
                <div class="contact-item">
                    <span class="contact-icon">📧</span>
                    <span>Email: psctvincacctg@gmail.com</span>
                </div>
                <div class="contact-item">
                    <span class="contact-icon">📍</span>
                    <span>Visit: 519 Quezon St., New Pandan, Panabo City</span>
                </div>
                <div class="contact-item">
                    <span class="contact-icon">🕒</span>
                    <span>Business Hours: Mon-Sat, 8:00 AM - 6:00 PM</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="cta-button">Visit Our Website</a>
            </div>
        </div>

        <div class="footer">
            <p>We look forward to serving you with the best connectivity solutions in Panabo City!</p>
            <p>&copy; 2025 PSCTV - Panabo Satellite Cable Television Inc. | Est. 1996</p>
        </div>
    </div>
</body>
</html>
