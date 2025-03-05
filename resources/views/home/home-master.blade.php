<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AAFT Connect</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.min.js"></script>
        <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">  
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>  
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
        
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
                position: relative;
            }
            .header h1 {
                margin: 0;
                font-size: 2rem;
            }
            .header p {
                margin: 0;
                font-size: 1.2rem;
            }
            .logout-button {
                position: absolute;
                top: 20px;
                right: 20px;
            }
            .logout-button .btn {
                padding: 10px 15px;
                font-size: 14px;
                background-color: #ff0000;
                border: none;
                color: #fff;
                border-radius: 5px;
                text-decoration: none;
            }
            .logout-button .btn:hover {
                background-color: #ff0000;
            }
            .container {
                max-width: 900px;
                margin: 30px auto;
                padding: 15px;
                text-align: inherit;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.35);
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
                padding-top: 30px;
                display: flex;
                flex-direction: column;
                flex-wrap: wrap;
                justify-content: space-between;
                gap: 15px;
            }
            input[type="email"], input[type="number"] {
                width: 100%;
                height: 40px;
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
                background-color: #ff0000;
            }
            .verify-btn {
                display: block;
                background-color: #d76060;
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
            .btn-primary {
                color: #fff;
                background-color: #ff0000;
                border-color: #ff0000;
            }

            .loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 2s linear infinite;
    position: fixed;
    left: 50%;
    top: 50%;
    margin: -30px 0 0 -30px; /* Center the loader */
    z-index: 1000;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
            <h1>AAFT Connect</h1>
            <p>Students Placement Portal 2025</p>
            <div class="logout-button">
                <a href="/logout" class="btn btn-primary">Logout</a>
            </div>
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
