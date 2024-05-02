<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cancel Booking</title>
</head>
<body style="background-color: #e9ecef;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
       <tbody>

          <tr>
             <td align="center" bgcolor="#e9ecef">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:800px;">
                   <tbody>
                      <tr>
                         <td align="center" valign="top" style="padding: 16px 24px; background:#fff;"><a href="https://parkmydrive.inqdemo.com/" target="_blank" style="display: inline-block;"> <img src="https://parkmydrive.inqdemo.com/assets/logo-SKifnuQt.png" alt="Logo" border="0" width="299" style="display: block; width:299px; max-width:299px; min-width:299px;"> </a></td>
                      </tr>
                   </tbody>
                </table>
             </td>
          </tr>
          <tr>
             <td align="center" bgcolor="#e9ecef">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:800px;">
                   <tbody>
                      <tr>
                         <td align="left" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
                            @foreach ($bookingData as $booking)

                            <h1 style="margin: 0; font-size:22px; font-weight: 700; letter-spacing: -1px; line-height: 48px;">Dear, {{ $booking['user']['name'] }},</h1>
                        @endforeach
                        </td>
                      </tr>
                      <tr>
                         <td align="left" bgcolor="#ffffff" style="padding:0 24px 24px 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                            <p style="margin: 0 0 15px;">Your car parking slot reservation has been cancelled. We apologize for any inconvenience this may cause.
                            </p>
                            <h1 style="margin: 0; font-size:18px; font-weight: 700; letter-spacing: -1px; line-height: 48px;">Booking Details</h1>
                            <table width="100%" border="1" cellspacing="10" cellpadding="10">
                               <tbody>
                                @if(isset($bookingData) && count($bookingData) > 0)
                                @foreach ($bookingData as $booking)
                                  <tr>
                                     <td width="200px">Name :</td>
                                     <td>{{ $booking['user']['name'] }}</td>

                                  </tr>
                                  <tr>
                                     <td>Address :</td>
                                     <td>{{ $booking['parking_spots']['google_map']  }}</td>

                                  </tr>

                                  <tr>
                                     <td>Vehicle No. :</td>
                                     <td>{{ $booking['vehicle_number'] }}</td>

                                  </tr>
                                  <tr>
                                    <td>From Date and Time :</td>
                                    <td>{{ $booking['from_datetime'] }}</td>
                                 </tr>
                                 <tr>
                                    <td>To Date and Time :</td>
                                    <td>{{ $booking['to_datetime'] }}</td>

                                 </tr>
                                 <tr>
                                    <td>Per Hour :</td>
                                    <td>{{$booking['parking_spots']['vehicle_fees']}}</td>

                                 </tr>
                                 <tr>
                                    <td>Total hours:</td>
                                    <td>{{$booking['total_hours']}}</td>
                                 </tr>
                                 <tr>
                                    <td>Total Cost :</td>
                                    <td>{{$booking['amount_paid']}}</td>

                                 </tr>
                                 <tr>
                                    <td>Booked on :</td>
                                    <td>{{$booking['booked_on']}}</td>

                                 </tr>
                                 <tr>
                                    <td>Status :</td>
                                    <td>{{$booking['status']}}</td>

                                 </tr>
                                  <tr>
                                     <td>Cancelled By :</td>
                                     <td>{{ $booking['cancelled_booking']['cancelled_by'] }}</td>

                                  </tr>
                                  <tr>
                                     <td>Reason for Cancellation :</td>
                                     <td>{{ $booking['cancelled_booking']['reason_for_cancellation'] }}</td>

                                  </tr>
                                  <tr>
                                     <td>Cancelled Date :</td>
                                     <td>{{ $booking['cancelled_booking']['cancelled_date'] }}</td>

                                  </tr>
                                  <tr>
                                     <td>Refund Status :</td>
                                     <td>{{ $booking['cancelled_booking']['refund_status'] }}</td>

                                  </tr>
                                  @endforeach
                                  @else
                                  <tr>
                                      <td colspan="2">No Cancel data available</td>
                                  </tr>
                                  @endif
                               </tbody>
                            </table>
                         </td>
                      </tr>
                   </tbody>
                </table>
             </td>
          </tr>
          <tr>
             <td align="center" bgcolor="#e9ecef" style="padding: 24px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                   <tbody>
                      <tr>
                         <td align="center" bgcolor="#e9ecef" style="padding: 12px 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666;">
                            <p style="margin: 0;"><strong>Park in my drive</strong></p>
                            <p style="margin: 0;">10707 Hastings Lane Austin, Texas 78750</p>
                         </td>
                      </tr>
                   </tbody>
                </table>
             </td>
          </tr>
       </tbody>
    </table>
 </body>
</html>
