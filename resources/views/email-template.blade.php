<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
	<!-- FontAwesome for eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
        align-items: center;
        justify-content: center;
        width: 350px;
        font-family: Montserrat;
        margin: auto; /* Added margin:auto to center the container horizontally */
	}
	
	.Container img{
		object-fit: contain;
		width: 80%;
	}

    .ResetPassword {
        flex-direction: column;
        align-items: center;
        text-align: center; 
		position: relative;
    }
	
	.Container input{
		border: 2px solid #6A1043;
  		border-radius: 30px;
		padding: 10px;
		margin:10px 0; 
		width: 80%;
		box-sizing: border-box;  
	}
	
	.Container button{
		border: 2px solid #6A1043;
		color: #fff;
		background-color:#A6708E;
  		border-radius: 30px;
		padding: 10px 20px;
		margin: 10px 0; 
		width: 80%;
		margin-bottom: 28px;
		
	}

	.toggle-password1 {
        position: absolute;
        right: 45px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
		color: #c0c0c0;
    }

	.toggle-password2{
        position: absolute;
        right: 45px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
		color: #c0c0c0;
    }

	.alert-danger{
		width: 200px;
        text-align: center;
		margin: auto;
		flex-direction: column;	
		background-color: #ffcccc;
		padding: 5px;
		margin-bottom: 5px;
        border-radius: 5px;
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

				<!-- get the reset password token !-->
				<input type="hidden" name="token" value="{{ $token }}">
				<input type="text" name="email" placeholder="Email" required>

				<div style="position: relative;">
					<input type="password" name="password" id="p1" placeholder="Password" required>
					<i class="fa fa-eye-slash toggle-password1" id="toggle-password1"></i>
				</div>

				<div style="position: relative;">
					<input type="password" name="password_confirmation" id="p2" placeholder="Confirm Password" required>
					<i class="fa fa-eye-slash toggle-password2" id="toggle-password2"></i>
				</div>

				<button type="submit">Reset Password</button>
			</form>

			<script>
				const togglePassword1 = document.getElementById('toggle-password1');
				const togglePassword2 = document.getElementById('toggle-password2');
				const passwordInput1 = document.getElementById('p1');
				const passwordInput2 = document.getElementById('p2');
				
				togglePassword1.addEventListener('click', () => {
					// Toggle the type attribute
					if (passwordInput1.getAttribute('type') === 'password'){
						passwordInput1.setAttribute('type', 'text');
						togglePassword1.classList.replace('fa-eye-slash', 'fa-eye');
					}else{
						passwordInput1.setAttribute('type', 'password');
						togglePassword1.classList.replace('fa-eye', 'fa-eye-slash');
					}
				});

				togglePassword2.addEventListener('click', () => {
					// Toggle the type attribute
					if (passwordInput2.getAttribute('type') === 'password'){
						passwordInput2.setAttribute('type', 'text');
						togglePassword2.classList.replace('fa-eye-slash', 'fa-eye');
					}else{
						passwordInput2.setAttribute('type', 'password');
						togglePassword2.classList.replace('fa-eye', 'fa-eye-slash');
					}
				});

			</script>	
        </div>
    </div>
    
</body>
</html>