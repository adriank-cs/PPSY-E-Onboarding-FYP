<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Have Sent</title>

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
	
	.Text{
        flex-direction: column;
        align-items: center;
        text-align: center;
		background-color: #D8F0D0;
		display: block;
		display: flex; 
		border-radius: 15px;
		width: 300px;
		margin-bottom:25px;
	}
	
	.ReturnLogIn{
		padding-bottom:25px;
	}
	</style>

</head>

<body>
    <div class="Container">
		<!--<img src="ppLogo.png" alt="PP Logo" height="100" width="200"/>-->
		<!--Will use the logo from the database-->
		<br>
		
        <div class = "Text">
            <p>We've sent an email containing the link to reset your password to your email address!</p>
        </div>
    
        <div class="ReturnLogIn">
            <a href="{{ route('login') }}" style="color:black" class="btn">Log In</a>
        </div>

    </div>

    
</body>
</html>