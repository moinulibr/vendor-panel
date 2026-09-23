<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        :root{
            --primary:#2563eb;
            --text:#334155;
            --heading:#0f172a;
            --border:#e2e8f0;
            --bg:#f8fafc;
            --white:#ffffff;
        }

        body{
            font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;
            background:var(--bg);
            color:var(--text);
            line-height:1.8;
        }

        .header{
            background:#fff;
            border-bottom:1px solid var(--border);
            padding:24px;
            text-align:center;
        }

        .header h1{
            color:var(--heading);
            font-size:36px;
            margin-bottom:8px;
        }

        .header p{
            color:#64748b;
        }

        .container{
            max-width:1000px;
            margin:40px auto;
            padding:0 20px;
        }

        .card{
            background:var(--white);
            border:1px solid var(--border);
            border-radius:16px;
            padding:40px;
        }

        .toc{
            background:#f8fafc;
            border:1px solid var(--border);
            border-radius:12px;
            padding:24px;
            margin-bottom:35px;
        }

        .toc h3{
            margin-bottom:12px;
            color:var(--heading);
        }

        .toc ul{
            padding-left:20px;
        }

        .toc li{
            margin-bottom:6px;
        }

        .section{
            margin-bottom:40px;
        }

        .section h2{
            color:var(--heading);
            margin-bottom:12px;
            font-size:24px;
        }

        .section ul{
            padding-left:20px;
        }

        .section li{
            margin-bottom:8px;
        }

        .info-box{
            background:#eff6ff;
            border-left:4px solid var(--primary);
            padding:16px;
            border-radius:8px;
            margin-top:15px;
        }

        .contact{
            background:#f8fafc;
            border:1px solid var(--border);
            padding:20px;
            border-radius:12px;
        }

        a{
            color:var(--primary);
            text-decoration:none;
        }

        a:hover{
            text-decoration:underline;
        }

        .footer{
            text-align:center;
            padding:30px 20px 50px;
            color:#64748b;
        }

        @media(max-width:768px){

            .header h1{
                font-size:28px;
            }

            .card{
                padding:24px;
            }

            .section h2{
                font-size:20px;
            }
        }
    </style>
</head>
<body>

<header class="header">
    <h1>Privacy Policy</h1>
    <p>Last Updated: September 23, 2026</p>
</header>

<div class="container">

    <div class="card">

        <div class="toc">
            <h3>Contents</h3>

            <ul>
                <li>Information We Collect</li>
                <li>How We Use Information</li>
                <li>Sharing of Information</li>
                <li>Data Security</li>
                <li>Data Retention</li>
                <li>Your Rights</li>
                <li>Account Deletion</li>
                <li>Changes to This Policy</li>
                <li>Contact Information</li>
            </ul>
        </div>

        <div class="section">
            <h2>Introduction</h2>

            <p>
                We respect your privacy and are committed to protecting your
                personal information. This Privacy Policy explains how we collect,
                use, store, and safeguard information when you use our application
                and related services.
            </p>
        </div>

        <div class="section">
            <h2>1. Information We Collect</h2>

            <ul>
                <li>Name and profile information</li>
                <li>Email address</li>
                <li>Phone number</li>
                <li>Device information</li>
                <li>Application usage data</li>
                <li>Support communications</li>
            </ul>
        </div>

        <div class="section">
            <h2>2. How We Use Information</h2>

            <ul>
                <li>Provide and maintain our services</li>
                <li>Improve application performance</li>
                <li>Respond to customer support requests</li>
                <li>Ensure security and prevent abuse</li>
                <li>Meet legal and regulatory obligations</li>
            </ul>
        </div>

        <div class="section">
            <h2>3. Sharing of Information</h2>

            <p>
                We do not sell or rent personal information. Information may be
                shared only with service providers or partners required to operate
                the application, process transactions, or comply with legal obligations.
            </p>
        </div>

        <div class="section">
            <h2>4. Data Security</h2>

            <p>
                We use appropriate technical and organizational measures to protect
                personal information from unauthorized access, disclosure, alteration,
                or destruction.
            </p>
        </div>

        <div class="section">
            <h2>5. Data Retention</h2>

            <p>
                Information is retained only for as long as necessary to provide
                services, comply with legal requirements, resolve disputes, and
                enforce agreements.
            </p>
        </div>

        <div class="section">
            <h2>6. Your Rights</h2>

            <ul>
                <li>Access your personal information</li>
                <li>Request correction of inaccurate information</li>
                <li>Request deletion of your account</li>
                <li>Request information regarding stored data</li>
            </ul>
        </div>

        <div class="section">
            <h2>7. Account Deletion</h2>

            <p>
                You may request permanent deletion of your account and associated
                data at any time.
            </p>

            <div class="info-box">
                Account deletion page:
                <a href="{{ url('/delete-account') }}">
                    {{ url('/delete-account') }}
                </a>
            </div>
        </div>

        <div class="section">
            <h2>8. Changes to This Policy</h2>

            <p>
                We may update this Privacy Policy from time to time. Updated versions
                will be published on this page with a revised effective date.
            </p>
        </div>

        <div class="section">
            <h2>9. Contact Information</h2>

            <div class="contact">
                <p><strong>Company:</strong> Your Company Name</p>
                <p><strong>Email:</strong> support@yourdomain.com</p>
                <p><strong>Website:</strong> https://yourdomain.com</p>
            </div>
        </div>

    </div>

</div>

<footer class="footer">
    © {{ date('Y') }} Your Company Name. All Rights Reserved.
</footer>

</body>
</html>