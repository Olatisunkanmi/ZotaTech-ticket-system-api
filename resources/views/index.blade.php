<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" /> -->
    <!--The link allows us add roboto font to the html. Its a google font so I think your html2pdf should be able to read it easily. If it cant, just comment it out. line7-10-->
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin /> -->
    <!-- <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    /> -->
    <!--The link below allows us link our CSS document to the html document. I used it when building and commented it out here so you dont need it. Just ignore it.-->
    <!-- <link rel="stylesheet" href="./style.css" /> -->
    <title>Ticket</title>
    <style>
      /* * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Roboto", sans-serif;
      } */

      body {
        /* width: 100vw; */
        /* height: 100vh; */
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        padding: 0;
      }

      .ticket_container {
        width: 90%;
        height: 50%;
        justify-content:right;
        display: flex;
        flex-direction: column;
        /* row-gap: 12px; */
      }

      .header_container {
        display: flex;
        justify-content: space-evenly;
        align-items: center;
      }

      .left_header_container {
        display: flex;
        flex-direction: column;
      }

      .present_text {
        font-size: 14px;
        text-align: left;
      }

      .zojatech_logo {
        display: flex;
        justify-content: end;
        column-gap: 10px;
        align-items: center;
      }

      .zojatech_logo img {
        width: 40%;
        height: auto;
      }

      .zojatech_logo_text {
        font-size: 20px;
        color: rgba(97, 97, 97, 0.65);
        font-weight: 400;
      }

      .main_ticket_container {
        display: flex;
        justify-content: space-evenly;
        width: 100%;
        background-color: rgba(194, 194, 194, 0.233);
        padding: 20px;
        /* column-gap: 10%; */
        flex-direction: column;
        border: 2px solid rgba(0, 0, 0, 0.875);
        border-top: 12px solid rgba(97, 97, 97, 0.233);
      }

      .main_ticket_container .qr-code {
        width: 25%;
        height: auto;
      }

      .left_ticket_container {
        width: 75%;
        height: auto;
        display: flex;
        flex-direction: column;
        row-gap: 18px;
      }

      .event_title_container {
        display: flex;
        flex-direction: column;
      }

      .company_title {
        font-size: 14px;
        color: #4472c4;
      }

      .event_title {
        font-size: 20px;
        color: #4472c4;
      }

      .location_container {
        display: flex;
        flex-direction: column;
        row-gap: 4px;
      }

      .location_text {
        font-size: 14px;
      }

      .time_text {
        font-size: 16px;
        color: #4472c4;
      }

      .main_ticket_footer {
        display: flex;
        justify-content: space-between;
      }

      .issued_to_container,
      .ticket_id_container {
        display: flex;
        flex-direction: column;
        row-gap: 4px;
      }

      .issued_to_text,
      .ticket_id_text {
        font-size: 14px;
      }

      .owner_text,
      .id_text {
        font-size: 20px;
        color: #4472c4;
        font-weight: y00;
        text-transform: uppercase;
      }

      .footer_text {
        text-align: center;
        font-size: 14px;
      }
    </style>
  </head>
  <body>
    <div class="ticket_container">
      <div class="header_container">
        <div class="left_header_container">
          <h2 class="header_text">This is your Ticket</h2>
          <p class="present_text">Present it at the event gate</p>
        </div>
        <div class="zojatech_logo">
          <!-- <img src="./ith_logo.png" alt="ith logo" /> -->
          <h2 class="zojatech_logo_text">Zojatix</h2>
        </div>
      </div>

      <div class="main_ticket_container">
        <div class="left_ticket_container">
          <div class="event_title_container">
            <h1 class="company_title">Organizer : {{$eventOwner}}</h1>
            <h2 class="event_title">Event: {{$event}}</h2>
          </div>

          <div class="location_container">
            <p class="location_text">
             Location:  {{$location}}
            </p>
           <h3 class="time_text"> Date and Time: {{$date}}, {{$time}} AM (WAT)</h3>
          </div>

          <div class="main_ticket_footer">
            <div class="issued_to_container">
              <p class="issued_to_text">Issued to:-</p>
              <h3 class="owner_text">{{$name}}</h3>
              <p class="issued_to_text">Admits:-</p>
              <h3 class="owner_text">{{$quantity}}</h3>
            </div>

            <div class="ticket_id_container">
              <p class="ticket_id_text">Ticket Id:-</p>
              <h3 class="id_text"><a href="{{$link}}">{{$id}}</a></h3>
            </div>
          </div>
        </div>

        <div class="qr-code">{!! DNS2D::getBarcodeHTML("{{$link}}", 'QRCODE') !!}</div>
        <!-- <img src="./qr_code.png" alt="qr code" /> -->
      </div>

      <p class="footer_text">
       © 2023 Zojatech ITH Town Hall - All Rights Reserved.
      </p>
    </div>
  </body>
</html>
