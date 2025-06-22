<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>User Create Mail</title>

        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

        <style type="text/css">
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                line-height: 1.5;
            }

            body {
                font-family: 'Roboto', sans-serif;
                background-color: #f4f7fa;
                color: #333;
            }

            .email-container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #ffffff;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .header {
                padding: 20px;
                text-align: center;
                background-color: #1a237e;
                color: #ffffff;
            }

            .header-title {
                font-size: 24px;
                font-weight: 700;
            }

            .logo-section {
                padding: 20px;
                text-align: center;
                background-color: #ffffff;
            }

            .logo-img {
                max-width: 150px;
                height: auto;
                display: inline-block;
            }

            .content-section {
                padding: 30px 40px;
                text-align: left;
            }

            .greeting {
                font-size: 18px;
                color: #444;
                margin-bottom: 20px;
            }

            .content-message {
                font-size: 16px;
                color: #333;
                margin-bottom: 20px;
            }

            .footer {
                padding: 20px;
                text-align: left;
                background-color: #f4f7fa;
                font-size: 12px;
                color: #666;
            }

            a {
                color: #1a237e;
                text-decoration: none;
            }

            a:hover {
                text-decoration: underline;
            }

            @media (max-width: 600px) {
                .email-container {
                    border-radius: 0;
                }

                .content-section {
                    padding: 20px;
                }

                .header-title {
                    font-size: 20px;
                }

                .greeting {
                    font-size: 16px;
                }

                .content-message {
                    font-size: 14px;
                }
            }
        </style>
    </head>

    <body>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td align="center" style="padding: 20px 0;">
                    <div class="email-container">
                        <div class="header">
                            <h1 class="header-title">{{ $setting->title }}</h1>
                        </div>

                        <div class="logo-section">
                            @if ($setting->logo)
                                <img class="logo-img" src="{{ asset('storage/logo/' . $setting->logo) }}" alt="Sixth Sense" title="Sixth Sense">
                            @else
                                <img class="logo-img" src="https://pms.sebpobd.net/storage/logo/FBJn76CnImPzGDdQ2lLcDOIjAqksI1IvH7K4ZB1p.png" alt="Sixth Sense" title="Sixth Sense">
                            @endif
                        </div>

                        <div class="content-section">
                            <p class="greeting">Hello {{ $newUser->name_en ?? 'User' }},</p>

                            <p class="content-message">Your account has been created. Please login to <a href="{{ url('/') }}" style="text-decoration: none;">{{ $setting->title }}</a>.</p>
                            
                            <p class="content-message">Password: 12345678</p>
                        </div>
                        
                        <div class="footer">
                            <p>&copy; {{ date('Y') }} {{ $setting->title }}. All rights reserved.</p>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>