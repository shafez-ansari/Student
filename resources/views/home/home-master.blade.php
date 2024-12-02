<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AAFT Corporate Resource Center</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                background-color: #F2F2F2;
            }
            .header {
                background-color: #333;
                color: #fff;
                padding: 20px;
                text-align: center;
            }
            .header h1 {
                margin: 0;
                font-size: 2rem;
            }
            .header p {
                margin: 0;
                font-size: 1.2rem;
            }
            .container {
                max-width: 900px;
                margin: 30px auto;
                padding: 15px;
                text-align: center;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            .logos {
                margin-top: 20px;
                display: flex;
                justify-content: space-around;
                align-items: center;
                flex-wrap: wrap;
                margin-bottom: 30px;
            }
            .logos img {
                margin: 10px;
                max-height: 50px;
                max-width: 100%;
            }
            .form-container {
                padding: 20px;
                border-radius: 8px;
                text-align: left;
            }
            .form-container h2 {
                font-size: 1.8rem;
                margin-bottom: 20px;
            }
            form {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                gap: 15px;
            }
            input[type="email"], input[type="number"] {
                width: 100%;
                padding: 10px;
                border: 1px solid #000;
                border-radius: 5px;
            }
            button {
                padding: 10px 20px;
                background-color: #28A745;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                width: 100%;
            }
            button:hover {
                background-color: #218838;
            }
            .verify-btn {
                display: block;
                background-color: #333;
                color: #fff;
                padding: 10px 20px;
                text-align: center;
                border-radius: 5px;
                cursor: pointer;
                width: 100%;
            }
            .illustration {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 15px;
                margin-top: 20px;
            }
            .illustration img {
                max-width: 75%;
                height: auto;
                border-radius: 10px;
            }
            footer {
                margin-top: 20px;
                font-size: 12px;
                color: #888;
                text-align: center;
            }
            /* Responsive Design */
            @media screen and (max-width: 768px) {
                .container {
                    padding: 10px;
                }
                .logos img {
                    max-height: 40px;
                }
                .header h1 {
                    font-size: 1.5rem;
                }
                .header p {
                    font-size: 1rem;
                }
                .form-container h2 {
                    font-size: 1.5rem;
                }
                button {
                    padding: 10px;
                }
            }
            @media screen and (max-width: 480px) {
                .header {
                    padding: 10px;
                }
                .header h1 {
                    font-size: 1.2rem;
                }
                .header p {
                    font-size: 0.9rem;
                }
                .logos {
                    flex-direction: column;
                }
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>AAFT CORPORATE RESOURCE CENTER</h1>
            <p>Students Placement Portal 2025</p>
        </div>
        <div class="container">
            <div class="logos">
                <img src="{{url('/images/AAfTonline.png')}}" alt="AAFT Online">
                <img src="{{url('/images/Aaft.png')}}" alt="AAFT Logo">
                <img src="{{url('/images/University.png')}}" alt="AAFT University Logo">
            </div>
            @yield('content')
            <div class="illustration">
                <div class="row">
                    <div class="col-sm-6">
                        <img src="{{url('/images/ill.png')}}" alt="Graduation Illustration 1">
                    </div>
                    <div class="col-sm-6">
                        <img src="{{url('/images/Ill2.png')}}" alt="Graduation Illustration 2">
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <p>Made with AAFT Technologies</p>
        </footer>
    </body>
</html>






