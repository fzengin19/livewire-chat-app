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
    public $offset;
    public $messages;
    public $height;

    public function mount()
    {
        $this->offset = 1;
        $this->loadChats();
    }


    public function render()
    {
        $data['chats'] = $this->chats;
        if (isset($this->activeChat)) {
            $data['activeChat'] = $this->activeChat;
            $data['messages'] = $this->messages;
        }
        return view('livewire.chat-app-component', $data);
    }
    public function selectChat($id)
    {
        $this->offset = 1;
        foreach ($this->chats as $key => $chat) {
            if ($chat->id === $id) {
                $this->activeChat = $chat;
            }
        }
        $this->loadChats();
        $this->message = null;
        $this->messages = Message::where('chat_id', $this->activeChat->id)->orderBy('id', 'desc')->take(20 * $this->offset)->get();
        $this->messages = $this->messages->reverse();
        // dd($this->messages);
        $this->dispatch('selectChat');
    }

    public function loadMoreMessages()
    {
        $this->offset += 1;
        $message = $this->messages->first();
        if (isset($this->activeChat)) {
            $this->messages = Message::where('chat_id', $this->activeChat->id)->orderBy('id', 'desc')->take(20 * $this->offset)->get();
            // $this->messages = $this->messages->sortBy('id');
            // $this->messages = $this->messages->reverse();
            $this->dispatch('messagesAdded');
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
            'loadMoreMessages',
            'getLastMessage'
        ];
    }
    public function receiveMessage($event)
    {
        if (isset($this->activeChat)) {

            $this->dispatch('messageReceived', $event);
            $message = Message::find($event['message']['id']);
            $this->messages[] = $message;
            // $this->messages = $this->messages->reverse();
            // $this->messages = $this->messages->sortBy('id');
        }
    }
    public function closeChat()
    {
        unset($this->activeChat);
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
            $this->messages[] = $message;
            // $this->messages = $this->messages->sortBy('id');
            // $this->messages = $this->messages->reverse();
            $this->dispatch('messageReceived', $message);
            broadcast(new SendMessageEvent($message));
            $this->message = null;
        }
    }
}
