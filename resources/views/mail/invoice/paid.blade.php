<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Match</title>
    <style>
        /* Reset de estilos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilo general del cuerpo */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }

        /* Contenedor de la lista */
        .list-container {
            max-width: 600px;
            margin: 0 auto;
        }

        /* Estilo de la lista */
        .company-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        /* Estilo de cada elemento de la lista */
        .company-item {
            background-color: #fff;
            border-radius: 4px;
            margin-bottom: 10px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        /* Estilo del logo */
        .company-logo {
            width: 50px;
            height: 50px;
            margin-right: 20px;
        }

        /* Estilo del nombre de la compañía */
        .company-name {
            flex: 1;
        }
    </style>
</head>
<body>

<div class="list-container">
    <div>

    </div>
    <h2>Your matches for service {{$matches['service']['name']}}</h2>
    <div style="margin-bottom: 15px"></div>
    <ul class="company-list">
        @foreach ($matches['matches'] as $company)
        <a href="{{$company->public_url}}" class="company-link">
            <li class="company-item">
                <img src="{{$company->logo_url}}" alt="Company Logo" class="company-logo">
                <span class="company-name">{{$company->name}}</span>
            </li>
        </a>
        @endforeach
    </ul>
</div>

</body>
</html>
