<!DOCTYPE html>
<html>
<head>
    <style>
        .status-badge { padding: 5px 10px; border-radius: 4px; font-weight: bold; color: #fff; }
        .pending { background-color: #ffc107; color: #000; }
        .interview { background-color: #17a2b8; }
        .hired { background-color: #28a745; }
        .rejected { background-color: #dc3545; }
    </style>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Hello, {{ $application->user->first_name }}!</h2>
    <p>There has been an update to your application for the position of <strong>{{ $application->job->title }}</strong> at <strong>{{ $application->job->company }}</strong>.</p>
    
    <p>Your current application status is now:</p>
    <p><span class="status-badge {{ $application->status }}">{{ strtoupper($application->status) }}</span></p>

    <p>Log in to the Alumni Portal to see more details.</p>
    
    <hr>
    <p>Best Regards,<br>KSU Alumni Office</p>
</body>
</html>