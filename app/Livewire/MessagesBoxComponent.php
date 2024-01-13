<?php

namespace App\Livewire;

use App\Events\SendMessageEvent;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class MessagesBoxComponent extends Component
{
    public $activeChat;
    public $messages;
    public $message;
    public $offset;

    public function mount($activeChat, $messages)
    {
        $this->activeChat = $activeChat;
        $this->messages = $messages;
    }
    public function render()
    {
        return view('livewire.messages-box-component');
    }
    public function loadMoreMessages()
    {
        $this->offset += 1;
        $message = $this->messages->first();
        if (isset($this->activeChat)) {
            $this->messages = Message::where('chat_id', $this->activeChat->id)->orderBy('id', 'desc')->take(20 * $this->offset)->get();
            $this->messages = $this->messages->sortBy('id');

            $this->dispatch('messagesAdded');
        }
    }

    public function receiveMessage($event)
    {
        if (isset($this->activeChat)) {

            $this->dispatch('messageReceived', $event);
            $message = Message::find($event['message']['id']);
            $this->messages[] = $message;
            $this->messages = $this->messages->sortBy('id');
            $this->dispatch('refreshComponent');
        }
    }
    public function sendMessage()
    {
        if (!empty($this->message)) {
            $message = Message::create([
                'user_id' => Auth::id(),
                'chat_id' => $this->activeChat->id,
                'message' => $this->message,
            ]);
            $this->messages[] = $message;
            $this->activeChat->update([
                'last_message_id' => $message->id
            ]);
            $this->messages = $this->messages->sortBy('id');
            $this->dispatch('messageReceived', $message);
            broadcast(new SendMessageEvent($message));
            $this->message = null;
        }
    }
}
