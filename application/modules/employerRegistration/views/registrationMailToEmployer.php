<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Registration done sucessfully</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
<div>
   <!--<div style="font-size: 26px;font-weight: 700;letter-spacing: -0.02em;line-height: 32px;color: #41637e;font-family: sans-serif;text-align: center" align="center" id="emb-email-header">
   <img style="border: 0;-ms-interpolation-mode: bicubic;display: block;Margin-left: auto;Margin-right: auto;max-width: 152px" src="file:///D:/xampp/htdocs/RecruitmentProject/assets/logo.png" alt="" width="152" height="108">
   </div> -->

<p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"><b>Greetings from Emagine People Solutions!!</b></p>
<p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">

Thank you for choosing us as your recruitment partner!!<br><br>

<?php if ($password != null && $password != "") {
    echo "We've generated a password for you. You can use below credentials to login:<br/>
    Username:<b>" . $email . "</b>
    <br/>Password:<b>" . $password . "</b><br><br>";
}
?>

We will be happy to have you onboard.<br><br>

Our Team will get back to you shortly for the Onboarding process!!<br><br>

If you have any queries, Please Call or Whatsapp on 8291812575.<br><br>

<b>Or Drop us an email on <u>new.clientpartner@emagine.co.in</u> </b>


</p>

</div>
</body>
</html>
