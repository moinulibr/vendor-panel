<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        :root{
            --primary:#2563eb;
            --primary-dark:#1d4ed8;
            --text:#334155;
            --heading:#0f172a;
            --border:#e2e8f0;
            --bg:#f8fafc;
            --card:#ffffff;
        }

        body{
            font-family:'Inter',sans-serif;
            background:var(--bg);
            color:var(--text);
            line-height:1.8;
        }

        .hero{
            background:linear-gradient(135deg,#2563eb,#1d4ed8);
            color:#fff;
            text-align:center;
            padding:80px 20px;
        }

        .hero h1{
            font-size:clamp(32px,5vw,52px);
            font-weight:800;
            margin-bottom:12px;
        }

        .hero p{
            max-width:700px;
            margin:auto;
            opacity:.95;
        }

        .container{
            max-width:1100px;
            margin:-50px auto 60px;
            padding:0 20px;
        }

        .card{
            background:var(--card);
            border-radius:24px;
            padding:40px;
            box-shadow:
                0 10px 30px rgba(15,23,42,.06),
                0 1px 2px rgba(15,23,42,.08);
        }

        .meta{
            display:flex;
            flex-wrap:wrap;
            gap:12px;
            margin-bottom:30px;
        }

        .badge{
            background:#eff6ff;
            color:#1d4ed8;
            padding:8px 14px;
            border-radius:999px;
            font-size:14px;
            font-weight:600;
        }

        .section{
            margin-bottom:35px;
        }

        .section h2{
            color:var(--heading);
            font-size:24px;
            margin-bottom:10px;
            font-weight:700;
        }

        .section p{
            margin-bottom:12px;
        }

        .section ul{
            padding-left:22px;
        }

        .section li{
            margin-bottom:8px;
        }

        .contact-box{
            background:#f8fafc;
            border:1px solid var(--border);
            border-radius:16px;
            padding:20px;
            margin-top:20px;
        }

        .footer{
            text-align:center;
            padding:30px 20px 50px;
            color:#64748b;
        }

        .footer a{
            color:var(--primary);
            text-decoration:none;
        }

        @media(max-width:768px){

            .hero{
                padding:60px 20px;
            }

            .container{
                margin:-35px auto 40px;
            }

            .card{
                padding:25px;
            }

            .section h2{
                font-size:20px;
            }
        }
    </style>
</head>
<body>

<section class="hero">
    <h1>Privacy Policy</h1>
    <p>
        Your privacy is important to us. This policy explains how we collect,
        use, store, and protect your information when you use our application.
    </p>
</section>

<div class="container">

    <div class="card">

        <div class="meta">
            <span class="badge">Last Updated: September 2026</span>
            <span class="badge">User Privacy First</span>
            <span class="badge">Secure Data Handling</span>
        </div>

        <div class="section">
            <h2>1. Information We Collect</h2>

            <p>
                We may collect certain information to provide and improve our services.
            </p>

            <ul>
                <li>Name and profile information</li>
                <li>Email address</li>
                <li>Phone number</li>
                <li>Device and browser information</li>
                <li>App usage and analytics data</li>
            </ul>
        </div>

        <div class="section">
            <h2>2. How We Use Your Information</h2>

            <ul>
                <li>Provide and maintain our services</li>
                <li>Improve user experience</li>
                <li>Respond to customer support requests</li>
                <li>Enhance security and prevent fraud</li>
                <li>Comply with legal obligations</li>
            </ul>
        </div>

        <div class="section">
            <h2>3. Data Sharing</h2>

            <p>
                We do not sell, rent, or trade your personal information to third parties.
                Information may only be shared with trusted service providers necessary
                for operating the application.
            </p>
        </div>

        <div class="section">
            <h2>4. Data Security</h2>

            <p>
                We implement industry-standard security measures designed to protect
                your information against unauthorized access, disclosure, or misuse.
            </p>
        </div>

        <div class="section">
            <h2>5. Data Retention</h2>

            <p>
                We retain user information only for as long as necessary to provide
                services, comply with legal obligations, and resolve disputes.
            </p>
        </div>

        <div class="section">
            <h2>6. Your Rights</h2>

            <ul>
                <li>Access your personal information</li>
                <li>Request correction of inaccurate data</li>
                <li>Request account deletion</li>
                <li>Withdraw consent where applicable</li>
            </ul>
        </div>

        <div class="section">
            <h2>7. Account Deletion</h2>

            <p>
                Users can request account deletion at any time through our account
                deletion page.
            </p>

            <p>
                <a href="{{ url('/delete-account') }}">
                    Delete My Account
                </a>
            </p>
        </div>

        <div class="section">
            <h2>8. Changes To This Policy</h2>

            <p>
                We may update this Privacy Policy periodically. Any changes will
                be posted on this page with an updated revision date.
            </p>
        </div>

        <div class="section">
            <h2>9. Contact Us</h2>

            <div class="contact-box">
                <p><strong>Email:</strong> support@yourdomain.com</p>
                <p><strong>Website:</strong> https://yourdomain.com</p>
            </div>
        </div>

    </div>

</div>

<div class="footer">
    © {{ date('Y') }} Your Company. All rights reserved.
</div>

</body>
</html>