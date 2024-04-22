<!-- login css style -->
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
    
    .ForgotPassword{
        align-items: center;
        justify-content: center;
        display: flex;
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

	.alert-danger{
		width: 200px;
        text-align: center;
		margin: auto;
		flex-direction: column;	
		background-color: #ffcccc;
		padding: 5px;
		margin-top: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
	}

	.alert-success{
		width: 200px;
        text-align: center;
		margin: auto;
		flex-direction: column;	
		background-color: #7CFC00;
		padding: 5px;
		margin-top: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
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
		display: flex;
        justify-content: center;
        align-items: center;
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
