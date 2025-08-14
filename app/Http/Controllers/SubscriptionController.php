<?php
namespace App\Http\Controllers;

use App\Http\Requests\Subscription\SubscriptionCreateRequest;
use App\Http\Requests\Subscription\SubscriptionUpdateRequest;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('user')->paginate(10);

        // Append the plan manually
        $subscriptions->getCollection()->transform(function ($subscription) {
            $subscription->plan = $subscription->plan;
            return $subscription;
        });

        return Inertia::render('subscriptions/Index', [
            'subscriptions' => $subscriptions,
        ]);
    }

    public function create()
    {
        $users = User::get();
        $packages = Package::select('id', 'title_en')->get();

        return Inertia::render('subscriptions/Create', [
            'users' => $users,
            'packages' => $packages,
        ]);
    }

    public function store(SubscriptionCreateRequest $request)
    {
        Subscription::create($request->validated());

        return redirect()->route('subscriptions.index')->with('success', 'Subscription created');
    }

    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);
        $users = User::get();
        $packages = Package::select('id', 'title_en')->get();

        return Inertia::render('subscriptions/Edit', [
            'subscription' => $subscription,
            'users' => $users,
            'packages' => $packages,
        ]);
    }

    public function update(SubscriptionUpdateRequest $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->update($request->validated());

        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated.');
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted.');
    }
}
