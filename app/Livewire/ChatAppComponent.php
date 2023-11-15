<?php

namespace App\Livewire;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatAppComponent extends Component
{
    public ?Chat $activeChat;
    public $chats;
    public $message;

    public function __construct()
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
        $selectedChat = $this->chats->where('id', $id)->first();

        if ($selectedChat) {
            // Veritabanına gitmeden koleksiyon içinde işlem yap
            $this->chats = $this->chats->map(function ($chat) use ($selectedChat) {
                if ($chat->id === $selectedChat->id) {
                    // İlgili sohbeti güncelle
                    $this->activeChat = $chat;
                }
                return $chat;
            });
            if (!isset($this->activeChat->messages)) {
                $this->activeChat->load('messages');
            }
        }
    }
    public function loadChats()
    {
        $this->chats = auth()->user()->chats()->orderBy('last_message_id', 'desc')->get();
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
            $this->chats = $this->chats->map(function ($chat) use ($message) {
                if ($chat->id === $this->activeChat->id) {
                    $chat->last_message_id = $message->id;
                }
                return $chat;
            })->sortByDesc('last_message_id');
            // $this->loadChats();

            $this->message = null;
        }
    }
}
