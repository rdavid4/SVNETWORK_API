<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Licence Verification</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Company Section Styles */
        .company {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }

        .company img {
            max-width: 100px;
            height: auto;
            margin-right: 10px;
            margin-bottom: 5px;
        }

        .company h3 {
            margin-top: 0;
        }

        /* Call to Action Button Styles */
        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #070510;
            color: #fff;
            text-decoration: none;
            border-radius: 15px;

        }

        /* Results Container Styles */
        .results-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
        }

        /* Footer Styles */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }

        .disclaimer {
            font-size: 12px;
            color: #777;
        }

        .logo {
            width: 200px;
        }

        .logo-container {
            width: 100%;
            padding: 15px 0;
            text-align: center;
            background-color: rgb(255, 255, 255);
        }

        .logo-container img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="logo-container">
            <img class="logo" src="{{ config('app.api_url') . '/storage/images/logo.png' }}" alt="SVNetwork Logo">
        </div>
        <div class="logo-container">
            <h2>Licence verification</h2>
        </div>

        <div class="results-container">

        </div>
        <div class="footer">
            <p class="disclaimer">
                This email was sent by SVNetwork. For legal information and email alerts, visit our <a
                    href="{{ config('app.app_url') . '/legal/terms' }}">Terms & Conditions</a>.
            </p>
            <p class="disclaimer">SVNetwork | Registered Address: 123 Main St, City, Country</p>
        </div>
    </div>

</body>

</html>
