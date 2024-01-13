<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SendMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ?Message $message;
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $users = User::whereIn('id', $this->message->chat->users->where('id', '!=', $this->message->user_id)->pluck('id'))->get();

        $result = [];
        foreach ($users as $key => $user) {
            $result[] = new PrivateChannel('chat.' . $user->id);
        }
        return $result;
    }

    public function broadcastWith()
    {
        $data = [
            'message' => $this->message,
        ];

        Log::info('Broadcast Data', $data);

        return  $data;
    }
}
