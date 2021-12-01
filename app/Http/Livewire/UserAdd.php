<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Mail;
use App\Mail\WelcomeEmail;

class UserAdd extends Component
{
    public $isAddModalVisible = false;
    public $role, $firstName, $lastName, $email, $password, $password_confirmation;
    
    /**
     * showAddModal
     * Show the modal
     * form to add New user
     *
     * @return void
     */
    public function showAddModal(){
        $this->isAddModalVisible = true;
    }


    public function render()
    {
        return view('livewire.user-add');
    }


    public function storeUser(){
        $this->validate([
            'role'=>['required'],
            'firstName'=>['required', 'string', 'max:255'],
            'lastName'=>['required', 'string', 'max:255'],
            'email'=>['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', new Password, 'confirmed'],
        ]);

        User::create([
            'role'=>$this->role,
            'first_name'=>$this->firstName, 
            'last_name'=>$this->lastName, 
            'email'=>$this->email,
            'email_verified_at' => now()->format('d-m-Y h:i:s'),
            'password'=>Hash::make($this->password)
        ]);

        //send welcome email with user credentials
        Mail::send(new WelcomeEmail($this->firstName, $this->email, $this->password));

        $this->role = $this->firstName = $this->lastName = $this->email = $this->password = $this->password_confirmation = NULL;
        $this->emit('newUserAdded'); 
        session()->flash('success', 'New User Added.');
        $this->isAddModalVisible = false;
    }
}
