<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Type;
use Illuminate\Http\Request;
use  App\Models\RemoteController;

class  RemoteControle extends Controller
{
   public function index()
{
    $remotes = RemoteController::all();
    $types = Type::all();

    return Inertia::render('remoteControl/Index', [
        'remotes' => $remotes,
        'types' => $types, 
    ]);
}

    public function create()
    {


        $types = Type::all(); 

        return Inertia::render('remoteControl/Create', [
            'types' => $types,
        ]);
    }

    public function store(Request $request)
     {
    $data = $request->validate([
        'brand' => 'required|string',
        'model' => 'required|string',
        'type_id' => 'required|string',
        'attributes' => 'array',
    ]);

     RemoteController::create($data);

    return redirect()->route('remoteControl.index')->with('success', 'Created');
   }

   public function destroy(RemoteController $remote)
    {
        $remote->delete();

        return redirect()->route('remoteControl.index')->with('success', 'Remote deleted successfully.');
    }

}
