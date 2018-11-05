<?php

namespace App\Http\Controllers\Calendars;

use App\Http\Controllers\Controller;
use App\Http\Requests\Calendars\EventRequest;
use App\Http\Resources\Calendars\EventResource;
use App\Models\Calendars\Calendar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Route;

class EventController extends Controller
{
    public function __construct()
    {
        // route binding for default calendar
        Route::bind('calendar', function ($id) {
            switch ($id) {
                case 0:
                    $calendar =  Calendar::query()
                        ->where('name', '=', 'ID8 Calendar (default)')
                        ->orderBy('id', 'asc')
                        ->firstOrFail();
                    break;
                default:
                    $calendar =  Calendar::query()->findOrFail($id);
            }
            return $calendar;
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @param Calendar $calendar
     * @return mixed
     */
    public function index(Calendar $calendar)
    {
        /** @var $events Collection*/
        $events = $calendar->events()
            ->latest('start_at')
            ->collect();

        // reverse sort order
        $reversed = $events->reverse();

        return EventResource::collection($reversed);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Calendar $calendar
     * @param EventRequest $request
     * @return EventResource
     */
    public function store(Calendar $calendar, EventRequest $request)
    {
        $appTimezone = config('app.timezone');

        // set config to calendar timezone
        $calendarTimezone = $calendar->settings->timezone;
        config()->set('app.timezone', $calendarTimezone);

        // create event
        $event = $calendar->events()->create($request->all());
        // reload model
        $event->fresh();

        // revert config to app timezone
        config()->set('app.timezone', $appTimezone);

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     *
     * @param Calendar $calendar
     * @param $id
     * @return EventResource
     */
    public function show(Calendar $calendar, $id)
    {
        $event = $calendar->events()->findOrFail($id);

        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Calendar $calendar
     * @param EventRequest $request
     * @param $id
     * @return EventResource
     */
    public function update(Calendar $calendar, EventRequest $request, $id)
    {
        $event = $calendar->events()->findOrFail($id);
        $event->update($request->all());
        $event->save();

        $event->fresh();
        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Calendar $calendar
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Calendar $calendar, $id)
    {
        $event = $calendar->events()->findOrFail($id);

        $this->authorize('delete', $event);

        $event->delete();

        return $this->respondDeleted();
    }
}
