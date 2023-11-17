<?php

namespace App\Livewire;

use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UsersComponent extends Component
{

    public $search = '';
    public function render()
    {
        $query = User::where('id', '!=', Auth::id());
        if (!empty($this->search)) {

            $query->where('name', 'like', '%' . $this->search . '%');
        }
        $users = $query->paginate(8);
        return view('livewire.users-component', compact('users'));
    }

    public function createNewChat($userId)
    {
        if (Auth::user()->chats()->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->exists()) {
            $this->redirect('/chats');
        } else {

            $user = User::find($userId);
            $chat = Chat::create([
                'name' => $user->name,
                'type' => 'private'
            ]);
            ChatUser::create([
                'chat_id' => $chat->id,
                'user_id' => Auth::id()
            ]);
            ChatUser::create([
                'chat_id' => $chat->id,
                'user_id' => $user->id
            ]);
            $this->redirect('/chats');
        }
    }
}
