<?php

namespace App\Services;

use App\Commons\Utils;
use App\Models\Mentor;
use App\User;

class UserService
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index($type = 'startup')
    {
        if($type == 'mentor') {
            return Mentor::query()
                ->where('type', $type)
                ->withCount('solutions')
                ->with(['mentor_areas'])
                ->get();
        }

        return User::query()
            ->where('type', $type)
            ->get();
    }

    /**
     * @param $password
     */
    public function resetPassword($password)
    {
        $user = request()->user();
        $user->password = $password;

        if ($user->isMentor()) {
            $meta = $user->meta;
            $meta->is_initial = false;
            $user->meta = $meta;
        }

        $user->save(); // save changes
    }

    public function saveUser($data)
    {
        $data['type'] = 'startup';
        return $this->create($data);
    }

    public function saveAdmin($data)
    {
        $data['type'] = 'admin';
        return $this->create($data);
    }

    public function saveInvestor($data)
    {
        $data['type'] = 'investor';
        return $this->create($data);
    }

    public function saveMentor($data)
    {
        $data['meta']['is_initial'] = 'true';
        $data['password'] = $data['meta']['default_password'];
        unset($data['meta']['default_password']);

        /** @var Mentor $mentor */
        $mentor = Mentor::query()->create($data);

        $mentor_areas = array_get($data, 'meta.mentor_ship_areas');
        foreach ($mentor_areas as $area_id) {
            $mentor->mentor_areas()->attach($area_id);
        }

        return $mentor;
    }

    public function create($data)
    {
        return User::query()->create($data);
    }

    public function update($data)
    {
        $data = $this->filterData($data);
        $user = request()->user();
        $user->update($data);
        return $user->fresh();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function filterData($data)
    {
        $collection = collect($data);

        $filtered =  $collection
            ->except([
                'password',
                'meta',
                'type',
                'email',
            ])
            ->toArray();

        return $filtered;
    }
}
