 # Arhitectura aplicatiei (Models, Views, Controllers)
 1. 
-   **Models**:
    
    -   `Event`: Reprezinta evenimentele disponibile pentru inregistrare. Contine campuri precum `name`, `description`, `event_date`.
    -   `Participant`: Reprezinta participantii care se inregistreaza la evenimente. Conține campuri precum `name`, `email`, `event_id` (cheie externa către tabela `events`).
-   **Views**:
    
    -   `events.index`: Afisează lista evenimentelor disponibile.
    -   `participants.create`: Formular pentru adăugarea unui participant la un eveniment.
-   **Controllers**:
    
    -   `EventController`: Contine metode pentru a gestiona evenimentele (index ,creare).
    -   `ParticipantController`: Contine metode pentru a gestiona participanții (creare).

#### Schema bazei de date

-   **events**:

    -   `id`: integer (Primary Key)
    -   `name`: string
    -   `description`: text
    -   `event_date`: datetime
-   **participants**:
    
    -   `id`: integer (Primary Key)
    -   `name`: string
    -   `email`: string
    -   `event_id`: integer (Foreign Key către `events`)

#### Tipuri de stocare

-   **Baza de date**: Pentru stocarea informatiilor despre evenimente și participanti.

#### Date pentru cache

-   **Evenimente populare**: Informatiile despre evenimentele care sunt frecvent accesate pot fi stocate în cache pentru a reduce incarcarea bazei de date.
-   **Datele participantilor**: Lista participantilor pentru fiecare eveniment poate fi stocata temporar în cache pentru a accelera accesul la informatiile despre participanti.

#### Stack-ul tehnologic utilizat

-   **Backend**: Laravel 10
-   **Frontend**: Blade
-   **Baza de date**: MySQL
-   **Dependențe suplimentare**: Faker

----------

### 2. Pașii urmați pentru crearea proiectului

#### Instrumente și comenzi utilizate

-   **Instalarea Laravel**:
    
    `composer create-project laravel/laravel:^10 event-registration` 
    
-   **Crearea modelelor + migratii**:
    
    `php artisan make:model Event -m`
    `php artisan make:model Participant -m` 
    
-   **Crearea controller-elor**:
    
    `php artisan make:controller EventController`
    `php artisan make:controller ParticipantController` 
    
-   **Generarea de date false**:
    
    `php artisan make:seeder EventSeeder`
	 `php artisan make:seeder ParticipantSeeder` 
    
-   **Migrarea bazei de date**:

    `php artisan migrate` 
    
-   **Popularea bazei de date cu date false**:
    
    `php artisan db:seed` 
    
----------

### 3. Metodele din Controler

#### EventController

	$request->validate([
	'name'  =>  'required|string|max:255',
	'description'  =>  'required|string',
	'event_date'  =>  'required|date',
	]);

	Event::create($request->all());
	return redirect()->route('events.index');
	}

#### ParticipantController 

	$request->validate([
	'name'  =>  'required|string|max:255',
	'email'  =>  'required|email|unique:participants,email',
	]);

	Participant::create([
	'name'  =>  $request->name,
	'email'  =>  $request->email,
	'event_id'  =>  $eventId,
	]);
	} 

----------

### 4. Validarea datelor

Codul de validare a datelor se afla deja în metodele de salvare din controlerele `EventController` si `ParticipantController`:

	$request->validate([
		'name'  =>  'required|string|max:255',
		'description'  =>  'required|string',
		'event_date'  =>  'required|date',
	]);

----------

### 5. Vizualizarile (Views)

#### Formular pentru adăugarea unui eveniment

<!-- resources/views/events/create.blade.php -->

	<form action="{{ route('events.store') }}" method="POST">
    @csrf
    <label for="name">Nume Eveniment</label>
    <input type="text" name="name" id="name" required>

    <label for="description">Descriere Eveniment</label>
    <textarea name="description" id="description" required></textarea>

    <label for="event_date">Data Evenimentului</label>
    <input type="date" name="event_date" id="event_date" required>
    <button type="submit">Adaugă Eveniment</button>
	</form>

#### Formular pentru adăugarea unui participant

<!-- resources/views/participants/create.blade.php -->

	<form action="{{ route('participants.store') }}" method="POST">
    @csrf
    <label for="name">Nume Participant</label>
    <input type="text" name="name" id="name" required>

    <label for="email">Email Participant</label>
    <input type="email" name="email" id="email" required>

    <label for="event_id">Eveniment</label>
    <select name="event_id" id="event_id" required>
        @foreach ($events as $event)
            <option value="{{ $event->id }}">{{ $event->name }}</option>
        @endforeach
    </select>

    <button type="submit">Înregistrează Participant</button>
	</form>

----------

### 6. Modelele

#### Modelul Event

`// app/Models/Event.php`

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Event extends Model {
    use HasFactory;

    protected $fillable = ['name', 'description', 'event_date'];

    public function participants() {
        return $this->hasMany(Participant::class);
    }
	}

#### Modelul Participant

`// app/Models/Participant.php`

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Participant extends Model {
	    use HasFactory;

    protected $fillable = ['name', 'email', 'event_id'];

    public function event() {
        return $this->belongsTo(Event::class);
    }
}` \

----------

### 7. Migrațiile

#### Migrarea pentru evenimente

`// database/migrations/2024_12_21_063434_create_events_table.php`
	
	public function  up(): void
	{
	Schema::create('events',  function  (Blueprint  $table)  {
	$table->id();
	$table->string('name');
	$table->text('description');
	$table->dateTime('event_date');
	$table->timestamps();
	});
	} 

#### Migrarea pentru participanți

`// database/migrations/2024_12_21_063645_create_participants_table.php`

	public function  up(): void
	{
	Schema::create('participants',  function  (Blueprint  $table)  {
	$table->id();
	$table->string('name');
	$table->string('email')->unique();
	$table->foreignId('event_id')->constrained('events')->onDelete('cascade');
	$table->timestamps();
	});
	} 

----------

### 8. Rutele

`// routes/web.php`

	use App\Http\Controllers\EventController;
	use App\Http\Controllers\ParticipantController;
	
	Route::resource('events',  EventController::class);
	Route::get('events/{eventId}/register',  [ParticipantController::class,  'create'])->name('participants.create');
	Route::post('events/{eventId}/register',  [ParticipantController::class,  'store'])->name('participants.store');

### 9. Screenshot

![poza1](/storage/code-snapshot1.png)
![poza2](/storage/code-snapshot2.png)