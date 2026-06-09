<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

class Login extends Component
{
    #[Validate('required', message: 'Entrez une adresse mail valide')]
    #[Validate('email', message: 'Entrez une adresse mail valide')]
    public $email;

    #[Validate('required', message: 'Entrez votre mot de passe')]
    public $password;

    public function render()
    {
        return view('livewire.login')->layout('components.layouts.guests');
    }

    public function login()
    {
        $this->validate();

        if (auth()->attempt($this->only('email', 'password'))) {
            session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        $this->addError('loginFailed', 'Adresse mail ou mot de passe incorrect');
    }
}
