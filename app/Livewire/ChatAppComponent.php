<?php

namespace App\Livewire;

use App\Events\SendMessageEvent;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatAppComponent extends Component
{
    public ?Chat $activeChat;
    public $chats;
    public $message;

    public function mount()
    {
        $this->loadChats();
    }


    public function render()
    {

        $data['chats'] = $this->chats;

        if (isset($this->activeChat)) {
            $data['activeChat'] = $this->activeChat;
        }

        return view('livewire.chat-app-component', $data);
    }
    public function selectChat($id)
    {
        foreach ($this->chats as $key => $chat) {
            if ($chat->id === $id) {
                $this->activeChat = $chat;
            }
        }
        $this->loadChats();
        if (!isset($this->activeChat->messages)) {
            $this->activeChat->load('messages');
        }
    }
    public function loadChats()
    {
        $this->chats = auth()->user()->chats()->orderBy('last_message_id', 'desc')->get();
    }

    public function  getListeners()
    {

        $auth_id = auth()->user()->id;
        return [
            "echo-private:chat.{$auth_id},SendMessageEvent" => 'receiveMessage',
        ];
    }
    public function receiveMessage($event)
    {
        if (isset($this->activeChat))
            $this->activeChat->refresh();
        $this->dispatch('messageReceived');
        $this->loadChats();
    }

    public function sendMessage()
    {
        if (!empty($this->message)) {
            $message = Message::create([
                'user_id' => Auth::id(),
                'chat_id' => $this->activeChat->id,
                'message' => $this->message,
            ]);
            $this->activeChat->update([
                'last_message_id' => $message->id
            ]);
            $this->dispatch('messageReceived');
            // dd(($users));
            broadcast(new SendMessageEvent($message));
            $this->message = null;
            // $this->loadChats();
        }
    }
}
