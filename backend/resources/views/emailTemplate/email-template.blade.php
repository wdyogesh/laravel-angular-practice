<?php
// $url = url('/public').'/uploads/static_files/';
?>
<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UGC</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300i,400,400i,700,700i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{asset('../../assets/css/style.css')}}">
</head>

<body style="margin: 0px; padding:0px; -webkit-text-size-adjust:none; -ms-text-size-adjust: none; min-width: 100%;" yahoo="fix">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
            <td>
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="610">
                    <tr style="width: 100%; height: 10px;border-bottom: #5e7786 solid 1px">
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <table width="610" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td bgcolor="#1d5baa" style="background:#2ea7df; color:#ffffff; font-family:Gotham, Helvetica Neue, Helvetica, 'Arial', sans-serif; font-size: 16px; font-weight: 700; padding: 11px 15px;">
                                        <div style="text-align:center"><img src="" alt="logo"></div>
                                    </td>
                                </tr>

                                <?php
                                echo html_entity_decode($template);
                                ?>

                                <tr>
                                    <td bgcolor="#ffffff" style=" font-size: 13px;font-family: 'Lato', sans-serif;color: #777777;padding: 10px 30px;">
                                        <p style="padding: 15px 0;">Best Regards,</p>
                                        <p style=" padding-bottom: 25px;">The XXXX Team</p>
                                    </td>
                                </tr>

                                <tr>
                                    <td bgcolor="#202020" style="color:#ffffff; padding: 10px 15px; font-size: 12px;font-family: 'Montserrat', sans-serif;">
                                        <table style="width: 100%;">
                                            <tr>
                                                <td align="center" style="text-align: center; font-size: 8px;font-family: 'Montserrat', sans-serif;width: 33.33%">Copyright © 2018 <span style="color: #2b9fe0">AJvortex.</span> All Right Reserved.</td>
                                                <td align="center" style="text-align: center;font-size: 8px; font-family: 'Montserrat', sans-serif;   width: 49%;">Free order hotline: <span style="color: #2b9fe0">971-371-1911</span></td>
                                                <td align="center">
                                                    <ul style="list-style-type: none; margin: 0; padding: 0;    float: right;">

                                                        <li style="float: left; cursor: pointer;padding: 0 2px;font-size: 8px;"><i class="fab fa fa-facebook"></i></li>
                                                        <li style="float: left; cursor: pointer;padding: 0 2px;font-size: 8px;"><i class="fab fa fa-twitter"></i></li>
                                                        <li style="float: left; cursor: pointer;padding: 0 2px;font-size: 8px;"><i class="fab fa fa-linkedin"></i></li>
                                                        <li style="float: left; cursor: pointer;padding: 0 2px;font-size: 8px;"><i class="fab fa fa-tumblr"></i></li>
                                                        <li style="float: left; cursor: pointer;padding: 0 2px; font-size: 8px;"><i class="fab fa fa-vimeo"></i></li>
                                                        <li style="float: left; cursor: pointer;padding: 0 2px;font-size: 8px;"><i class="fab fa fa-pinterest"></i></li>

                                                    </ul>

                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
