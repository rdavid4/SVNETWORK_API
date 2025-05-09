<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>New Quote Request</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">

  <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; overflow: hidden;">
    <tr>
      <td style="background-color: #fa5f1e; color: white; padding: 20px; text-align: center;">
        <h2>New Quote Request Received</h2>
      </td>
    </tr>

    <tr>
      <td style="padding: 20px;">
        <p>Hello,</p>

        <p>You’ve received a new quote request from a potential customer. Here are the details:</p>

        <h4>Customer Information</h4>
        <ul>
          <li><strong>Name:</strong> {{ $user->name }} {{ $user->surname }}</li>
          <li><strong>Email:</strong> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></li>
          <li><strong>Phone:</strong> <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a></li>
        </ul>

        <h4>Project Details</h4>
        <ul>
          <li><strong>Requested Service:</strong> {{ $quote->service->name }}</li>
          <li><strong>Location:</strong> {{ $quote->location?->zipcode }} {{ $quote->location?->location }}, {{ $quote->location?->state }}</li>
          <li><strong>Description:</strong> {{ $quote->description }}</li>
        </ul>

        @if (!empty($quote->images) && count($quote->images))
        <h4>Images</h4>
        <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
          @foreach ($quote->images as $image)
            <img src="{{ $image->urlQuote }}" alt="Project Image" style="width: 100%; height: auto; border-radius: 4px; border: 1px solid #ccc;" />
          @endforeach
        </div>
        @endif

        <p style="margin-top: 30px; text-align: center;">
          <a href="{{ $quote->link }}" style="background-color: #fa5f1e; color: white; text-decoration: none; padding: 12px 20px; border-radius: 5px; display: inline-block;">
            View Quote Details
          </a>
        </p>

        <p>Be sure to respond promptly to provide the best service possible.</p>

        <p>Best regards,<br/>The {{ config('app.name') }} Team</p>
      </td>
    </tr>

    <tr>
      <td style="background-color: #f1f1f1; text-align: center; font-size: 12px; color: #777; padding: 10px;">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
      </td>
    </tr>
  </table>

</body>
</html>
