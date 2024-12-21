<form action="{{ route('participants.store', $event->id) }}" method="POST">
    @csrf
    <label for="name">Nume:</label>
    <input type="text" name="name" required>
    
    <label for="email">Email:</label>
    <input type="email" name="email" required>
    
    <button type="submit">Înregistrează-te</button>
</form>
