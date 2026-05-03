<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSCTV - Premium Cable & Fiber Internet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        /* Previous CSS remains the same, adding new styles for the app section */
        
        /* App Download Section */
        .app-section {
            background-color: var(--dark);
            padding: 80px 0;
        }
        
        .app-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .app-buttons {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 50px;
            flex-wrap: wrap;
        }
        
        .app-btn {
            background: var(--gradient);
            color: var(--secondary);
            border: none;
            border-radius: 12px;
            padding: 20px 40px;
            font-size: 1.2rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            z-index: 1;
            min-width: 220px;
        }
        
        .app-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gold-shine);
            transition: 0.7s;
            z-index: -1;
        }
        
        .app-btn:hover::before {
            left: 100%;
        }
        
        .app-btn:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        
        .app-btn.active {
            background: var(--secondary);
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        
        .app-content {
            display: flex;
            gap: 50px;
            align-items: center;
            opacity: 0;
            height: 0;
            overflow: hidden;
            transition: all 0.5s ease;
        }
        
        .app-content.active {
            opacity: 1;
            height: auto;
            overflow: visible;
        }
        
        .app-qr {
            flex: 0 0 250px;
            text-align: center;
        }
        
        .app-qr img {
            width: 200px;
            height: 200px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 2px solid rgba(255, 215, 0, 0.3);
        }
        
        .app-info {
            flex: 1;
        }
        
        .app-info h3 {
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 1.8rem;
        }
        
        .app-info p {
            margin-bottom: 20px;
            color: var(--light);
        }
        
        .download-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--gradient);
            color: var(--secondary);
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }
        
        .download-link:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            color: var(--secondary);
        }
        
        @media (max-width: 768px) {
            .app-content {
                flex-direction: column;
                text-align: center;
            }
            
            .app-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .app-btn {
                width: 100%;
                max-width: 300px;
            }
        }
        
        /* Rest of your existing CSS remains unchanged */
        :root {
            --primary: #FFD700; /* Bright Gold */
            --primary-dark: #D4AF37;
            --primary-light: #FFE55C;
            --secondary: #000000; /* Black */
            --dark: #0a0a0a;
            --light: #ffffff;
            --gray: #a0a0a0;
            --gray-light: #1a1a1a;
            --transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.4);
            --gradient: linear-gradient(135deg, #FFD700 0%, #D4AF37 50%, #B8860B 100%);
            --gradient-light: linear-gradient(135deg, #FFE55C 0%, #FFD700 100%);
            --gold-shine: linear-gradient(135deg, rgba(255, 215, 0, 0.8) 0%, rgba(255, 255, 255, 0.9) 50%, rgba(255, 215, 0, 0.8) 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--secondary);
            color: var(--light);
            line-height: 1.6;
            overflow-x: hidden;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        section {
            padding: 100px 0;
        }

        h1, h2, h3, h4 {
            margin-bottom: 1rem;
            line-height: 1.2;
            font-weight: 700;
            color: var(--light);
        }

        p {
            margin-bottom: 1.5rem;
            color: var(--light);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 32px;
            background: var(--gradient);
            color: var(--secondary);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow);
            z-index: 1;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gold-shine);
            transition: 0.7s;
            z-index: -1;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--secondary);
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: var(--gradient);
            color: var(--secondary);
        }

        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--gradient);
            color: var(--secondary);
        }

        .btn-login {
            background-color: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 10px 24px;
            margin-left: 15px;
        }

        .btn-login:hover {
            background-color: rgba(255, 215, 0, 0.1);
            color: var(--primary);
            transform: none;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-size: 2.8rem;
            position: relative;
            display: inline-block;
            color: var(--primary);
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--gradient);
            border-radius: 2px;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }

        .section-title p {
            max-width: 600px;
            margin: 30px auto 0;
            font-size: 1.1rem;
            color: var(--gray);
        }

        /* Header & Navigation */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background-color: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 2px 30px rgba(0, 0, 0, 0.3);
            transition: var(--transition);
        }

        header.scrolled {
            padding: 5px 0;
            background-color: rgba(0, 0, 0, 0.98);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            transition: var(--transition);
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            margin-right: 12px;
            transition: var(--transition);
            object-fit: cover;
            border: 2px solid var(--primary);
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.5);
        }

        .logo-text {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.5px;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }

        .nav-links {
            display: flex;
            list-style: none;
            align-items: center;
        }

        .nav-links li {
            margin-left: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--light);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient);
            transition: var(--transition);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .mobile-menu {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--light);
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.9)), url('https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80') center/cover no-repeat;
            position: relative;
            overflow: hidden;
            color: white;
        }

        .hero-content {
            max-width: 650px;
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 3.8rem;
            margin-bottom: 1.5rem;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 1s forwards 0.5s;
            line-height: 1.1;
            color: var(--primary);
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 1s forwards 0.8s;
            color: var(--light);
        }

        .hero-btns {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 1s forwards 1.1s;
            display: flex;
            gap: 15px;
        }

        .hero-image {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 50%;
            height: 80%;
            opacity: 0;
            animation: fadeInRight 1s forwards 1.4s;
        }

        .hero-image::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('https://images.unsplash.com/photo-1621905252507-b35492cc74b4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80') center/cover no-repeat;
            border-radius: 20px 0 0 20px;
            box-shadow: var(--shadow-lg);
        }

        /* AI Chat Widget */
        .ai-chat-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .chat-toggle {
            width: 70px;
            height: 70px;
            background: var(--gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary);
            font-size: 1.8rem;
            cursor: pointer;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .chat-toggle::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: scale(0);
            transition: var(--transition);
        }

        .chat-toggle:hover::before {
            transform: scale(1);
        }

        .chat-toggle:hover {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 0 25px rgba(255, 215, 0, 0.7);
        }

        .chat-container {
            position: absolute;
            bottom: 90px;
            right: 0;
            width: 380px;
            height: 550px;
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            display: none;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid rgba(255, 215, 0, 0.3);
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.5s ease;
        }

        .chat-container.active {
            display: flex;
            transform: translateY(0);
            opacity: 1;
        }

        .chat-header {
            background: var(--gradient);
            color: var(--secondary);
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 215, 0, 0.3);
        }

        .chat-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-header h3 i {
            font-size: 1.2rem;
        }

        .chat-close {
            background: none;
            border: none;
            color: var(--secondary);
            font-size: 1.2rem;
            cursor: pointer;
            transition: var(--transition);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-close:hover {
            background: rgba(0, 0, 0, 0.1);
            transform: rotate(90deg);
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message {
            padding: 15px 18px;
            border-radius: 18px;
            max-width: 80%;
            line-height: 1.4;
            position: relative;
            animation: messageSlide 0.4s ease-out;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            color: var(--light);
        }

        @keyframes messageSlide {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.bot {
            background: rgba(255, 215, 0, 0.1);
            color: var(--light);
            align-self: flex-start;
            border-bottom-left-radius: 5px;
            border: 1px solid rgba(255, 215, 0, 0.2);
        }

        .message.user {
            background: var(--gradient);
            color: var(--secondary);
            align-self: flex-end;
            border-bottom-right-radius: 5px;
        }

        .chat-input {
            padding: 20px;
            border-top: 1px solid rgba(255, 215, 0, 0.2);
            display: flex;
            gap: 10px;
            background: rgba(0, 0, 0, 0.5);
        }

        .chat-input input {
            flex: 1;
            padding: 15px 20px;
            border: 1px solid rgba(255, 215, 0, 0.3);
            border-radius: 25px;
            outline: none;
            background: rgba(26, 26, 26, 0.8);
            color: var(--light);
            transition: var(--transition);
        }

        .chat-input input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
        }

        .chat-input button {
            background: var(--gradient);
            color: var(--secondary);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .chat-input button:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
        }

        /* Video Section */
        .video-section {
            background: var(--dark);
            padding: 80px 0;
        }

        .video-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .video-content h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--primary);
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }

        .video-player {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 2px solid rgba(255, 215, 0, 0.3);
        }

        .video-player video {
            width: 100%;
            display: block;
            transition: var(--transition);
        }

        .video-player:hover video {
            transform: scale(1.02);
        }

        /* Map Section */
        .map-section {
            padding: 80px 0;
            background: var(--dark);
        }

        .map-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            height: 400px;
            border: 2px solid rgba(255, 215, 0, 0.3);
        }

        #map {
            width: 100%;
            height: 100%;
        }

        /* Contact Form Updates */
        .g-recaptcha {
            margin: 20px 0;
        }

        .form-status {
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            display: none;
            color: var(--light);
        }

        .form-status.success {
            background: rgba(255, 215, 0, 0.1);
            color: var(--primary);
            border: 1px solid rgba(255, 215, 0, 0.3);
            display: block;
        }

        .form-status.error {
            background: rgba(220, 53, 69, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(220, 53, 69, 0.3);
            display: block;
        }

        /* Services Section */
        .services {
            background-color: var(--secondary);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .service-card {
            background-color: rgba(26, 26, 26, 0.8);
            border-radius: 12px;
            padding: 40px 30px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            text-align: center;
            opacity: 0;
            transform: translateY(30px);
            position: relative;
            overflow: hidden;
            z-index: 1;
            border: 1px solid rgba(255, 215, 0, 0.1);
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient);
            transform: scaleX(0);
            transform-origin: left;
            transition: var(--transition);
        }

        .service-card.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(255, 215, 0, 0.3);
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: var(--secondary);
            font-size: 2rem;
            transition: var(--transition);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .service-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: var(--gold-shine);
            transform: rotate(45deg);
            transition: var(--transition);
            opacity: 0;
        }

        .service-card:hover .service-icon::before {
            opacity: 1;
            animation: shine 1.5s ease;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
            }
            100% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
            }
        }

        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .service-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--primary);
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }

        .service-card p {
            color: var(--light);
        }

        /* Plans Section */
        .plans-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
        }

        .plan-card {
            background-color: rgba(26, 26, 26, 0.8);
            border-radius: 12px;
            padding: 40px 30px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            text-align: center;
            flex: 1;
            min-width: 280px;
            max-width: 350px;
            opacity: 0;
            transform: translateY(30px);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 215, 0, 0.1);
        }

        .plan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient);
        }

        .plan-card.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .plan-card.featured {
            transform: scale(1.05);
            border: 2px solid var(--primary);
            position: relative;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
        }

        .plan-card.featured::after {
            content: 'Most Popular';
            position: absolute;
            top: 20px;
            right: -30px;
            background: var(--gradient);
            color: var(--secondary);
            padding: 5px 30px;
            font-size: 0.8rem;
            font-weight: 600;
            transform: rotate(45deg);
        }

        .plan-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(255, 215, 0, 0.3);
        }

        .plan-card.featured:hover {
            transform: scale(1.05) translateY(-10px);
        }

        .plan-name {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: var(--primary);
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }

        .plan-price {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: var(--light);
            position: relative;
            display: inline-block;
        }

        .plan-price::before {
            content: '₱';
            font-size: 1.5rem;
            position: absolute;
            top: 10px;
            left: -20px;
            color: var(--primary);
        }

        .plan-price span {
            font-size: 1rem;
            color: var(--gray);
            font-weight: 500;
        }

        .plan-features {
            list-style: none;
            margin-bottom: 30px;
        }

        .plan-features li {
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 215, 0, 0.1);
            position: relative;
            padding-left: 25px;
            color: var(--light);
        }

        .plan-features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--primary);
            font-weight: bold;
        }

        .plan-features li:last-child {
            border-bottom: none;
        }

        /* About Section */
        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .about-text {
            opacity: 0;
            transform: translateX(-50px);
        }

        .about-text.animate {
            opacity: 1;
            transform: translateX(0);
            transition: all 0.8s ease;
        }

        .about-image {
            opacity: 0;
            transform: translateX(50px);
        }

        .about-image.animate {
            opacity: 1;
            transform: translateX(0);
            transition: all 0.8s ease;
        }

        .about-image img {
            width: 100%;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
            border: 2px solid rgba(255, 215, 0, 0.3);
        }

        .about-image:hover img {
            transform: scale(1.02);
        }

        /* Stats Section */
        .stats {
            background: var(--gradient);
            color: var(--secondary);
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
        }

        .stat-item {
            opacity: 0;
            transform: translateY(30px);
        }

        .stat-item.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .stat-text {
            font-size: 1.2rem;
            font-weight: 500;
        }

        /* Contact Section */
        .contact {
            background-color: var(--secondary);
        }

        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
        }

        .contact-info {
            opacity: 0;
            transform: translateX(-50px);
        }

        .contact-info.animate {
            opacity: 1;
            transform: translateX(0);
            transition: all 0.8s ease;
        }

        .contact-form {
            opacity: 0;
            transform: translateX(50px);
        }

        .contact-form.animate {
            opacity: 1;
            transform: translateX(0);
            transition: all 0.8s ease;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: var(--gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary);
            font-size: 1.2rem;
            margin-right: 15px;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }

        .contact-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: var(--gold-shine);
            transform: rotate(45deg);
            transition: var(--transition);
            opacity: 0;
        }

        .contact-item:hover .contact-icon::before {
            opacity: 1;
            animation: shine 1.5s ease;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            border: 1px solid rgba(255, 215, 0, 0.3);
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
            background-color: rgba(26, 26, 26, 0.8);
            color: var(--light);
            position: relative;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        /* Form Placeholder Styling */
        .form-control::placeholder {
            color: var(--light);
            opacity: 0.8;
        }

        /* Form Select Styling */
        .form-control option {
            background-color: var(--dark);
            color: var(--light);
        }

        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 80px 0 30px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 50px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .footer-logo img {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            margin-right: 12px;
            object-fit: cover;
            border: 2px solid var(--primary);
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }

        .footer-logo-text {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary);
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }

        .footer-about p {
            color: var(--gray);
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-icons a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 215, 0, 0.1);
            border-radius: 50%;
            color: var(--primary);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .social-icons a::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            border-radius: 50%;
            transform: scale(0);
            transition: var(--transition);
            z-index: -1;
        }

        .social-icons a:hover::before {
            transform: scale(1);
        }

        .social-icons a:hover {
            color: var(--secondary);
            transform: translateY(-3px);
        }

        .footer-links h3, .footer-contact h3 {
            font-size: 1.3rem;
            margin-bottom: 25px;
            position: relative;
            color: var(--primary);
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }

        .footer-links h3::after, .footer-contact h3::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--gradient);
            border-radius: 2px;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: var(--gray);
            text-decoration: none;
            transition: var(--transition);
            display: inline-block;
        }

        .footer-links a:hover {
            color: var(--primary);
            transform: translateX(5px);
        }

        .footer-contact p {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            color: var(--gray);
        }

        .footer-contact i {
            margin-right: 12px;
            color: var(--primary);
            width: 20px;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 215, 0, 0.2);
            color: var(--gray);
        }

        /* Black Address Text */
        .black-address {
            color: var(--secondary) !important;
            font-weight: 600;
        }

        /* Animations */
        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            to {
                opacity: 1;
                transform: translateX(0) translateY(-50%);
            }
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .hero-image {
                width: 45%;
            }
        }

        @media (max-width: 992px) {
            .hero-content {
                max-width: 100%;
                text-align: center;
            }
            
            .hero h1 {
                font-size: 3.2rem;
            }
            
            .hero-image {
                display: none;
            }
            
            .about-content, .contact-container, .video-container {
                grid-template-columns: 1fr;
            }
            
            .about-text, .about-image, .contact-info, .contact-form {
                transform: translateY(30px);
            }
            
            .about-text.animate, .about-image.animate, .contact-info.animate, .contact-form.animate {
                transform: translateY(0);
            }

            .chat-container {
                width: 320px;
                height: 480px;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 15px 0;
            }
            
            .nav-links {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 80px);
                background-color: rgba(0, 0, 0, 0.95);
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                padding-top: 50px;
                transition: var(--transition);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
                backdrop-filter: blur(20px);
            }
            
            .nav-links.active {
                left: 0;
            }
            
            .nav-links li {
                margin: 20px 0;
            }
            
            .mobile-menu {
                display: block;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .section-title h2 {
                font-size: 2.2rem;
            }
            
            .plan-card.featured {
                transform: scale(1);
            }
            
            .plan-card.featured:hover {
                transform: translateY(-10px);
            }
            
            .hero-btns {
                flex-direction: column;
                align-items: center;
            }
            
            .hero-btns .btn {
                width: 100%;
                max-width: 250px;
                margin-bottom: 15px;
            }

            .ai-chat-widget {
                bottom: 20px;
                right: 20px;
            }

            .chat-container {
                width: 300px;
                height: 450px;
                right: -10px;
            }
        }

        @media (max-width: 576px) {
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .services-grid {
                grid-template-columns: 1fr;
            }
            
            .plan-card {
                min-width: 100%;
            }
            
            .stat-number {
                font-size: 2.8rem;
            }

            .chat-container {
                width: 280px;
                right: -20px;
            }
        }

        /* Mobile AI Chat Improvements */
        @media (max-width: 480px) {
            .ai-chat-widget {
                bottom: 15px;
                right: 15px;
            }

            .chat-toggle {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .chat-container {
                width: calc(100vw - 30px);
                height: 70vh;
                right: 0;
                bottom: 80px;
                border-radius: 15px 15px 0 0;
            }

            .chat-messages {
                padding: 15px;
            }

            .chat-input {
                padding: 15px;
            }

            .chat-input input {
                padding: 12px 16px;
            }

            .chat-input button {
                width: 45px;
                height: 45px;
            }
        }

        /* Touch-friendly improvements for mobile */
        .chat-toggle {
            -webkit-tap-highlight-color: transparent;
        }

        .message {
            word-wrap: break-word;
        }

        /* Prevent zoom on input focus for mobile */
        @media (max-width: 768px) {
            .chat-input input {
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header id="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <img src="images/logo.png" alt="PSCTV Logo">
                    <div class="logo-text">PSCTV</div>
                </div>
                <ul class="nav-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#plans">Plans</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="{{ route('login.form') }}">Login</a></li>
                </ul>
                <div class="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <h1>Premium Cable & Fiber Internet</h1>
                <p>Experience lightning-fast internet speeds and crystal-clear television with PSCTV's advanced fiber optic technology. Serving Panabo City with reliable connectivity.</p>
                <div class="hero-btns">
                    <a href="#plans" class="btn">View Plans</a>
                    <a href="#contact" class="btn">Contact Us</a>
                </div>
            </div>
            <div class="hero-image"></div>
        </div>
    </section>

    <!-- App Download Section -->
    <section class="app-section" id="apps">
        <div class="container">
            <div class="section-title">
                <h2>Download Our Apps</h2>
                <p>Get the best experience with our mobile applications</p>
            </div>
            <div class="app-container">
                <div class="app-buttons">
                    <button class="app-btn active" data-app="technician">Technician App</button>
                    <button class="app-btn" data-app="customer">Customer App</button>
                </div>
                
                <div class="app-content active" id="technician-app">
                    <div class="app-qr">
                        <img src="{{ asset('images/qrcode.png') }}" alt="Technician App QR Code">
                    </div>
                    <div class="app-info">
                        <h3>PSCTV Technician App</h3>
                        <p>Our technician app is designed to help our field technicians efficiently manage service requests, track work orders, and provide real-time updates to customers. With intuitive tools and seamless integration, technicians can deliver exceptional service with ease.</p>
                        <p>Key features include:</p>
                        <ul>
                            <li>Service request management</li>
                            <li>Real-time work order tracking</li>
                            <li>Customer information access</li>
                            <li>Digital documentation</li>
                            <li>Route optimization</li>
                        </ul>
                        <a href="http://bit.ly/43C2jQz" class="download-link">
                            <i class="fas fa-download"></i> For faster download click here
                        </a>
                    </div>
                </div>
                
                <div class="app-content" id="customer-app">
                    <div class="app-qr">
                         <img src="{{ asset('images/qrcodecustomer.png') }}" alt="Technician App QR Code">
                    </div>
                    <div class="app-info">
                        <h3>PSCTV Customer App</h3>
                        <p>Our customer app provides a seamless experience for managing your PSCTV services. From paying bills and reporting issues to accessing customer support and viewing service status, everything you need is at your fingertips.</p>
                        <p>Key features include:</p>
                        <ul>
                            <li>Bill payment and history</li>
                            <li>Service request submission</li>
                            <li>Real-time service status</li>
                            <li>Customer support chat</li>
                            <li>Account management</li>
                        </ul>
                        <a href="https://expo.dev/accounts/psctvcustomerapp/projects/PSCTV-Customer-App/builds/a502bea7-8406-4dcf-b139-15c4c30cf509" class="download-link">
                            <i class="fas fa-download"></i> For faster download click here
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Chat Widget -->
    <div class="ai-chat-widget">
        <button class="chat-toggle">
            <i class="fas fa-robot"></i>
        </button>
        <div class="chat-container">
            <div class="chat-header">
                <h3><i class="fas fa-robot"></i> PSCTV Assistant</h3>
                <button class="chat-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="chat-messages" id="chatMessages">
                <div class="message bot">
                    Welcome to PSCTV! I'm your virtual assistant, here to help you with any questions about our premium services.
                </div>
                <div class="message bot">
                    Please share your email address so we can send you personalized information about our exclusive offers.
                </div>
            </div>
            <div class="chat-input">
                <input type="text" id="chatInput" placeholder="Type your email or message...">
                <button id="sendMessage">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Video Section -->
    <section class="video-section">
        <div class="container">
            <div class="video-container">
                <div class="video-content">
                    <h2>Experience PSCTV Quality</h2>
                    <p>Watch our introduction video to learn more about our premium cable and fiber internet services in Panabo City.</p>
                    <p>Discover why thousands of customers trust PSCTV for their connectivity needs with our state-of-the-art infrastructure and dedicated customer support.</p>
                    <a href="#contact" class="btn">Get Started Today</a>
                </div>
                <div class="video-player">
                    <video id="promoVideo" autoplay muted loop playsinline>
                       <source src="public/videos/video.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-title">
                <h2>Our Services</h2>
                <p>Discover the range of services we offer to keep you connected and entertained</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-broadcast-tower"></i>
                    </div>
                    <h3>High-Speed Internet</h3>
                    <p>Blazing fast fiber optic internet with speeds up to 1Gbps for seamless browsing, streaming, and gaming.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-tv"></i>
                    </div>
                    <h3>Cable Television</h3>
                    <p>Over 100 channels including local and international content in high definition for your entertainment.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3>Digital Phone</h3>
                    <p>Crystal clear digital phone service with unlimited calling to keep you connected with loved ones.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">5,000+</div>
                    <div class="stat-text">Happy Customers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">99%</div>
                    <div class="stat-text">Uptime Guarantee</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-text">Customer Support</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10+</div>
                    <div class="stat-text">Years of Service</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Plans Section -->
    <section id="plans">
        <div class="container">
            <div class="section-title">
                <h2>Internet Plans</h2>
                <p>Choose the perfect plan for your home or business needs</p>
            </div>
            <div class="plans-container">
                <div class="plan-card">
                    <h3 class="plan-name">Basic</h3>
                    <div class="plan-price">999<span>/month</span></div>
                    <ul class="plan-features">
                        <li>25 Mbps Internet Speed</li>
                        <li>50+ Cable Channels</li>
                        <li>Free Installation</li>
                        <li>24/7 Customer Support</li>
                    </ul>
                    <a href="#contact" class="btn btn-outline">Get Started</a>
                </div>
                <div class="plan-card featured">
                    <h3 class="plan-name">Premium</h3>
                    <div class="plan-price">1,499<span>/month</span></div>
                    <ul class="plan-features">
                        <li>100 Mbps Internet Speed</li>
                        <li>100+ Cable Channels</li>
                        <li>Free Digital Phone</li>
                        <li>Free Wi-Fi Router</li>
                        <li>24/7 Customer Support</li>
                    </ul>
                    <a href="#contact" class="btn">Get Started</a>
                </div>
                <div class="plan-card">
                    <h3 class="plan-name">Ultimate</h3>
                    <div class="plan-price">2,499<span>/month</span></div>
                    <ul class="plan-features">
                        <li>500 Mbps Internet Speed</li>
                        <li>150+ Cable Channels</li>
                        <li>Free Digital Phone</li>
                        <li>Premium Wi-Fi Router</li>
                        <li>Priority Support</li>
                    </ul>
                    <a href="#contact" class="btn btn-outline">Get Started</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="section-title">
                <h2>Visit Our Location</h2>
                <p>Find us at our main office in Panabo City</p>
            </div>
            <div class="map-container">
                <div id="map"></div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>About PSCTV</h2>
                    <p>PSCTV has been serving the Panabo community since 2010, providing reliable cable television and internet services. Our commitment to quality and customer satisfaction has made us the leading provider in the region.</p>
                    <p>With our state-of-the-art fiber optic network, we deliver unparalleled internet speeds and crystal-clear television signals to homes and businesses throughout Panabo City and surrounding areas.</p>
                    <p>Our team of dedicated professionals is always ready to assist you with installation, troubleshooting, and any other needs you may have.</p>
                    <a href="#contact" class="btn">Learn More</a>
                </div>
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="PSCTV Network Infrastructure">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="section-title">
                <h2>Contact Us</h2>
                <p>Get in touch with us for inquiries, installation requests, or support</p>
            </div>
            <div class="contact-container">
                <div class="contact-info">
                    <h3>Get In Touch</h3>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h4>Our Location</h4>
                            <p>519 Quezon St., New Pandan, Panabo City, Davao Del Norte</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h4>Call Us</h4>
                            <p>+639328476222</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h4>Email Us</h4>
                            <p>psctvincacctg@gmail.com</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h4>Business Hours</h4>
                            <p>Monday - Saturday: 8:00 AM - 6:00 PM</p>
                        </div>
                    </div>
                </div>
                <div class="contact-form">
                    <h3>Send Us a Message</h3>
                   <form id="contactForm" action="/contact" method="POST">
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" class="form-control" name="phone" placeholder="Your Phone Number">
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="service" required>
                                <option value="">Select Service</option>
                                <option value="internet">Internet</option>
                                <option value="cable">Cable TV</option>
                                <option value="phone">Digital Phone</option>
                                <option value="bundle">Bundle Package</option>
                                <option value="support">Technical Support</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="message" placeholder="Your Message" required></textarea>
                        </div>
                        <div class="g-recaptcha" data-sitekey="6LcoSggsAAAAAAGkJlo5BF8Ph_TAs64cHdGRWiGj"></div>
                        <div class="form-status" id="formStatus"></div>
                        <button type="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <div class="footer-logo">
                        <img src="images/logo.png" alt="PSCTV Logo">
                        <div class="footer-logo-text">PSCTV</div>
                    </div>
                    <p>Panabo's premier cable and fiber internet provider, delivering reliable connectivity and entertainment to homes and businesses since 1996.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#plans">Plans</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="#apps">Download Apps</a></li>
                        <li><a href="#">Login</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h3>Contact Info</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 519 Quezon St., New Pandan, Panabo City, Davao Del Norte</p>
                    <p><i class="fas fa-phone"></i> +639328476222</p>
                    <p><i class="fas fa-envelope"></i> psctvincacctg@gmail.com</p>
                    <p><i class="fas fa-clock"></i> Mon-Sat: 8:00 AM - 6:00 PM</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 by Panabo Satellite Cable Television Inc. Est. 1996</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Mobile Menu Toggle
        const mobileMenu = document.querySelector('.mobile-menu');
        const navLinks = document.querySelector('.nav-links');

        mobileMenu.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            mobileMenu.querySelector('i').classList.toggle('fa-bars');
            mobileMenu.querySelector('i').classList.toggle('fa-times');
        });

        // Header Scroll Effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Smooth Scrolling for Anchor Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    navLinks.classList.remove('active');
                    mobileMenu.querySelector('i').classList.add('fa-bars');
                    mobileMenu.querySelector('i').classList.remove('fa-times');
                }
            });
        });

        // Animation on Scroll
        const animateOnScroll = () => {
            const elements = document.querySelectorAll('.service-card, .plan-card, .about-text, .about-image, .contact-info, .contact-form, .stat-item');
            
            elements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const screenPosition = window.innerHeight / 1.2;
                
                if(elementPosition < screenPosition) {
                    element.classList.add('animate');
                }
            });
        };

        window.addEventListener('scroll', animateOnScroll);
        window.addEventListener('load', animateOnScroll);

        // App Section Toggle
        const appButtons = document.querySelectorAll('.app-btn');
        const appContents = document.querySelectorAll('.app-content');
        
        appButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons and contents
                appButtons.forEach(btn => btn.classList.remove('active'));
                appContents.forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked button
                button.classList.add('active');
                
                // Show corresponding content
                const appType = button.getAttribute('data-app');
                const contentToShow = document.getElementById(`${appType}-app`);
                if (contentToShow) {
                    contentToShow.classList.add('active');
                }
            });
        });

        // AI Chat Functionality
        const chatToggle = document.querySelector('.chat-toggle');
        const chatContainer = document.querySelector('.chat-container');
        const chatClose = document.querySelector('.chat-close');
        const chatMessages = document.getElementById('chatMessages');
        const chatInput = document.getElementById('chatInput');
        const sendMessage = document.getElementById('sendMessage');

        // Toggle chat visibility
        chatToggle.addEventListener('click', () => {
            chatContainer.classList.toggle('active');
        });

        // Close chat
        chatClose.addEventListener('click', () => {
            chatContainer.classList.remove('active');
        });

        // Mobile-friendly touch events
        chatToggle.addEventListener('touchstart', function(e) {
            e.preventDefault();
            chatContainer.classList.toggle('active');
        }, { passive: false });

        // Close chat when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const isClickInsideChat = chatContainer.contains(event.target) || chatToggle.contains(event.target);
            if (!isClickInsideChat && chatContainer.classList.contains('active')) {
                chatContainer.classList.remove('active');
            }
        });

        // Prevent chat close when clicking inside chat on mobile
        chatContainer.addEventListener('click', function(event) {
            event.stopPropagation();
        });

        function addMessage(message, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
            messageDiv.textContent = message;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function handleUserMessage() {
            const message = chatInput.value.trim();
            if (!message) return;

            addMessage(message, true);
            chatInput.value = '';

            // Simulate AI response
            setTimeout(() => {
                if (message.includes('@') && message.includes('.')) {
                    addMessage("Thank you! We've received your email and will contact you shortly with our exclusive offers. Is there anything specific you'd like to know about our premium services?");
                } else {
                    addMessage("I'd be delighted to assist you! Could you please provide your email address so we can send you personalized information about our premium packages and exclusive offers?");
                }
            }, 1000);
        }

        sendMessage.addEventListener('click', handleUserMessage);
        
        // Mobile-friendly send on enter
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                handleUserMessage();
            }
        });

        // Video Auto-Play
        const promoVideo = document.getElementById('promoVideo');
        
        // Ensure video plays when page loads
        document.addEventListener('DOMContentLoaded', function() {
            promoVideo.play().catch(function(error) {
                console.log('Video autoplay failed:', error);
                // Fallback: show play button
            });
        });

        // Leaflet Map
        function initMap() {
            // PSCTV Location Coordinates (Panabo City)
            const psctvLocation = [7.3086, 125.6840];
            
            // Initialize map
            const map = L.map('map').setView(psctvLocation, 15);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Add custom marker
            const customIcon = L.divIcon({
                className: 'custom-marker',
                html: '<i class="fas fa-map-marker-alt" style="color: #FFD700; font-size: 30px; text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);"></i>',
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            });
            
            // Add marker to map
            L.marker(psctvLocation, {icon: customIcon}).addTo(map)
                .bindPopup(`
                    <div style="text-align: center;">
                        <h4 style="margin: 0 0 10px 0; color: #FFD700;">PSCTV Office</h4>
                        <p style="margin: 0; font-size: 14px; color: #000000; font-weight: 600;">519 Quezon St., New Pandan<br>Panabo City, Davao Del Norte</p>
                    </div>
                `)
                .openPopup();
        }

        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', initMap);

        // Contact Form Submission with reCAPTCHA
        const contactForm = document.getElementById('contactForm');
        const formStatus = document.getElementById('formStatus');

        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Check if reCAPTCHA is filled
            const recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                formStatus.textContent = 'Please complete the reCAPTCHA verification.';
                formStatus.className = 'form-status error';
                return;
            }

            // Show loading state
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Sending...';
            submitBtn.disabled = true;

            try {
                // Get form data
                const formData = new FormData(contactForm);
                
                // Send to Laravel backend
                const response = await fetch(contactForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    formStatus.textContent = 'Thank you! Your message has been sent successfully. We will contact you soon.';
                    formStatus.className = 'form-status success';
                    contactForm.reset();
                    grecaptcha.reset();
                } else {
                    formStatus.textContent = 'Sorry, there was an error sending your message. Please try again.';
                    formStatus.className = 'form-status error';
                }
            } catch (error) {
                formStatus.textContent = 'Network error. Please check your connection and try again.';
                formStatus.className = 'form-status error';
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        });
    </script>
</body>
</html>