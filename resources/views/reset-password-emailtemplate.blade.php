<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Email Template</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        body {
            text-align: center;
            font-family: Montserrat, Arial, sans-serif;
            background-color: #fff;
            color: #333;
            padding: 20px;
        }

        .email-container {
            max-width: 700px; /* Increased max-width */
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }

        .header {
            background-color: #6A1043;
            padding: 10px;
            height: 80px;
            align-items: center;
            justify-content: center;
        }

        .header img {
            max-height: 80px; /* Adjust this value to fit within the header height */
            max-width: 200%;
            height: auto;
            display: block;
        }

        .content {
            padding: 20px;
        }

        .content p {
            font-size: 1.1em;
        }

        .button-container {
            margin-top: 20px;
        }

        .button-container a {
            background-color: #A6708E;
            color: #fff;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 1.1em;
        }

        .divider {
            border-top: 1px solid #ccc;
            margin: 20px 40px; /* Added margin for space on left and right */
        }

        .footer {
            font-size: 0.9em;
            color: 	#3b3b3b;
            padding: 20px;
        }

        .footer a {
            color: #A6708E;
            text-decoration: none;
        }

        .footer p {
            margin: 0;
        }

        .copyright {
            font-size: 0.8em;
            color: #fff;
            margin-top: 10px;
            background-color:#999;
        }
    </style>
</head>
<body>
    <center>
    <div class="email-container">
        <div class="header">
            <img src="{{ $message->embed($logoPath) }}" alt="Company Logo" />
        </div>

        <h1>Forgot Your Password?</h1>
        
        <div class="content">
            <p>Hi {{ $name }},</p>
            <p>You can reset your password by clicking on the link below.</p>
            <div class="button-container">
                <a href="{{ $resetLink }}">Reset Your Password</a>
            </div>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p>If you didn't perform this action, just ignore this email.</p>
            <p>For customer care, please contact <a href="mailto:pphumanresourcemanagement@gmail.com">pphumanresourcemanagement@gmail.com</a>.</p>
        </div>

        <div class="copyright">
            <p>Copyright Reserved © 2024 Peolple Psyence Human Resource Management. All Rights Reserved.</p>
        </div>
    </div>
    </center>
</body>
</html>
