<h3>Hello, {{ $name }}</h3>
<h3>You have left the event {{ $event }}</h3>
<h3>We're sad to see you go! If you have second thoughts about the decision you made, you can always check out the event's page <a href="{{ route('event.show', ['id' => $eventId]) }}">here</a></h3>
<h4>Best regards,</h4>
<h4>Invents Staff</h4>