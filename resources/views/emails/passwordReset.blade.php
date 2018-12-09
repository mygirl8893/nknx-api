<!DOCTYPE html>
<html>
  <body style="background-color: #222533; padding: 20px; font-family: font-size: 14px; line-height: 1.43; font-family: &quot;Helvetica Neue&quot;, &quot;Segoe UI&quot;, Helvetica, Arial, sans-serif;">
    <div style="max-width: 600px; margin: 0px auto; background-color: #fff; box-shadow: 0px 20px 50px rgba(0,0,0,0.05);">
      <table style="width: 100%;">
        <tr>
          <td style="background-color: #fff;">
            <img alt="" src="https://nknx.org/static/img/site-logo_color.png" style="width: 90px; padding: 15px">
          </td>
          <td style="padding-left: 50px; text-align: right; padding-right: 20px;">
            <a href="https://nknx.org/login" style="color: #261D1D; text-decoration: underline; font-size: 14px; letter-spacing: 1px;">Sign In</a><a href="https://nknx.org/forgot-password" style="color: #7C2121; text-decoration: underline; font-size: 14px; margin-left: 20px; letter-spacing: 1px;">Forgot Password</a>
          </td>
        </tr>
      </table><div style="padding: 60px 70px; border-top: 1px solid rgba(0,0,0,0.05);">
        <h1 style="margin-top: 0px;">
          Hi Chris,
        </h1>
        <div style="color: #636363; font-size: 14px;">
          You recently requested to reset your password for your nknX account. Click the button below to reset it.
        </div>
        <a href="{{url('https://nknx.org/newpassword/'.$user->passwordReset->token)}}" style="padding: 8px 20px; background-color: #4B72FA; color: #fff; font-weight: bolder; font-size: 16px; display: inline-block; margin: 20px 0px; margin-right: 20px; text-decoration: none;">Reset your password</a>
        <div style="color: #636363; font-size: 14px; margin-top: 30px;">
          If you did not request a password reset, pelase ignore this email or reply to let us know. This password reset is only valid for the next 30 minutes.<br><br>Thanks,<br>Your nknX team
        </div>
        <h4 style="margin-bottom: 10px;">
          Questions?
        </h4>
        <div style="color: #A5A5A5; font-size: 12px;">
          <p>
            If you have any questions you can simply reply to this email or find our contact information below. Also contact us at <a href="mailto:hello@nknx.org<" style="text-decoration: underline; color: #0073e7;">hello@nknx.org</a>
          </p>
        </div>
      </div><div style="background-color: #F5F5F5; padding: 40px; text-align: center;">
        <div style="color: #A5A5A5; font-size: 12px; margin-bottom: 20px; padding: 0px 50px;">
          You are receiving this email because you signed up for nknX - A smart blockchain explorer for NKN.
        </div>
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.05);">
          <div style="color: #A5A5A5; font-size: 10px;">
            Copyright 2018 nknX. All rights reserved.
          </div>
        </div>
      </div>
    </div>
  </body>
</html>