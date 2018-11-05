<?php

namespace App\Services;

use App\Commons\ResponseUtils;
use App\Exceptions\BaseException;
use App\Models\Startup;

class StartupService
{
    use ResponseUtils;

    protected $model;

    public function __construct(Startup $startup)
    {
        $this->model = $startup;
    }

    public function show($id)
    {
        return $this->model
            ->with([
                'owner',
                'cover_photo_file',
                'logo_file',
            ])
            ->view()
            ->findOrFail($id);
    }

    public function delete($id)
    {
        return $this->model
            ->view()
            ->findOrFail($id)
            ->delete();
    }

    public function update($id, $request)
    {
        $data = $request->input(['is_registered']) ? $request->all() : $request->except(['bussiness_licence_no', 'tin_no']);
        $record = $this->model->view()->findOrFail($id);
        $record->update($data);
        return $record->fresh();
    }

    /**
     * @param $request
     * @return mixed
     * @throws BaseException
     */
    public function save($request)
    {
        if (request()->user()->startup()->exists()) {
            throw new BaseException("Startup already exists for this user");
        }
        $data = $request->input(['is_registered']) ? $request->all() : $request->except(['business_licence_no', 'tin_no']);
        $user = $request->user();
        $startup = $user->startup()->create($data);
        return $startup;
    }

    public function index()
    {
        return $this->model
            ->with([
                'owner',
                'cover_photo_file',
                'logo_file',
            ])
            ->latest()
            ->view()
            ->paginate();
    }

    public function ownerStartup()
    {
        return $this->model
            ->with([
                'owner',
                'cover_photo_file',
                'logo_file',
            ])
            ->view()
            ->first();
    }
}
