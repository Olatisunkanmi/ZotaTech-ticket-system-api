<!DOCTYPE html>
<html>

<head>
  <title>Ticket</title>
  <style>
    /* @page {
      size: A6;
      orientation: portrait;
      margin: 0;
    } */


    body {
      font-family: Arial, sans-serif;
      background-color: #ffff;
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 400px;
    }

    .ticket {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 10px;
      width: 100%;
      height: 70%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .ticket h2 {
      color: #333;
      font-size: 18px;
      margin-top: 0;
      text-align: center;
    }

    .ticket .event {
      margin-bottom: 5px;
      text-align: center;
    }

    .ticket .event p {
      color: #333;
      font-size: 15px;
      margin: 5px 0;
    }

    .ticket .details {
      margin-bottom: 5px;
      padding: 10px;
      border: 1px solid #ccc;
      background-color: #f9f9f9;
      justify-content: space-between;
    }

    .ticket .details p {
      top: 76px;
      color: #333;
      font-size: 12px;
      margin: 5px 0;
    }

    .ticket .barcode {
      text-align: center;
    }

    .qr-code {
      text-align: center;
      padding-left: 25%;
      height: 10px;
      margin: 0px;
    }

    p.location {
      display: flex;
      max-width: 190px;
      position: absolute;
      top: 76px;
      right: 10px;
    }

    .event-owner {
      margin: -5px;
    }

    .footer_text {
      margin: 0px;
    }
  </style>
</head>

<body>
  <div class="ticket">
    <h2 class="event-owner">ZOJATIX</h2>
    <div class="event">
      <p>Event Name: {{$event}}</p>
      <p>Date & Time : {{$date}} | {{$time}} (WAT)</p>
    </div>
    <div class="details">
      <p>Issued to: {{$name}}</p>
      <p>Admits: {{$quantity}}</p>
      <p class="location">Location: {{$location}}</p>

    </div>
    <div class="barcode">
      <p>ID: <a style="text-decoration: none; font: san-serif; color: black; text-transform:uppercase" href="{{$link}}">{{$id}}</a> </p>
      <div class="qr-code"> {!! DNS2D::getBarcodeHTML( "{{$link}}" , 'QRCODE') !!}</div>

      <!-- <footer class="footer_text"> -->
      <!-- <h6>© 2023 ZojaTix - All Rights Reserved.</h6> -->
      <!-- </footer> -->
    </div>
  </div>
</body>

</html>