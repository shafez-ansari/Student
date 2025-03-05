@extends('home.home-master')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
       /* .header {
            background-color: #fff;
            text-align: center;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.35);
        }
        .header img {
            max-height: 60px;
            margin: 0 15px;
        }*/
        .container {
            margin-top: 30px;
            text-align: center;
        }
        .message-box {
            background-color: #fff;
            border: 2px solid #d3d3d3;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.35);
            display: inline-block;
            text-align: center;
            width: 80%;
            max-width: 900px;
            position: relative;
        }
        .message-box .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #000;
            font-size: 18px;
            text-decoration: none;
            cursor: pointer;
        }
        .message-box p {
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 10px;
            color: #000;
        }
        .message-box .unique-id-box {
            font-size: 16px;
            background-color: #f8d7da; /* Light pink background */
            color: #000; /* Black text */
            font-weight: bold;
            border-radius: 5px;
            padding: 10px 15px;
            display: inline-block;
            margin-bottom: 20px;
            text-align: left;
            width: 100%;
            max-width: 100%;
        }
        .check-icon {
            margin: 20px auto;
            width: 80px;
            height: 80px;
        }
        .message-box h2 {
            color: #28a745;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message-box">
            <!-- Close Button -->
            <!-- <a href="#" class="close-btn" onclick="window.history.back();">&times;</a> -->

            <!-- Greeting -->
            <p>Dear {{ $fullName }}</p>

            <!-- Unique ID Box -->
            <div class="unique-id-box">
                Unique ID: {{ $uniqueId }}
            </div>
            
              <br>

            <!-- Check Icon -->
            <img src="{{url('/images/Green.png')}}" alt="Check Icon" class="check-icon">

            <!-- Thank You Message -->
            <h2>Thank you!</h2>
            <p>Our placement team will connect with you!</p>
        </div>
    </div>
</body>
</html>
@endsection