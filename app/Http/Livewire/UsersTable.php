<?php

namespace App\Http\Livewire;

use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class UsersTable extends LivewireDatatable
{
    public $model = User::class;

    protected $listeners = ['newUserAdded'=>'columns'];
    public $exportable = false;
    public $searchable = "id, first_name, last_name, email";
    public $hideable = 'select';
    public $afterTableSlot = 'livewire.user-table-modals';

    /*Properties for Edit User*/
    public $isEditModalVisible = false;
    public $toEditUserId;
    public $firstName, $lastName;

    public function builder(){
        return User::where('role', '!=', 'admin');
    }
    
    /**
     * Livewire DataTable
     * to show the Users
     *
     * @return void
     */
    public function columns(){
        return [
            NumberColumn::name('id')
            ->label('ID')
            ->alignCenter(),
            
            Column::name('first_name')
            ->label('First Name')
            ->alignCenter(),

            Column::name('last_name')
            ->label('Last Name')
            ->alignCenter(),

            Column::name('email')
            ->label('Email')
            ->alignCenter(),

            Column::callback('role', function($role){
                if($role == 'admin'){
                    $roleName = 'Admin'; 
                    $roleColor = 'indigo-600';
                }elseif($role == 'user'){
                    $roleName = 'User'; 
                    $roleColor = 'blue-500';
                }elseif($role == 'customer'){
                    $roleName = 'Customer'; 
                    $roleColor = 'indigo-600';
                }
                return "<span class='px-2 py-1 mr-2 text-xs font-bold leading-none text-white bg-$roleColor rounded-full'>$roleName</span>";
            })->label('Role')
            ->filterable(['User', 'Customer'])
            ->alignCenter()
            ->unsortable(),

            Column::callback(['id','is_active'], function($id, $isActive){
                if($isActive == 1) {
                    $activeStatus = 'Active'; 
                    $isActiveColor = 'blue-500';
                }else{
                    $activeStatus = 'Inactive'; 
                    $isActiveColor = 'red-600';
                }
                return "<a wire:click='toogleActiveStatus($id)' href='javascript:void(0)'><span class='px-2 py-1 mr-2 text-xs font-bold leading-none text-white bg-$isActiveColor rounded-full'>$activeStatus</span></a>";
            })->label('User Status')
            ->alignCenter()
            ->unsortable(),

            DateColumn::name('created_at')
            ->label('Registered On')
            ->filterable()
            ->alignCenter(),

            Column::callback(['id'], function($id){
                return "<a href='javascript:void(0)' wire:click='openEditModal($id)'>
                    <button class='p-1 text-blue-600 hover:bg-blue-600 hover:text-white rounded'>
                        <svg class='w-5 h-5' fill='currentColor' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'><path d='M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z'></path></svg>
                    </button>
                </a>";
            })->label('Edit')
            ->alignCenter()
            ->unsortable(),

            Column::delete()
            ->label('Delete')
            ->alignCenter()
            ->unsortable(),       

        ];
    }
    
    /**
     * toogleActiveStatus
     * This change the user's ic_active status
     *
     * @param  mixed $id
     * @return void
     */
    public function toogleActiveStatus($id){
        $theUser = User::find($id);
        if($theUser->is_active == 1){
            $newIsActive = 0;
        }else{
            $newIsActive = 1;
        }

        $theUser->is_active = $newIsActive;
        $theUser->save();
    }
    
    /**
     * openEditModal
     * Opens the edit modal
     * and set edit properties values
     *
     * @param  mixed $id
     * @return void
     */
    public function openEditModal($id){
        $this->toEditUserId = $id;

        $theUser = User::find($id);

        $this->firstName = $theUser->first_name;
        $this->lastName = $theUser->last_name;

        //Now Open Modal
        $this->isEditModalVisible = true;
    }
    
    /**
     * updateUser
     * Update user's info
     *
     * @return void
     */
    public function updateUser(){
        $this->validate([
            'firstName'=>['required', 'string', 'max:255'],
            'lastName'=>['required', 'string', 'max:255'],
        ]);

        User::find($this->toEditUserId)->update([
            'first_name'=>$this->firstName, 
            'last_name'=>$this->lastName
        ]);

        $this->toEditUserId = $this->firstName = $this->lastName = NULL;
        session()->flash('success', 'User Details Updated.');

        //Now Close Modal
        $this->isEditModalVisible = false;
    }
}