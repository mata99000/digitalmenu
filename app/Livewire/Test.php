<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\TestEvent;
use Livewire\Attributes\On;
class Test extends Component
{
    public $message = '';

    public function emitEvent()
    {
        $this->message = 'Event fired!';
        TestEvent::dispatch($this->message);
    }

    #[On('echo:test-channel,TestEvent')]
    public function listenforevent($data){
        return $this->message;
    }
    public function render()
    {
        return view('livewire.test')->layout('layouts.guest');
    }
    protected $listeners = ['echo:test-channel,.TestEvent' => 'handleEvent'];

    public function handleEvent($payload)
    {
        $this->message = $payload['message'];
        $this->dispatch('alert', ['message' => $this->message]);
    }
}
