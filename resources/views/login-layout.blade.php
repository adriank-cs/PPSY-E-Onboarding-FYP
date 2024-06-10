<!-- login css style -->
<style>
    body {
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

    body::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.5);
        /* Add dark overlay on the image */
        z-index: -1;/make sure the background behind the login container/
    }

    .Container {
        background-color: #ffffff;
        border-radius: 15px;
        align-items: center;
        justify-content: center;
        width: 350px;
        /* height: 437px; */
        font-family: Montserrat;
        margin: auto;
        /* Added margin:auto to center the container horizontally */
    }

    .ForgotPassword {
        align-items: center;
        justify-content: center;
        display: flex;
        position: relative;
    }

    .Container img {
        object-fit: contain;
        width: 80%;
    }

    .Login {
        flex-direction: column;
        align-items: center;
        text-align: center;
        /* Added text-align:center to center the text within the container */
        position: relative;
    }

    .Container input[type=text],
    .Container input[type=password] {
        border: 2px solid #6A1043;
        border-radius: 30px;
        padding: 10px;
        margin: 10px 0;
        width: 80%;
        box-sizing: border-box;

    }

    .Container input[type=submit] {
        border: 2px solid #6A1043;
        color: #fff;
        background-color: #A6708E;
        border-radius: 30px;
        padding: 10px 20px;
        margin: 10px 0;
        width: 80%;
    }

    .GoogleLogin {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    /* .GoogleLogin button {
		background-color: #4285F4;
		color: #fff;
		border: none;
		border-radius: 30px;
		padding: 10px 20px;
		cursor: pointer;
		margin-bottom:10px;
	}


    .GoogleLogin button i {
        margin-right: 10px;
    } */
	
	.GoogleLogin {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    .GoogleLogin a {
        width: 60%;
        text-decoration: none;
    }

    .GoogleLogin button {
        background-color: #fff;
        color: #000;
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 30px;
        padding: 10px 20px;
        cursor: pointer;
        margin-bottom: 10px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        /* font-size: 10px; */
		/* made the font bold */
		
    }

    .GoogleLogin button svg {
        margin-right: 10px;
        width: 20px;
        height: 20px;
    }



    .toggle-password {
        position: absolute;
        right: 45px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #c0c0c0;
    }

    .alert-danger {
        width: 200px;
        text-align: center;
        margin: auto;
        flex-direction: column;
        background-color: #ffcccc;
        padding: 5px;
        margin-top: 10px;
        border-radius: 5px;
    }

    .alert-success {
        width: 200px;
        text-align: center;
        margin: auto;
        flex-direction: column;
        background-color: #D8F0D0;
        padding: 5px;
        margin-top: 10px;
        border-radius: 5px;
    }

</style>
