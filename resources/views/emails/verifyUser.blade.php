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
          Hi {{$user['name']}},
        </h1>
        <div style="color: #636363; font-size: 14px;">
          <p>
            Thank you for creating account on our website, there is one more step before you can use it, you need to activate your account by clicking the link below. Once you click the button, just login to your account and you are set to go.
          </p>
        </div>
        <a href="{{url('auth/verify', $user->verifyUser->token)}}" style="padding: 8px 20px; background-color: #0073e7; color: #fff; font-weight: bolder; font-size: 16px; display: inline-block; margin: 20px 0px; margin-right: 20px; border-radius:50px; text-decoration: none;">Activate my account</a>
        <h4 style="margin-bottom: 10px;">
          Need Help?
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