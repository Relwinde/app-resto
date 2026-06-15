<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component
{
    #[Validate('required|string|min:3|max:255')]
    public string $restaurant_name = '';

    #[Validate('required|string|email|max:255|unique:users,email')]
    public string $email = '';

    #[Validate('required|string|min:2|max:255')]
    public string $owner_name = '';

    #[Validate('required|string|min:8')]
    public string $password = '';

    #[Validate('required|string|min:8|same:password')]
    public string $password_confirmation = '';

    public function register()
    {
        $this->validate();

        $restaurant = Restaurant::create([
            'name' => $this->restaurant_name,
            'email' => $this->email,
            'slug' => str($this->restaurant_name)->slug(),
        ]);

        $freePlan = SubscriptionPlan::where('name', 'Free')->first();
        Subscription::create([
            'restaurant_id' => $restaurant->id,
            'subscription_plan_id' => $freePlan->id,
            'started_at' => now(),
            'status' => 'active',
        ]);

        $user = User::create([
            'name' => $this->owner_name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'restaurant_id' => $restaurant->id,
        ]);

        auth()->login($user);

        return redirect()->route('app.dashboard', ['restaurantId' => $restaurant->id]);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
