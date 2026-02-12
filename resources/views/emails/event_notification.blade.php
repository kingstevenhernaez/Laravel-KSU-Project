<h1>New Alumni Event Posted!</h1>
<p>Hi Alumni, a new event has been scheduled:</p>
<ul>
    <li><strong>Title:</strong> {{ $event->title }}</li>
    <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y h:i A') }}</li>
    <li><strong>Location:</strong> {{ $event->location }}</li>
</ul>
<p>{{ $event->description }}</p>
<a href="{{ url('/events') }}">View Details on Portal</a>