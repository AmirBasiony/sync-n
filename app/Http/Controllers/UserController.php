<?php

namespace App\Http\Controllers;

use App\Events\SendNotification;
use App\Http\Requests\User\SendNotificationRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;

use App\Models\Icon;
use Carbon\Carbon;

use App\Models\Notification;
use App\Models\User;

use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {


        $search = $request->input('search');
        $createdAt = $request->input('created_at');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('gender', 'like', "%$search%");
                });
            })
            ->when($createdAt, function ($query, $createdAt) {
                try {
                    $formatted = Carbon::createFromFormat('d-m-Y', $createdAt)->format('Y-m-d');
                    $query->whereDate('created_at', $formatted);
                } catch (\Exception $e) {
                    \Log::warning('Invalid date format for blog filter: ' . $e->getMessage());
                }
            })
            ->paginate(10)
            ->withQueryString();
        return Inertia::render('user/Index', [
            'users' => $users,
        ]);
    }


    public function create()
    {
        return Inertia::render('user/Create');
    }

    public function store(UserCreateRequest $request)
    {
        $validated = $request->validated();
        User::create([
            'name' => $validated['name'],
            'password' => $validated['password'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
            'phone' => $validated['phone'],
            'email_verified_at' => $validated['email_verified_at'] ? Carbon::now() : null,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return Inertia::render('user/Edit', [
            'user' => $user,
        ]);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validated();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
            'phone' => $validated['phone'],
            'email_verified_at' => $validated['email_verified_at'] ? Carbon::now() : null,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function notification()
    {
        $users = User::get();
        $notificationIcons = Icon::where('type', 'notification')->get();

        return Inertia::render('user/Notification', [
            'users' => $users,
            'notificationIcons' => $notificationIcons,
        ]);
    }

    public function sendNotification(SendNotificationRequest $request)
    {
        $notification = Notification::create([
            'subject' => $request->validated('subject'),
            'message' => $request->validated('message'),
            'user_id' => $request->validated('user_id'),
            'icon_id' => $request->validated('icon_id'),
        ]);

        broadcast(new SendNotification($notification));

        return redirect()->back()->with('success', 'Notification send successfully');
    }


    public function createNotificationType()
    {
        return Inertia::render('user/NotificationType');
    }

    public function storeNotificationType(NotificationTypeCreateRequest $request)
    {
        Notification::create([
            'name' => $request->validated('name'),
            'icon_id' => $request->validated('icon_id')
        ]);

        return redirect()->back()->with('success', 'Notification type created successfully');
    }

}
