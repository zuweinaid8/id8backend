<?php

namespace App\Http\Controllers\Calendars;

use App\Http\Requests\Calendars\CalendarRequest;
use App\Http\Resources\Calendars\CalendarResource;
use App\Models\Calendars\Calendar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        $calendars = Calendar::query()
            ->with([
                'settings'
            ])
            ->paginate();

        return CalendarResource::collection($calendars);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CalendarRequest $request
     * @return CalendarResource
     */
    public function store(CalendarRequest $request)
    {
        $calendar = DB::transaction(function () use ($request) {
            $calendar = Calendar::create($request->all());

            $calendar->settings()->create(
                $request->input('settings', $this->getDefaultSettings())
            );

            return $calendar->fresh('settings');
        });

        return new CalendarResource($calendar);
    }


    /**
     * Display the specified resource.
     *
     * @param Calendar $calendar
     * @return CalendarResource
     */
    public function show(Calendar $calendar)
    {
        $calendar->load('settings');

        return new CalendarResource($calendar);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CalendarRequest $request
     * @param Calendar $calendar
     * @return CalendarResource
     */
    public function update(CalendarRequest $request, Calendar $calendar)
    {
        $calendar->update($request->all());
        $calendar->save();

        if ($request->has('settings') && !is_null($request->input('settings'))) {
            $calendar->settings()->update(
                $request->input('settings')
            );
        }

        $calendar->load('settings');
        $calendar->fresh();

        return new CalendarResource($calendar);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Calendar $calendar
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Calendar $calendar)
    {
        $this->authorize('delete', $calendar);

        $calendar->delete();

        return $this->respondDeleted();
    }

    protected function getDefaultSettings()
    {
        return  [
            'is_public' => false,
            'notifications_enabled' => false,
            'timezone' => config('app.timezone'),
            'location' => null,
        ];
    }
}
