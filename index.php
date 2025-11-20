<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CubiSchool</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins';font-size: 22px; font-weight: medium;
            background-color: #FF5900;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #FF7A3D 0%, #FF5900 60%);
        }

        main {
            padding: 20px;
        }

        .event {
            padding: 20px;
            background-color: #fff;
            border-radius: 30px;
            width: 310px;
            height: 480px;
            margin: auto;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.18);
            animation: fadeIn 0.5s ease;
        } 

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .event img {
            display: block;
            margin: 0 auto;
        }

        h2 {
            color: black;
            margin-bottom: 10px;
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-style: normal;
            margin-top: 0;
            text-align: center;
        }

        label {
            color: #FA9E31;
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
            margin-top: 0;
            display: block;
            text-align: left; 
            font-size: 18px;
        }

        p {
            color: #000000;
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            text-align: center;
            font-size: 18px;
            margin-top: 10px;
            margin-bottom: 0px;
        }

        .button, .button2 {
            display: block;
            width: 100%;
            font-family: "Poppins", sans-serif;
            font-style: normal;
            text-align: center;
            cursor: pointer;
            border-radius: 41px;
            text-decoration: none;
            margin: 10px auto;
            padding: 8px 0;
            margin-top: 0px;
            margin-bottom: 0px;
        }

        .button {
            background-color: #FF5900;
            color: white;
            border: none;
            transition: background-color 0.3s;
            font-size: 14px;
            font-weight: 400;
        }

        .button:hover {
            background-color: #60421F;
        }

        .button2 {
            background-color: transparent;
            color: #000000;
            border: none;
            font-size: 16px;
            font-weight: 600;
        }

        .button2:hover {
            color: blue;
        }

        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #000000;
            border-radius: 42px;
            margin-bottom: 10px;
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            margin-top: 10px;
            font-family: "Poppins", sans-serif;
        }

        input[type="text"], input[type="password"] {
            background-size: 20px 20px;
            background-position: 13px center;
            background-repeat: no-repeat;
            padding-left: 40px;
        }

        .input-container {
            position: relative;
            width: 100%;
            margin-top: 10px;
        }

        .input-container .icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #FA9E31; 
            font-size: 18px;
        }

        .input-container input {
            width: 100%;
            padding: 10px 15px 10px 45px;
            border: 1px solid #000000;
            border-radius: 42px;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            font-size: 16px;
        }


    </style>
</head>
<body>

<main>
    <div class="event">
        <form action="auth/login_user.php" method="post">
            <img width="106" src="./img/A-gk32bu.png" >
            <h2>CubiSchool</h2>
            <label>ID IEST:</label>
            <div class="input-container">
                <i class="fa-solid fa-user icon"></i>
                <input type="text" name="idIest" required>
            </div>
            <br>
            <label>Contraseña:</label>
            <div class="input-container">
                <i class="fa-solid fa-lock icon"></i>
                <input type="password" name="password" required>
            </div>
            <br>
            <button type="submit" class="button">Iniciar Sesión</button>
            <p>o</p>
            <a href="/cubi/auth/register.php" class="button2">Regístrate ya</a>

        </form>
    </div>
</main>

</body>
</html>