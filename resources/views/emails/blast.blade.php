<!DOCTYPE html>
<html>
<head>
    <title>{{ $details['subject'] }}</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
    <h2 style="color: #198754;">Kalinga State University Alumni Association</h2>
    <hr>
    <p>Dear Alumni,</p>
    
    <p>{!! nl2br(e($details['body'])) !!}</p>
    
    <br>
    <p>Best Regards,<br>KSU Admin Team</p>
    
    <div style="margin-top: 20px; font-size: 12px; color: #777;">
        This email was sent from the KSU Alumni Management System.
    </div>
</body>
</html>