<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AAFT Corporate Resource Center</title>
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
                background-color: #ff0000;
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
        </div>
        <div class="container">
            <div class="logos">
                <img src="{{url('/images/aaftt.jpg')}}" alt="AAFT Online">

            </div>
            <div id="loader" class="loader" style="display: none;"></div>
            <div class="form-container" style="max-width: 500px; margin: auto; border: 1px solid #ccc; border-radius: 10px; padding: 20px; background-color: #fff; box-shadow: 0px 4px 6px rgba(0,0,0,0.35);">
                <div style="background-color: #333; padding: 10px 15px; border-radius: 8px 8px 0 0;">
                    <h2 style="color: white; font-size: 16px; margin: 0;">Verify Yourself</h2>
                </div>
                <div style="padding: 15px;">
                    <label style="width: 100%; color: #333; font-size: 14px; margin-bottom: 5px;">Enter your unique ID or Email</label>
                    <div class="row">
                        <div class="col-md-12 eml-bx" style="margin-bottom: 15px;">
                            <div class="mbx" style="position: relative;">
                                <input type="email" name="loginEmail" class="form-control" id="loginEmail" placeholder="Email or UID" style="padding-right: 80px; border-radius: 4px; border: 1px solid #ddd;">
                                <a class="button vrf-btn" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background-color: #28a745; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; cursor: pointer; font-size: 12px;" id="verifyBtn" onclick="verifyOtp()">Verify</a>
                            </div>
                            <span class="text-danger" id="emailError" style="font-size: 12px;"></span>
                        </div>
                        <div class="col-md-12" style="margin-bottom: 15px;">
                            <input type="number" class="form-control" name="loginOtp" style="display: none; border-radius: 4px; border: 1px solid #ddd;" id="loginOtp" placeholder="OTP">
                            <span class="text-danger" id="otpError" style="font-size: 12px;"></span>
                        </div>
                    </div>

                    <a class="button" id="resendBtn" style="display: none; color:rgb(0, 0, 0); font-size: 12px; cursor: pointer; margin-bottom: 15px;" onclick="resendOtp()">Resend</a>

                    <button id="loginSubmit" style="display: none; width: 100%; background-color: #f80901; color: white; border: none; padding: 10px 0; border-radius: 4px; cursor: pointer; font-size: 14px;" onclick="submitOtp()">Submit</button>
                </div>
            </div>
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

            <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<script type="text/javascript">
    function verifyOtp() {
        var email = $('#loginEmail').val();
        if (email == "") {
            $("#emailError").empty().html("Please enter email or unique ID");
        } else {
            $('#loader').show();
            $.ajax({
                type: 'get',
                url: "/verify-email",
                data: { 'email': email },
                success: function (data) {
                    $('#loader').hide();
                    $("#verifyBtn").hide();
                    if (data.loginMsg == "Invalid email or unique Id") {
                        $("#emailError").empty().html(data['loginMsg']);
                        $("#verifyBtn").show();
                    }
                    else if(data.loginMsg == "Invalid OTP"){
                        $("#emailError").empty().html(data['loginMsg']);
                    }
                    else {
                        $("#emailError").empty().html('');
                        $("#loginOtp").show();
                        $("#otpError").show();
                        $("#loginSubmit").show();
                        $("#resendBtn").show();
                        $("#loginEmail").attr("disabled", "disabled");
                    }
                }
            });
        }
    }

    function resendOtp() {
        var email = $('#loginEmail').val();
        $('#loader').show();
        $("#emailError").empty().html('');
        $.ajax({
            type: 'get',
            url: "/resend-otp",
            data: { 'email': email },
            success: function (data) {
                $('#loader').hide();
                if (data['loginMsg'] == "OTP resend generated") {
                    $.notify("OTP re-generated successfully", "success");
                }
            }
        });
    }

    function submitOtp() {
        var email = $('#loginEmail').val();
        var otp = $('#loginOtp').val();
        if (otp == "") {
            $("#otpError").empty().html("Please enter OTP");
        } else {
            $('#loader').show();
            $.ajax({
                type: 'get',
                url: "/submit-otp",
                data: { 'email': email, 'otp': otp },
                success: function (data) {
                    $('#loader').hide();
                    if (data['loginMsg'] == "Invalid OTP") {
                        $("#otpError").empty().html(data['loginMsg']);
                        
                    } else {
                        window.location = data['loginMsg'];
                    }
                }
            });
        }
    }
</script>

