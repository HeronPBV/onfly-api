<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<div style="background-color: #353b48; font-family: 'Roboto', sans-serif; color: white;text-align: center; padding: 1px 0;">

    <div>
        <h1 style="font-family: 'Poppins', sans-serif; background-color: #00a8ff; color: #005580; padding: 15px 50px; width: fit-content; margin: 50px auto;">Nova despesa cadastrada!</h1>
    </div>

    <div style="margin-top: 25px;">
        <p style="color: white">Olá, {{$name}}!</p>
        <p style="color: white">Uma nova despesa foi cadastrada no seu registro.</p>
    </div>

    <div style="text-align: left; margin: 50px 25px;">
        <h2 style="color: white">Descrição:</h2>
        <p style="line-height: 18pt; color: white;">{{$description}}</p>

        <hr style="margin-top: 25px">

        <table style="margin-top: 5px; width: 100%; font-size: 1.2em; font-weight: bold;">
            <tr>
                <td>{{$value}}</td>
                <td style="text-align: right;">{{$date}}</td>
            </tr>
        </table>

    </div>
</div>
</html>
