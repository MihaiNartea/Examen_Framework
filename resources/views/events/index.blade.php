<h1>Evenimente</h1>

@foreach ($events as $event)
    <div>
        <h3>{{ $event->name }}</h3>
        <p>{{ $event->description }}</p>
        <p>{{ $event->event_date }}</p>
        <a href="{{ route('participants.create', $event->id) }}">Înregistrează-te la acest eveniment</a>
    </div>
@endforeach
