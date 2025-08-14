<?php

namespace App\Http\Controllers;

use App\Models\Widgets;
use Illuminate\Http\Request;
use App\Models\Type;
use App\Models\Device;
use Inertia\Inertia;

class SubtypeController extends Controller
{    



   public function index(Request $request)
    {
        $search  = $request->string('search')->toString();
        $typeId  = $request->string('type_id')->toString();
        $created = $request->date('created_at'); // لو عايز فلترة بتاريخ الإنشاء

        $widgets = Widgets::with(['type:_id,name'])
            ->when($search, fn($q) =>
                $q->where('name', 'like', "%{$search}%")
            )
            ->when($typeId, fn($q) =>
                $q->where('type_id', $typeId)
            )
            ->when($created, fn($q) =>
                $q->whereDate('created_at', $created)
            )
            ->orderByDesc('_id')
            ->paginate(10)
            ->withQueryString();

        $types = Type::select('_id','name')->orderBy('name')->get();

        return Inertia::render('subtypes/Index', [
            'widgets' => $widgets,
            'types'   => $types,
            'filters' => [
                'search' => $search,
                'type_id'=> $typeId,
                'created_at' => optional($created)?->format('Y-m-d'),
            ],
        ]);
    }


public function show(Device $device)
{
    $device->load('type', 'site', 'area');

    $widgets = \App\Models\Widgets::where('type_id', (string) $device->type_id)
        ->get(['_id', 'name', 'attributes', 'type_id', 'created_at']);

    return response()->json([
        'device' => [
            'id' => (string) $device->_id,
            'factory_name' => $device->factory_name,
            'production_name' => $device->production_name,
            'auth_token' => $device->auth_token,
            'type' => $device->type?->name,
            'site' => $device->site?->name,
            'area' => $device->area?->name,
            'connection_status' => (bool) $device->connection_status,
            'activated_at' => $device->activated_at,
            'created_at' => $device->created_at,
        ],
        'widgets' => $widgets->map(function ($w) {
            return [
                'id' => (string) $w->_id,                        
                'name' => is_array($w->name) ? ($w->name['en'] ?? 'Unnamed') : $w->name,
                'attributes' => $w->attributes ?? [],
                'type_id' => (string) $w->type_id,
                'created_at' => $w->created_at,
            ];
        }),
    ]);
}


    public function store(Request $request)
    { 
        $validatedData = $request->validate([
            'type_id' => 'required',
            'name' => 'required|string',
            'attributes' => 'required|array|min:1',
            'attributes.*.key' => 'required|string',
            'attributes.*.value' => 'required|string',
        ]);
    
        try {
            $attributes = collect($validatedData['attributes'])
                ->mapWithKeys(function ($attr) {
                    return [$attr['key'] => $attr['value']];
                })
                ->toArray();
            
            Widgets::create([
                'type_id' => $validatedData['type_id'],
                'name' => $validatedData['name'], 
                'attributes' => $attributes,
            ]);
    
            return redirect()->route('subtypes.index')->with('success', 'Subtype created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Failed to store subtype: ' . $e->getMessage()
            ]);
        }
    }
    
    

    public function create()
    {  
         $type = Type::all();
        return Inertia::render('subtypes/Create', [
            'types' => $type,
        ]);
    }


public function edit(Widgets $subtype)
{
    $types = Type::select('_id', 'name')->orderBy('name')->get();

    return Inertia::render('subtypes/Edit', [
        'widget' => [
            '_id' => (string) $subtype->_id,
            'name_en' => is_array($subtype->name) ? ($subtype->name['en'] ?? '') : $subtype->name, // ✅ هنا الحل
            'type_id' => (string) $subtype->type_id,
            'attributes' => $subtype->attributes ?? [],
        ],
        'types' => $types->map(fn ($type) => [
            '_id' => (string) $type->_id,
            'name' => is_array($type->name) ? ($type->name['en'] ?? '') : $type->name,
        ]),
    ]);
}


public function update(Request $request, Widgets $subtype)
{
    $validated = $request->validate([
        'name_en' => 'required|string',
        'type_id' => 'required|string',
        'attributes' => 'nullable|array',
        'attributes.*.key' => 'required_with:attributes|string',
        'attributes.*.value' => 'required_with:attributes',
     ]);

    $subtype->name = $validated['name_en'];

    $subtype->type_id = $validated['type_id'];

      if (!empty($validated['attributes'])) {
        $attributes = collect($validated['attributes'])
            ->filter(fn($item) => !empty($item['key']) && array_key_exists('value', $item))
            ->mapWithKeys(function ($item) {
                $key = $item['key'];
                $value = is_numeric($item['value']) ? $item['value'] + 0 : $item['value'];
                return [$key => $value];
            })
            ->toArray();

        $subtype->attributes = $attributes;
    }

    $subtype->updated_at = now()->toDateTimeString();
    $subtype->save();

    return redirect()->route('subtypes.index')->with('success', 'Widget updated successfully.');
}







    public function destroy(Widgets $subtype)
    {
        $subtype->delete();

        return redirect()->route('subtypes.index')->with('message', 'subtype deleted successfully');
    }


}
