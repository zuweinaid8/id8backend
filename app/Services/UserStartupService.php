<?php

namespace App\Services;

use App\Models\Mentor;
use App\User;

class UserStartupService
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        return  $this->user->query()
            ->latest()
            ->ofStartup()
            ->paginate();
    }

    public function update($id, $data)
    {
        $record = $this->user->ofStartup()->findOrFail($id);
        $record->update($data);
        return $record->fresh();
    }

    public function show($id)
    {
        return $this->user
            ->ofStartup()
            ->findOrFail($id);
    }

    public function delete($id)
    {
        return $this->user
            ->ofStartup()
            ->findOrFail($id)
            ->delete();
    }
}
