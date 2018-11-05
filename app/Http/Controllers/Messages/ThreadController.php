<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\ThreadRequest;
use App\Http\Resources\Messages\ThreadResource;
use App\Models\Messages\Thread;
use Illuminate\Database\Eloquent\Collection;

class ThreadController extends Controller
{
    public function index()
    {
        /** @var Collection $threads */
        $threads = Thread::query()
            ->latest('updated_at') // get most recently active threads
            ->collect();

        $user_id = request()->user()->id;
        $threads->each(function ($thread) use ($user_id) {
            $thread->unread_count = $thread->countUnread($user_id);
        }); // see if the threads have been read by current user

        return ThreadResource::collection($threads);
    }

    public function show($id)
    {
        $thread = Thread::findOrFail($id);
        $thread->unread_count = $thread->countUnread(request()->user()->id);

        return new ThreadResource($thread);
    }

    public function store(ThreadRequest $request)
    {
        $participants = $request->input('participants', []);
        $participants = collect($participants)->pluck('user_id'); // extract user_id's
        $participants->push($request->user()->id);

        $thread = Thread::create($request->all());
        $thread->participants()->sync($participants->toArray());

        $message_body = $request->input('message.body');

        // add optional new message in thread
        if (!is_null($message_body)) {
            $thread->messages()->create([
                'body' => $message_body
            ]);
        }

        return new ThreadResource($thread);
    }

    public function update(ThreadRequest $request, $id)
    {
        $thread = Thread::findOrFail($id);
        $thread->update($request->all());
        $thread->save();

        $participants = $request->input('participants', []);
        $participants = collect($participants)->only('user_id'); // extract user_id's
        $participants->push($request->user()->id);

        $thread->participants()->sync($participants->toArray());

        return new ThreadResource($thread);
    }

    public function destroy($id)
    {
        $thread = Thread::findOrFail($id);
        $thread->delete();

        return response([], 204);
    }
}
