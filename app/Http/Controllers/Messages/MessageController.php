<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\MessageRequest;
use App\Http\Resources\Messages\MessageResource;
use App\Models\Files\File;
use App\Models\Messages\Message;
use App\Models\Messages\Thread;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

class MessageController extends Controller
{
    public function index(Thread $thread)
    {
        /** @var Collection $messages */
        $messages = $thread->messages()
            ->latest()
            ->collect();

        // update last read time for current user on this thread
        $thread->markAsRead(request()->user());

        return MessageResource::collection($messages);
    }

    public function show(Thread $thread, $id)
    {
        $message = $thread->messages()->findOrFail($id);

        return new MessageResource($message->fresh());
    }

    public function store(Thread $thread, MessageRequest $request)
    {
        /** @var Message $message */
        $message = $thread->messages()->create($request->all());

        // Grant access to file shared to thread participants
        if (! is_null($request->input('attachment_file_id'))) {
            /** @var File $file */
            $file = File::query()->find($request->input('attachment_file_id'));
            $file->addReaders($thread->participants);
        }

        return new MessageResource($message);
    }

    public function update(Thread $thread, MessageRequest $request, $id)
    {
        $message = $thread->messages()->findOrFail($id);
        $message->update($request->only('body'));
        $message->save();

        return new MessageResource($message->fresh());
    }

    public function destroy(Thread $thread, $id)
    {
        $message = $thread->messages()->findOrFail($id);
        $message->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }
}
