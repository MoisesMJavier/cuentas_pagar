<?php
function plantilla ($contenido=""){
  return '<!DOCTYPE html>
  <html lang="es">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <meta name="x-apple-disable-message-reformatting">
      <title></title>
      <style>
        body{font-family:Arial,sans-serif;}
        table, td, div, h1, p {font-family: Arial, sans-serif;}
        .f16{
          font-size: 16px;
        }
      </style>
    </head>
    <body style="margin:0;padding:0;">
      <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
        <tr>
          <td align="center" style="padding:0;">
            <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
              <tr>
                <td align="center" style="padding:25px 0 20px 0;background:#00263A;">
                  <img src="https://prueba.gphsis.com/imgscorreo/logo_blanco.png" alt="" width="365px" style="height:auto;display:block;" />
                </td>
              </tr>
              <tr>
                <td style="padding:36px 30px 42px 30px;">
                  <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                    <tr>
                      <td style="padding:0 0 36px 0;color:#153643;">
                        '.$contenido.'
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="padding:30px;background:#00263A;">
                  <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                    <tr>
                      <td style="padding:0;width:50%;" align="left">
                        <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                          &reg; <a href="https://ciudadmaderas.com" style="color:#ffffff;text-decoration:underline;">Ciudad Maderas</a> '.date("Y").'
                        </p>
                      </td>
                      <td style="padding:0;width:50%;" align="right">
                        <table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
                          <tr>
                            <td style="padding:0 0 0 10px;width:38px;">
                              <a href="https://twitter.com/cd_maderas?lang=es" style="color:#ffffff;">
                                <img src="https://prueba.gphsis.com/imgscorreo/twitter.png" alt="Twitter" width="38" style="height:auto;display:block;border:0;" />
                              </a>
                            </td>
                            <td style="padding:0 0 0 10px;width:38px;">
                              <a href="https://www.facebook.com/CiudadMaderasSitioOficial/" style="color:#ffffff;">
                                <img src="https://prueba.gphsis.com/imgscorreo/facebook.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;" />
                              </a>
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
  </html>';
}
?>