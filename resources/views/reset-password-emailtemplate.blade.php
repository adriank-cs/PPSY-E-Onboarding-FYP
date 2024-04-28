<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Email Template</title>
    <style>
        /* Resetting default margin and padding */
        body, html {
            margin: 0;
            padding: 0;
        }

        body {
            text-align: center;
            font-family: Montserrat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Set the height of the body to full viewport height */
        }

        .button-container {
            margin-top: 20px;
        }

        .button-container button {
            background-color: #A6708E;
		    color: #fff;
		    border: none;
		    border-radius: 30px;
		    padding: 10px 20px;
		    cursor: pointer;
        }

    </style>
</head>

<body>
    <!--<img src="pp-logo.png" alt="PP Logo" height="100" width="200"/>-->
    <center>
        <p>Click the button below to reset your password:</p>
        <div class="button-container">
            <a href="{{ $resetLink }}" class="button">
                <button>Reset Your Password</button>
            </a>
        </div>
    </center>

</body>
</html>
