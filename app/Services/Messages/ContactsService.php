<?php

namespace App\Services\Messages;

use App\Exceptions\BaseException;
use App\Models\Mentor;
use App\User;

class ContactsService
{
    /**
     * @param $user
     * @return mixed
     * @throws BaseException
     */
    public function getContactsForUser($user)
    {
//        switch ($user->type) {
//            case 'admin':
//                $contacts = User::query()
//                    ->filter(request()->all())
//                    ->paginate();
//                break;
//
//            case 'mentor':
//                $contacts = User::query()
//                    ->where('type', '=', 'startup')
//                    ->whereHas('startup', function ($q) use ($user) {
//                        return $q->whereHas('solution', function ($q) use ($user) {
//                            return $q->whereHas('mentorships', function ($q) use ($user) {
//                                return $q->where('mentor_id', '=', $user->id);
//                            });
//                        });
//                    })
//                    ->filter(request()->all())
//                    ->paginate();
//                break;
//
//            case 'startup':
//                $contacts = Mentor::query()
//                    ->where('type', '=', 'mentor')
//                    ->whereHas('mentorships', function ($q) use ($user) {
//                        return $q->whereHas('solution', function ($q) use ($user) {
//                            return $q->whereHas('startup', function ($q) use ($user) {
//                                return $q->where('owner_id', '=', $user->id);
//                            });
//                        });
//                    })
//                    ->filter(request()->all())
//                    ->paginate();
//                break;
//
//            default:
//                throw new BaseException('Invalid role type for user');
//                break;
//        }

        $contacts = User::query()
                    ->with('startup')
                    ->filter(request()->all())
                    ->paginate();

        return $contacts;
    }
}
