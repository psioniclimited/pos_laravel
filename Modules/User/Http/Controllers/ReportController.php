<?php

namespace Modules\User\Http\Controllers;

use App\Exports\ReportFactory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    public function index(Request $request, ReportFactory $factory)
    {
        return $factory->getReport($request);
    }
}
