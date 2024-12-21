<form action="{{ route('events.store') }}" method="POST">
    @csrf
    <label for="name">Nume Eveniment:</label>
    <input type="text" name="name" required>
    
    <label for="description">Descriere:</label>
    <textarea name="description" required></textarea>
    
    <label for="event_date">Data și Ora Evenimentului:</label>
    <input type="datetime-local" name="event_date" required>
    
    <button type="submit">Crează Eveniment</button>
</form>
