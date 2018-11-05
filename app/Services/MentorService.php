<?php

namespace App\Services;

use App\Models\Mentor;
use App\User;

class MentorService
{
    private $mentor;
    public function __construct(Mentor $mentor)
    {
        $this->mentor = $mentor;
    }

    public function index()
    {
        return  $this->mentor
            ->withCount('solutions')
            ->with('mentor_areas')->mentor()->paginate();
    }

    public function update($id, $data)
    {
        $record = $this->mentor->mentor()->findOrFail($id);
        $record->update($data);
        return $record->fresh();
    }

    public function show($id)
    {
        return $this->mentor
            ->mentor()
            ->withCount('solutions')
            ->with('mentor_areas')
            ->findOrFail($id);
    }

    public function delete($id)
    {
        return $this->mentor
            ->mentor()
            ->findOrFail($id)
            ->delete();
    }
}
