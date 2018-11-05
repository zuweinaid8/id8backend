<?php

namespace App\Http\Controllers;

use App\Commons\ResponseUtils;
use App\Http\Resources\BaseResource;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ResponseUtils;
    private $service;

    public function __construct(DashboardService $service)
    {
        /* echo "<pre/>";
        print_r($service);
        echo "dddddd";*/
        $this->service = $service;
    }

    public function getSummary()
    {
      return $this->respond('success', $this->service->getSummary(), 200);
    }
}
