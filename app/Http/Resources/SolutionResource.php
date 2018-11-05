<?php

namespace App\Http\Resources;

use App\Http\Resources\BaseResource;

class SolutionResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $solutions = parent::toArray($request);
        $mentors = $solutions['mentors'];
        foreach ($mentors as $mentor) {
            $mentor['mentor_areas']='test';
        }
        dd($mentors);
        $array['mentors']=$mentors;
        return $array;
    }
}
