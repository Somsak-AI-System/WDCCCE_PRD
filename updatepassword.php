<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once("config.inc.php");
require_once("library/dbconfig.php");

global $site_URL;

if (!isset($_SESSION['id']) || @$_SESSION['id'] == '') {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ai-CRM Update password</title>

    <link rel="stylesheet" href="asset/css/login/style.min.css" />
    <link rel="stylesheet" href="asset/css/custom.css?t=<?php echo time(); ?>" />
    <script src="asset/js/jquery-3.2.1.min.js"></script>

    <style type="text/css">
        @font-face {
            font-family: PromptMedium;
            src: url(asset/fonts/Prompt-Medium.ttf);
        }

        .page-wrapper {
            background: none;
        }

        .container-fluid {
            width: 100wh;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            width: 869px;
            height: 485px;
            border-radius: 5px;
            box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.1);
        }

        .btn-success {
            background-color: #068af0;
            border-color: #068af0;
        }

        .btn-success:hover {
            background-color: #068af0;
            border-color: #068af0;
        }
        
        .container {
            margin-top: 50px;
            margin-left: 20px;
            margin-right: 20px;
        }

        .form-control {
            font-family: PromptMedium;
            width: 100%;
            border: 1px solid #068af0;
            border-radius: 4px;
        }

        .form-button {
            font-family: PromptMedium;
            width: 100%;
            color: #ffffff;
            border: 1px solid #068af0;
            background-color: #068af0;
            border-radius: 4px;
            padding: 8px;
        }

        .form-button:disabled {
            background-color: rgb(226 232 240);
            border: 1px solid rgb(226 232 240);
        }

        .form-button:hover {
            cursor: pointer;
        }

        .hits-message {
            font-family: PromptMedium;
            width: 100%;
            padding: 8px;
            border-radius: 4px;
        }

        .hits-success {
            color: rgb(16 185 129);
        }

        .hits-error {
            color: rgb(239 68 68);
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body style="padding:0; margin:0;">
    <div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body" style="padding: 0px;">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6" style="padding-left: 0px; padding-right: 0px;">
                                <div class="banner" style="width: 100%; height: 100%;">
                                    <img src="themes/softed/images/img_login2.png" style=" border-radius: 5px;">
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="width:100%; height:100%; display:flex; align-items:center; padding:48px;">
                                    <div style="width:100%; display:flex; flex-direction:column; gap:8px">
                                        <div>
                                            <input type="password" class="form-control" id="password" placeholder="New Password" onkeyup="validatePassword()"/>
                                        </div>
                                        <div>
                                            <input type="password" class="form-control" id="cf_password" placeholder="Confirm New Password" onkeyup="validatePassword()" />
                                        </div>
                                        <div>
                                            <button type="button" class="form-button" id="btn-save" disabled onclick="savePassword()">
                                                Change Password
                                            </button>
                                        </div>
                                        <div>
                                            <ul class="hits-message">
                                                <li class="valid-length">ระบุอย่างน้อย 8 ตัว</li>
                                                <li class="valid-uppercase">ระบุตัวพิมพ์ใหญ่ (A-Z) อย่างน้อย 1 ตัว</li>
                                                <li class="valid-number">ระบุตัวเลข (8-9) อย่างน้อย 1 ตัว</li>
                                                <li class="valid-special-char">ระบุอักขระพิเศษ (@ # $ % &) อย่างน้อย 1 ตัว</li>
                                                <li class="valid-confirm">ยืนยันรหัสผ่าน</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    var siteURL = '<?php echo $site_URL; ?>'
    var userid = '<?php echo $_SESSION['id']; ?>'
    var user_password = '<?php echo $_SESSION['user_password']; ?>'
    function validatePassword()
    {
        var password = $('#password').val(),
            cf_password = $('#cf_password').val(),
            all_pass = true; 

        var uppercase = /[A-Z]/,
            number = /[0-9]/,
            special = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;

        if (password.length < 8) {
            $('.valid-length').removeClass('hits-success')
            all_pass = false
        } else {
            $('.valid-length').addClass('hits-success')
        }

        if (!uppercase.test(password)) {
            $('.valid-uppercase').removeClass('hits-success')
            all_pass = false
        } else {
            $('.valid-uppercase').addClass('hits-success')
        }

        if (!number.test(password)) {
            $('.valid-number').removeClass('hits-success')
            all_pass = false
        } else {
            $('.valid-number').addClass('hits-success')
        }

        if (!special.test(password)) {
            $('.valid-special-char').removeClass('hits-success')
            all_pass = false
        } else {
            $('.valid-special-char').addClass('hits-success')
        }  
        
        if(password != '' && password === cf_password){
            $('.valid-confirm').addClass('hits-success')
        } else {
            $('.valid-confirm').removeClass('hits-success')
            all_pass = false
        }

        if(all_pass){
            $('#btn-save').prop('disabled', false)
        } else [
            $('#btn-save').prop('disabled', true)
        ]

        return all_pass
    }

    function savePassword()
    {
        var verifyPassword = validatePassword();
        if(verifyPassword){
            var password = $('#password').val()
            $('#btn-save').prop('disabled', true)
            $.post(`${siteURL}updatepassword_confirm.php`, { record:userid, password, user_password }, function(rs){
                if(rs?.status){
                    window.location.href = siteURL
                } else {
                    $('#password').val('')
                    $('#cf_password').val('')

                    $('.valid-length').addClass('hits-success')
                    $('.valid-uppercase').addClass('hits-success')
                    $('.valid-number').addClass('hits-success')
                    $('.valid-special-char').addClass('hits-success')
                    $('.valid-confirm').removeClass('hits-success')
                }
            }, 'json')
        }
    }
</script>

</html>