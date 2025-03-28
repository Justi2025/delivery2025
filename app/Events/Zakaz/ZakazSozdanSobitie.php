<?php
 

namespace App\Events\Zakaz;

use App\Models\User;
use App\Khranilischa\Sdelki\ProstieObjiecti\DostavkaProstoObjiect;
use App\Servsi\Authentication\ZaloginenniPolzovatel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ZakazSozdanSobitie
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly DostavkaProstoObjiect $order,
        public readonly ZaloginenniPolzovatel $user,
        public readonly ?int                  $telegram_chat_id
    )
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
