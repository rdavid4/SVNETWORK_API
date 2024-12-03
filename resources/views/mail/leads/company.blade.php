<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Results</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; border-radius: 15px;">

        <!-- Logo Section -->
        <div style="text-align: center; padding-bottom: 20px;">
            <a href="https://app.thesvnetwork.com">
                <img src="{{ config('app.api_url') . '/storage/images/logo.png' }}" alt="SVNetwork Logo" style="max-width: 200px; height: auto;">
            </a>
        </div>

        <!-- Header Section -->
        <div style="text-align: left; padding-bottom: 20px;">
            <h2 style="color: #333333;">Hi {{ $data->company_name }}.</h2>
            <p style="color: #555555;">
                We’re reaching out with a unique opportunity! A customer in your area is looking for
                <strong>{{ $data['service']->name }}</strong> services, and we believe your company might be the perfect fit.
            </p>
            <p style="color: #555555;">
                At this moment, we don’t have a registered company offering this service in this area in our database. However, we selected your business because we believe it could be a great fit for this client’s needs.
            </p>
            <p style="background-color: #8bfffd; padding: 10px; border-radius: 8px; font-weight: bold; color: #333333;">
                Take advantage of this opportunity to connect with a new client!
            </p>
        </div>

        <!-- Lead Information -->
        <div style="border: 1px solid #ccc; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <h3 style="color: #333333;">Project - {{ $data->title }}</h3>
            <p style="color: #555555;">{{ $data->description }}</p>
            <ul style="padding: 0; margin: 0; list-style: none; color: #555555;">
                @if (!empty($data->answers))
                    @foreach ($data->answers as $answer)
                        <li style="padding: 10px; background-color: #f9f9f9; margin-bottom: 10px; border-radius: 8px;">
                            <strong>{{ $answer->question->text }}</strong>: {{ $answer->text }}
                        </li>
                    @endforeach
                @endif
                @if (!empty($data->openAnswers))
                    @foreach ($data->openAnswers as $answer)
                        <li style="padding: 10px; background-color: #f9f9f9; margin-bottom: 10px; border-radius: 8px;">
                            <strong>{{ $answer->question_text }}</strong>: {{ $answer->text }}
                        </li>
                    @endforeach
                @endif
            </ul>
            @if (!empty($data->images))
                @foreach ($data->images as $image)
                    <a href="{{ $image->url }}" target="_blank" style="display: block; margin-bottom: 10px;">
                        <img src="{{ $image->url }}" alt="Project Image" style="max-width: 100%; height: auto; border-radius: 8px;">
                    </a>
                @endforeach
            @endif
        </div>

        <!-- Contact Information -->
        <div style="padding: 15px; border: 3px dotted #e45700; border-radius: 8px; margin-bottom: 20px;">
            <h3 style="color: #333333;">{{ $data['user']['name'] }} {{ $data['user']['surname'] }}</h3>
            <p style="color: #555555;">Phone: <a href="tel:+{{ $data['user']['phone'] }}" style="color: #fa5f1e;">{{ $data['user']['phone'] }}</a></p>
            <p style="color: #555555;">Email: <a href="mailto:{{ $data['user']['email'] }}" style="color: #fa5f1e;">{{ $data['user']['email'] }}</a></p>
        </div>

        <!-- CTA Section -->
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="https://app.thesvnetwork.com/auth/register/pro" target="_blank" style="display: inline-block; background-color: #fa5f1e; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 8px;">
                Register to receive more leads like this
            </a>
        </div>

        <!-- Footer -->
        <div style="font-size: 12px; color: #777777; text-align: center; padding-top: 15px; border-top: 1px solid #ccc;">
            <p>
                This email was sent by SVNetwork. For more information, visit our <a href="{{ config('app.app_url') . '/legal/terms' }}" style="color: #fa5f1e;">Terms & Conditions</a>.
            </p>
