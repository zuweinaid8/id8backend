<?php

namespace App\Events\Messages;

use App\Http\Resources\Files\FileResource;
use App\Http\Resources\Messages\MessageResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Messages\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageModelEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public $event;

    /**
     * Create a new event instance.
     *
     * @param Message $message
     * @param $event
     */
    public function __construct($message, $event)
    {
        logger('Broadcasting Message model event',
            ['id' => $message->id, 'event' => $event]
        );

        $this->message = $message;
        $this->event = $event;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('threads.' . $this->message->thread_id);
    }

    public function broadcastAs()
    {
        return 'message.' . $this->event;
    }

    public function broadcastWith()
    {
        $msg = $this->message;
        return [
            'event' => $this->event,
            'message' => [
                'id' => $msg->id,
                'sender' => new UserResource($msg->sender),
                'sender_id' => $msg->sender_id,
                'body' =>  $msg->body,
                'attachment_file_id' => $msg->attachment_file_id,
                'attachment' => isset($msg->attachment) ?  new FileResource($msg->attachment) : null,
                'created_at' => $msg->created_at,
                'updated_at' => $msg->updated_at,
                'is_edited' => $msg->is_edited,
                'i_am_sender' => false,
            ],
        ];
    }
}
