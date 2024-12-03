<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Results</title>
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
            border-radius: 15px !important;
        }

        /* Company Section Styles */
        .company {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;

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
            background-color: #fa5f1e;
            color: #fff;
            text-decoration: none;
            border-radius: 15px;

        }

        .cta-button-2 {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffffff;
            color: #323232;
            text-decoration: none;
            border-radius: 15px;
            border: 1px solid #fa5f1e;

        }

        /* Results Container Styles */
        .results-container {

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
            border-radius: 8px;
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

        .icon {
            font-size: 40px;
            padding: 10px;
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
            transition: transform 0.3s;
            border-radius: 15px;
            background-color: rgb(206, 206, 206);
        }

        .e-font-icon-svg {
            fill: rgb(89, 89, 89, 0.5);
            width: 40px;
            align-items: center;
        }

        .feature {

            text-align: center;
            background-color: rgb(255, 255, 255);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            padding: 15px;
            display: grid;
            margin-bottom: 15px;

            .icon {
                justify-self: center;
            }

            h3 {
                color: rgb(34, 34, 34);
            }

            p {
                color: rgb(41, 41, 41);
            }
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* 3 columnas iguales */
            grid-template-rows: auto;
            /* Filas dinámicas basadas en el contenido */
            gap: 16px;
            /* Espaciado entre filas y columnas */

            background-color: #f9f9f9;
            /* Fondo del contenedor */
        }

        .grid-item {

            text-align: center;
            /* Texto centrado */
            font-size: 16px;
            /* Tamaño de la fuente */
            border-radius: 8px;
            /* Bordes redondeados */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Sombra */
        }

        .header {
            text-align: left;
            padding: 15px;
        }

        .lead-container {
            background-color: rgb(255, 255, 255);
            border-radius: 15px;
        }

        .service-name {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .question {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .answer {
            border-radius: 8px;
        }

        .image {
            max-width: 100%;
            width: 100%;
        }

        .dotted-border {
            border: 3px dotted #e45700;
            /* Cambia el color y grosor según desees */
            padding: 16px;
            /* Espaciado interno opcional */
            border-radius: 8px;
            /* Bordes redondeados opcionales */
            margin-bottom: 15px;
        }

        .alert-info {
            border-radius: 8px;
            background-color: #8bfffd;
            padding: 8px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="lead-container">
            <div class="logo-container">
                <a href="https://app.thesvnetwork.com"> <img class="logo"
                        src="{{ config('app.api_url') . '/storage/images/logo.png' }}" alt="SVNetwork Logo"></a>
                <div class="header">
                    <h2>Hi {{ $data->company_name }}.</h2>
                    <p>We’re reaching out with a unique opportunity! A customer in your area is looking for
                        <span>{{ $data['service']->name }}</span>
                        services, and we believe your company might be the perfect fit.
                    </p>
                    <p class="">
                        At this moment, we don’t have a registered company offering this service in this area, and your
                        business stood out as a great match.
                    </p>
                    <p class="alert-info">Here’s the best part: this lead is completely free and comes with no strings
                        attached. </p>

                </div>
            </div>

            <div class="results-container">

                <div class="company">
                    <h3>Project - {{ $data->title }}</h3>

                    <div style="padding-bottom: 15px">{{ $data->description }}</div>

                    <div>
                        <ul class="">
                            @if (!empty($data->answers))
                                @foreach ($data->answers as $answer)
                                    <li>
                                        <div class="question">
                                            {{ $answer->question->text }}
                                        </div>
                                        <div class="answer"> {{ $answer->text }} </div>
                                    </li>
                                @endforeach
                            @endif
                            @if (!empty($data->openAnswers))
                                @foreach ($data->openAnswers as $answer)
                                    <li>
                                        <div class="question">
                                            {{ $answer->question_text }}
                                        </div>
                                        <div class="answer"> {{ $answer->text }} </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <div>
                        @if (!empty($data->images))
                            @foreach ($data->images as $image)
                                <a href="{{ $image->url }}" target="_blank">
                                    <img class="image" src="{{ $image->url }}" alt="" srcset="">
                                </a>
                            @endforeach
                        @endif
                    </div>

                </div>

            </div>
            <div class="results-container">
                <h2>Lead information</h2>
                <div class="dotted-border">
                    <h3>{{ $data['user']['name'] }} {{ $data['user']['surname'] }}</h3>
                    <p>Phone: <a href="tel:+{{ $data['user']['phone'] }}"> {{ $data['user']['phone'] }}</a> </p>
                    <p>Email: <a href="mailto: {{ $data['user']['email'] }}"> {{ $data['user']['email'] }}</a>
                    </p>
                </div>
                <div class="alert-info">
                    Please make sure to inform our client that you’re calling on behalf of SVNetwork in response to the
                    inquiry they submitted through our app.
                </div>
            </div>

        </div>
        <div class="footer">
            <p class="disclaimer">
                This email was sent by SVNetwork. For legal information and email alerts, visit our <a
                    href="{{ config('app.app_url') . '/legal/terms' }}">Terms & Conditions</a>.
            </p>

        </div>
        <div>
            <h2>At SVNetwork, we’re all about connecting customers with the right professionals. If you’d like more
                opportunities like this, join our platform.</h2>
            <a class="q-btn q-btn-item non-selectable no-outline q-btn--outline q-btn--rectangle q-btn--rounded text-white q-btn--actionable q-focusable q-hoverable"
                tabindex="0" href="https://app.thesvnetwork.com/auth/register/pro" target="_blank"><span
                    class="q-focus-helper"></span><span class="cta-button">Register to receive more leads like
                    this</span></a>
            <div>
                <h2 class="">Why Choose Us</h2>
                <div class="grid-container">
                    <div class="grid-item feature">

                        <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-people-arrows"
                                viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M96,128A64,64,0,1,0,32,64,64,64,0,0,0,96,128Zm0,176.08a44.11,44.11,0,0,1,13.64-32L181.77,204c1.65-1.55,3.77-2.31,5.61-3.57A63.91,63.91,0,0,0,128,160H64A64,64,0,0,0,0,224v96a32,32,0,0,0,32,32V480a32,32,0,0,0,32,32h64a32,32,0,0,0,32-32V383.61l-50.36-47.53A44.08,44.08,0,0,1,96,304.08ZM480,128a64,64,0,1,0-64-64A64,64,0,0,0,480,128Zm32,32H448a63.91,63.91,0,0,0-59.38,40.42c1.84,1.27,4,2,5.62,3.59l72.12,68.06a44.37,44.37,0,0,1,0,64L416,383.62V480a32,32,0,0,0,32,32h64a32,32,0,0,0,32-32V352a32,32,0,0,0,32-32V224A64,64,0,0,0,512,160ZM444.4,295.34l-72.12-68.06A12,12,0,0,0,352,236v36H224V236a12,12,0,0,0-20.28-8.73L131.6,295.34a12.4,12.4,0,0,0,0,17.47l72.12,68.07A12,12,0,0,0,224,372.14V336H352v36.14a12,12,0,0,0,20.28,8.74l72.12-68.07A12.4,12.4,0,0,0,444.4,295.34Z">
                                </path>
                            </svg></div>
                        <h3>Quality Leads</h3>
                        <p>Receive requests from clients specifically interested in your services, increasing your
                            chances of closing deals.</p>

                    </div>
                    <div class="grid-item feature">

                        <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-hands-helping"
                                viewBox="0 0 640 512" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M488 192H336v56c0 39.7-32.3 72-72 72s-72-32.3-72-72V126.4l-64.9 39C107.8 176.9 96 197.8 96 220.2v47.3l-80 46.2C.7 322.5-4.6 342.1 4.3 357.4l80 138.6c8.8 15.3 28.4 20.5 43.7 11.7L231.4 448H368c35.3 0 64-28.7 64-64h16c17.7 0 32-14.3 32-32v-64h8c13.3 0 24-10.7 24-24v-48c0-13.3-10.7-24-24-24zm147.7-37.4L555.7 16C546.9.7 527.3-4.5 512 4.3L408.6 64H306.4c-12 0-23.7 3.4-33.9 9.7L239 94.6c-9.4 5.8-15 16.1-15 27.1V248c0 22.1 17.9 40 40 40s40-17.9 40-40v-88h184c30.9 0 56 25.1 56 56v28.5l80-46.2c15.3-8.9 20.5-28.4 11.7-43.7z">
                                </path>
                            </svg></div>
                        <h3>Less Competition</h3>
                        <p>We only show three results to clients, ensuring you stand out and have a better
                            opportunity to win the job.</p>

                    </div>
                    <div class="grid-item feature">

                        <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-fas-mouse"
                                viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M0 352a160 160 0 0 0 160 160h64a160 160 0 0 0 160-160V224H0zM176 0h-16A160 160 0 0 0 0 160v32h176zm48 0h-16v192h176v-32A160 160 0 0 0 224 0z">
                                </path>
                            </svg></div>
                        <h3>Easy Management</h3>
                        <p>Simplify your workflow with our easy-to-use platform, giving you full control and
                            transparency over prices and projects.</p>

                    </div>
                    <div class="grid-item feature">

                        <div class="icon"><svg aria-hidden="true" class="e-font-icon-svg e-far-money-bill-alt"
                                viewBox="0 0 640 512" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M320 144c-53.02 0-96 50.14-96 112 0 61.85 42.98 112 96 112 53 0 96-50.13 96-112 0-61.86-42.98-112-96-112zm40 168c0 4.42-3.58 8-8 8h-64c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h16v-55.44l-.47.31a7.992 7.992 0 0 1-11.09-2.22l-8.88-13.31a7.992 7.992 0 0 1 2.22-11.09l15.33-10.22a23.99 23.99 0 0 1 13.31-4.03H328c4.42 0 8 3.58 8 8v88h16c4.42 0 8 3.58 8 8v16zM608 64H32C14.33 64 0 78.33 0 96v320c0 17.67 14.33 32 32 32h576c17.67 0 32-14.33 32-32V96c0-17.67-14.33-32-32-32zm-16 272c-35.35 0-64 28.65-64 64H112c0-35.35-28.65-64-64-64V176c35.35 0 64-28.65 64-64h416c0 35.35 28.65 64 64 64v160z">
                                </path>
                            </svg></div>
                        <h3>Transparent Pricing</h3>
                        <p>We offer complete price transparency, so you always know what you're paying for.</p>

                    </div>
                </div>
                <div class="" style="display: flex; gap: 8px">
                    <div>
                        <a class="q-btn q-btn-item non-selectable no-outline q-btn--outline q-btn--rectangle q-btn--rounded text-white q-btn--actionable q-focusable q-hoverable"
                            tabindex="0" href="https://app.thesvnetwork.com/auth/register/pro" target="_blank"><span
                                class="q-focus-helper"></span><span class="cta-button">Register to receive more leads
                                like
                                this.</span></a>
                    </div>
                    <div>
                        <a class="q-btn q-btn-item non-selectable no-outline q-btn--outline q-btn--rectangle q-btn--rounded text-white q-btn--actionable q-focusable q-hoverable"
                            tabindex="0" href="https://thesvnetwork.com/contractors?utm_source=email-no-match"
                            target="_blank"><span class="q-focus-helper"></span><span class="cta-button-2">Learn
                                more</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>



</body>

</html>
