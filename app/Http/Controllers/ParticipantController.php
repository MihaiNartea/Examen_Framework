<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Event;

class ParticipantController extends Controller
{
    public function create($eventId)
    {
        $event = Event::findOrFail($eventId);
        return view('participants.create', compact('event'));
    }

    public function store(Request $request, $eventId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:participants,email',
        ]);

        Participant::create([
            'name' => $request->name,
            'email' => $request->email,
            'event_id' => $eventId,
        ]);

        return redirect()->route('events.index');
    }
}
