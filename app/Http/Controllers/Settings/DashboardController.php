<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Type;
use Inertia\Inertia;

use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{


    public function index()
    {
        $admin_logs = Activity::with('causer')->orderBy('created_at', 'desc')->where('event', 'auth')->get();
        $devices_num = Device::count();
        $types_num = Type::count();

        return Inertia::render('Dashboard', compact('admin_logs', 'devices_num', 'types_num'));
    }

    public function home()
    {
        return redirect()->route('login');
    }
}
