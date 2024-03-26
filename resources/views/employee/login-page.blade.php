<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People Psynece Login Page</title>

    <style>

	body{
		background-image: url('images/loginBG.png');
		background-repeat: no-repeat;
  		background-attachment: fixed;
  		background-size: 100% 100%;
		margin: 0;
		display: flex;
		justify-content: center;
		align-items: center;
		height: 100vh;
		
	}
	
	body::before{
		content: "";
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		background: rgba(0, 0, 0, 0.5); /* Add dark overlay on the image */
		z-index:-1; /*make sure the background behind the login container*/
		
	}
	
	.Container{
        background-color: #ffffff;
        border-radius: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 350px;
        font-family: Montserrat;
        margin: auto; /* Added margin:auto to center the container horizontally */
        position: relative;
	}
	
	.Container img{
		object-fit: contain;
		width: 80%;
	}

    .Login {
        flex-direction: column;
        align-items: center;
        text-align: center; /* Added text-align:center to center the text within the container */
    }
	
	.Container input[type=text],
	.Container input[type=password]{
		border: 2px solid #6A1043;
  		border-radius: 30px;
		padding: 10px;
		margin:10px 0; 
		width: 80%;
		box-sizing: border-box;
        
        
	}
	
	.Container input[type=submit]{
		border: 2px solid #6A1043;
		color: #fff;
		background-color:#A6708E;
  		border-radius: 30px;
		padding: 10px 20px;
		margin:10px 0; 
		width: 80%;
	}
	
	.GoogleLogin{
		margin-top:20px;
	}
	
	.GoogleLogin button {
		background-color: #4285F4;
		color: #fff;
		border: none;
		border-radius: 30px;
		padding: 10px 20px;
		cursor: pointer;
		margin-bottom:25px;
	}
	</style>
</head>

<body>

    <div class="Container">
		<!--<img src="ppLogo.png" alt="PP Logo" height="100" width="200"/>-->
		<!--Will use the logo from the database-->
		<br>

        <div class = "Login">
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="passsword" placeholder="Password" required>
            <br>
            <input type="submit" value="Login">

        </div>
    
        <div class="Forgot Password">
            <a href="/forgot-password-page" style="color:black" class="btn">Forgot Password</a>
        </div>
        
        <div class="GoogleLogin">
			<a href="{{ route('google-auth') }}">
                <button>Continue with Google</button>
            </a>
        </div>
		
		<?php
			if(session('error')){
				echo "<script>alert('INVALID USER.');</script>";
			}
		?>

    </div>
    
</body>
</html>