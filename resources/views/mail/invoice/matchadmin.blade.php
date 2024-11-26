<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Results</title>
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

         /* Estilos generales */
    ul {
      padding: 0;
      margin: 0;
      list-style-type: none;
    }
    li {
      padding: 10px;
      background-color: #f0f0f0;
      margin-bottom: 10px;
    }

    /* Estilos para pantallas grandes */
    @media (min-width: 600px) {
      .columns {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
      }
    }

    /* Estilos para pantallas móviles */
    @media (max-width: 599px) {
      .columns {
        display: block;
      }
      li {
        width: 100%;
      }
    }
    </style>
</head>

<body>
    <div class="container">

        <div class="logo-container">
            <img class="logo" src="{{ config('app.api_url') . '/storage/images/logo.png' }}" alt="SVNetwork Logo">
        </div>
        <div class="logo-container">
            <h2>We’ve found these results for your {{ $company['service']['name'] }} project</h2>
        </div>

        <div class="results-container">

            <div class="company">
                <h3>Company name: {{ $company['company_name'] }}</h3>
                <p>Phone: <a href="tel:+{{ $company['company_phone'] }}"> {{ $company['company_phone'] }}</a> </p>
                <p>Address: {{ $company['company_address'] }}</p>
            </div>

        </div>
        <div class="footer">
            <p class="disclaimer">
                This email was sent by SVNetwork. For legal information and email alerts, visit our <a
                    href="{{ config('app.app_url') . '/legal/terms' }}">Terms & Conditions</a>.
            </p>

        </div>
        <div>
            <h2>Looking for your next home improvement project? Here are some of the most in-demand services in your
                area:</h2>
                <ul class="columns">
            @foreach ($company['services'] as $service)
                    <li style="width: 100%">
                        <a href="https://app.thesvnetwork.com/search/{{$service->slug}}" style="width: 100%">
                            <div style="width: 100%">
                                <img src="{{ $service->image }}" alt="Logo {{ $service->name }}" style="border-radius: 15px; width: 100%; max-width: 200px;">
                                <h3>{{ $service->name }}</h3>
                            </div>
                        </a>
                    </li>

            @endforeach
                </ul>

        </div>
    </div>



</body>

</html>
