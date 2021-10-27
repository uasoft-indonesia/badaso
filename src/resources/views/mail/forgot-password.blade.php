<!doctype html>
<html>

<head>
  <meta name="viewport" content="width=device-width">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Email template</title>
  <style>
    * {
      color: black;
    }

    @media only screen and (max-width: 620px) {
      table[class=body] h1 {
        font-size: 28px !important;
        margin-bottom: 10px !important;
      }

      table[class=body] p,
      table[class=body] ul,
      table[class=body] ol,
      table[class=body] td,
      table[class=body] span,
      table[class=body] a {
        font-size: 16px !important;
      }

      table[class=body] .wrapper,
      table[class=body] .article {
        padding: 10px !important;
      }

      table[class=body] .content {
        padding: 0 !important;
      }

      table[class=body] .container {
        padding: 0 !important;
        width: 100% !important;
      }

      table[class=body] .main {
        border-left-width: 0 !important;
        border-radius: 0 !important;
        border-right-width: 0 !important;
      }

      table[class=body] .btn table {
        width: 100% !important;
      }

      table[class=body] .btn a {
        width: 100% !important;
      }

      table[class=body] .img-responsive {
        height: auto !important;
        max-width: 100% !important;
        width: auto !important;
      }
    }

    @media all {
      .ExternalClass {
        width: 100%;
      }

      .ExternalClass,
      .ExternalClass p,
      .ExternalClass span,
      .ExternalClass font,
      .ExternalClass td,
      .ExternalClass div {
        line-height: 100%;
      }

      .apple-link a {
        color: inherit !important;
        font-family: inherit !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
        text-decoration: none !important;
      }

      #MessageViewBody a {
        color: inherit;
        text-decoration: none;
        font-size: inherit;
        font-family: inherit;
        font-weight: inherit;
        line-height: inherit;
      }

      .btn-primary table td:hover {
        background-color: #34495e !important;
      }

      .btn-primary a:hover {
        background-color: #34495e !important;
        border-color: #34495e !important;
      }

      .btnRowBlock {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
      }

      .btnRowBlock .btnWrapper {
        padding: 0 2px;
      }

      .btnWrapper {
        display: block;
      }

      .badaso-btn.btn-primary {
        border: 1px solid #06bbd3;
        border-radius: 3px;
        color: #06bbd3;
        display: inline-block;
        font-size: 16px;
        font-weight: 400;
        line-height: 1.2em;
        padding: 10px 20px;
        text-decoration: none !important;
        transition: background .3s, color .3s;
        margin-bottom: 16px;
      }

      .has-text-weight-bold {
        font-weight: 700 !important;
      }

      .btn-primary {
        background: #06bbd3 !important;
        color: #fff !important;
      }

      .separator {
        margin: 32px 0;
        border: 1px solid #c4c4c4;
      }

      .gray {
        color: #999999 !important;
      }
    }
  </style>
</head>

<body class=""
  style="background-color: #f1f1f1; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-top: 4px solid #06bbd3">
  <span class="preheader"
    style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;"></span>
  <table border="0" cellpadding="0" cellspacing="0" class="body"
    style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f1f1f1;">
    <tr>
      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
      <td class="container"
        style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
        <div style="text-align: center;"><img
        src="{{ Storage::url('photos/shares/logo-144px.png') }}"
            width="25%" alt="Logo Badaso"></div>
        <div class="content"
          style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

          <table class="main"
            style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">
            <tr>
              <td class="wrapper"
                style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                <table border="0" cellpadding="0" cellspacing="0"
                  style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                  <tr>
                    <td>
                      <p>Hi {{ $user->name }},</p>
                      <p>You are receiving this email because we received a password reset request for your account. Here is the verification code for your account: </p>

                      <h1>{{$token}}</h1>

                      <p>All you have to do is copy the verification code and paste it to email verification form to continue the reset password process.</p>

                      <p>Regards,<br />{{ config('app.name') }}</p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

          </table>

        </div>
      </td>
      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
    </tr>
  </table>
</body>

</html>
