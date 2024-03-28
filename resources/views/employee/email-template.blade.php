<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

    <style>

	body{
		background-image: url('../images/loginBG.png');
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

    .ResetPassword {
        flex-direction: column;
        align-items: center;
        text-align: center; /* Added text-align:center to center the text within the container */
    }
	
	.Container input{
		border: 2px solid #6A1043;
  		border-radius: 30px;
		padding: 10px 10px;
		margin: 5px 0; 
		width: 80%;
		box-sizing: border-box;     
	}
	
	.Container button{
		border: 2px solid #6A1043;
		color: #fff;
		background-color:#A6708E;
  		border-radius: 30px;
		padding: 10px 20px;
		margin:10px 0; 
		width: 80%;
	}

	.show-hide-checkbox {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center; /* Center horizontally */ 
			
    }

	

	
	</style>
</head>

<body>
    <div class="Container">
		<br>
        <div class = "ResetPassword">
			<form action="{{ route('reset_password') }}" method="POST">
				@csrf
				@if(session()->has('error'))
				<div class="alert-danger" role="alert">
					{{session('error')}}
				</div>
				@endif

				@if(session()->has('success'))
				<div class="alert-success" role="alert">
					{{session('success')}}
				</div>
				@endif

				<!-- get the reset password token !-->
				<input type="hidden" name="token" value="{{ $token }}">
				
				<input type="email" name="email" placeholder="Email" required>
				<input type="password" name="password" id="p1" placeholder="Password" required>
				<input type="password" name="password_confirmation" id="p2" placeholder="Confirm Password" required>
            	<br>

				<div class="show-hide-checkbox">
					<input type="checkbox" onclick="myFunction()"> Show Password
				</div>

				<button type="submit">Reset Password</button>
			</form>

			<script>
				function myFunction() {
                var passwordField = document.getElementById("p1");
                var confirmPasswordField = document.getElementById("p2");

                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    confirmPasswordField.type = "text";
                } else {
                    passwordField.type = "password";
                    confirmPasswordField.type = "password";
                }
            }
				
				
			</script>	
        </div>
    </div>
    
</body>
</html>