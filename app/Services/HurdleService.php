<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Models\Hurdle;
use App\Models\Mentor;
use App\Models\Solution;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HurdleService
{
    protected $model;

    public function __construct(Hurdle $hurdle)
    {
        $this->model = $hurdle;
    }

    /**
     * @param $data
     * @return mixed
     * @throws BaseException
     */
    public function save($data)
    {
        try {
            $this->model->Active(Carbon::now())->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return $this->model->create($data);
        }
        throw new BaseException('Active hurdle already exists');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model
            ->view()
            ->findOrFail($id)
            ->delete();
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $hurdle = $this->model->view()->findOrFail($id);
        $hurdle->update($data);
        return $hurdle->fresh();
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->model
            ->view()
            ->with([
                'solutions'
            ])
            ->findOrFail($id);
    }

    /**
     * @param $when
     * @return mixed
     */
    public function showActive($when)
    {
        return $this->model
            ->Active($when)
            ->view()
            ->with([
                'solutions'
            ])->get();
    }

    /**
     * @return Solution[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->model::with([
              'solutions'
        ])->view()->get();
    }
}
