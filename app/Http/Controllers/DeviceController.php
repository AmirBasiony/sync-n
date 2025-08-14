<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use  App\Models\Widgets;
use App\Models\Type;
use App\Models\WidgetsData;
use Illuminate\Support\Str;
use Inertia\Inertia;

class DeviceController extends Controller
{
    public function index(Request $request)
    {    
        $query = Device::with('site', 'area', 'type')->orderBy('created_at', 'desc');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($createdAt = $request->input('created_at')) {
            $query->whereDate('created_at', $createdAt);
        }

        if ($request->has('is_used') && $request->input('is_used') !== '') {
            $query->where('is_use', (bool) $request->input('is_used'));
        }

        $devices = $query->paginate(10)->withQueryString();

        return Inertia::render('devices/Index', compact('devices'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'selected_widgets' => 'nullable|array',
            'selected_widgets.*' => 'string',
        ]);

        $widgets = collect();

        //$typeId = null;
        $typeId = $request->type_id ?? $widgets->first()?->type_id;
        if (!empty($validated['selected_widgets'])) {
            $widgets = Widgets::whereIn('_id', $validated['selected_widgets'])->get();
            $typeId = $widgets->first()?->type_id;
        }


        $device = Device::create([
            'factory_name' => $validated['name'],
            'production_name' => '',
            'auth_token' => Str::random(32),
            'site_id' => '',
            'type_id' =>  $typeId,
            'icon_id' => '',
            'connection_status' => false,
            'activated_at' => now(),
            'user_id' => null,
        ]);

        if (!empty($validated['selected_widgets'])) {
            $widgets = Widgets::whereIn('_id', $validated['selected_widgets'])->get();

            foreach ($widgets as $widget) {

                WidgetsData::create([
                    'name' => is_array($widget->name) ? ($widget->name['en'] ?? 'Unnamed') : $widget->name,
                    'type_id' => (string) $widget->type_id,
                    'area_id' => (string) ($widget->area_id ?? ''),
                    'category_id' => (string) ($widget->category_id ?? ''),
                    'icon_id' => (string) ($widget->icon_id ?? ''),
                    'device_id' => (string) $device->_id,
                    'attributes' => $widget->attributes,
                    'visible' => true,
                    'production_name' => $widget->production_name ?? '',
                    'factory_name' => $widget->factory_name ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        return redirect()->route('devices.index')->with('message', 'Device created successfully');
    }


    


    public function byType($typeId)
    {
        try {
            $typeId = (string) $typeId;

            $widgets = Widgets::where('type_id', $typeId)->get();


            $widgetsFormatted = $widgets->map(function ($widget) {
                return [
                    'id' => (string) $widget->_id,
                    'name' => $widget->name ?? 'Unnamed Widget',
                ];
            });

            return response()->json($widgetsFormatted);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function create()
    {
        $types = Type::all();

        return Inertia::render('devices/Create', compact('types'));
    }

    public function destroy(Device $device)
    {
        $device->widgetsData()->delete();

        $device->delete();

        return redirect()->route('devices.index')->with('message', 'Device and its widgets deleted successfully');
    }


     public function show(\App\Models\Device $device)
    {
        $device->load('type', 'site', 'area');

        $widgetsQuery = Widgets::query();

        if (!empty($device->type_ids) && is_array($device->type_ids)) {
            $widgetsQuery->whereIn('type_id', array_map('strval', $device->type_ids));
        } else {
            $widgetsQuery->where('type_id', (string) $device->type_id);
        }

        $widgets = $widgetsQuery->get(['_id','name','attributes','type_id','created_at']);

        return response()->json([
            'device' => [
                'id'                => (string) $device->_id,
                'factory_name'      => $device->factory_name,
                'production_name'   => $device->production_name,
                'auth_token'        => $device->auth_token,
                'type'              => $device->type?->name,
                'site'              => $device->site?->name,
                'area'              => $device->area?->name,
                'connection_status' => (bool) $device->connection_status,
                'activated_at'      => $device->activated_at,
                'created_at'        => $device->created_at,
            ],
            'widgets' => $widgets->map(function ($w) {
                return [
                    'id'         => (string) $w->_id,
                    'name'       => is_array($w->name) ? ($w->name['en'] ?? 'Unnamed') : $w->name,
                    'attributes' => $w->attributes ?? [],
                    'type_id'    => (string) $w->type_id,
                    'created_at' => $w->created_at,
                ];
            }),
        ]);
    }

}
