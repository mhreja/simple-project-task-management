<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ProjectUserTable extends LivewireDatatable
{
    public $model = ProjectUser::class;

    public $exportable = false;
    public $searchable = "project.project_name";
    public $hideable = 'select';
    public $beforeTableSlot = 'livewire.projects-assigning-table-before';
    public $afterTableSlot = 'livewire.projects-assigning-table-after';
    
    public $projectId , $userId, $customerId;

    /*New Assign*/
    public $isAddModalVisible = false;    

    /*Properties for Editing*/
    public $isEditModalVisible = false;
    public $toEditProjectUserProjectId;

    /*Properties for Custom Delete*/
    public $isDeleteModalVisible = false;
    public $toDeleteProjectUserProjectId;


    public function builder(){
        return ProjectUser::with(['project', 'user'])
        ->groupBy('project_id');
    }
    
    /**
     * Livewire DataTable
     * to show the Projects USers Assiginings
     *
     * @return void
     */
    public function columns(){
        return [
            Column::name('project.project_name')
            ->label('Project Name')
            ->filterable($this->projects)
            ->alignCenter(),


            Column::callback(['id', 'project_id'], function($id, $projectId){
                $projectUserIds = ProjectUser::where('project_id', $projectId)
                ->pluck('user_id');                
                $customers = User::whereIn('id', $projectUserIds)
                ->where('role', 'customer')
                ->pluck('first_name')
                ->toArray();
                return implode(', ', $customers);
            })
            ->label('Assigned To Customers')
            //->filterable($this->customers)
            ->alignCenter()
            ->unsortable(), 

            Column::callback(['project_id'], function($projectId){
                $projectUserIds = ProjectUser::where('project_id', $projectId)->pluck('user_id');                
                $users = User::whereIn('id', $projectUserIds)->where('role', 'user')->pluck('first_name')->toArray();
                return implode(', ', $users);
            })
            ->label('Assigned To Users')
            // ->filterable($this->users)
            ->alignCenter()
            ->unsortable(),

            Column::callback(['project.id','project.is_active'], function($id, $isActive){
                if($isActive == 1) {
                    $activeStatus = 'Active'; 
                    $isActiveColor = 'blue-500';
                }else{
                    $activeStatus = 'Inactive'; 
                    $isActiveColor = 'red-600';
                }
                return "<span class='px-2 py-1 mr-2 text-xs font-bold leading-none text-white bg-$isActiveColor rounded-full'>$activeStatus</span>";
            })->label('Project Status')
            ->alignCenter()
            ->unsortable(),

            DateColumn::name('created_at')
            ->label('Assigned On')
            ->filterable()
            ->alignCenter(),

            Column::callback(['id', 'project_id', 'created_at'], function($id, $projectId){               
                return view('livewire..actions.assign-table-actions', ['id' => $id, 'projectId' => $projectId]);
            })->label('Action')
            ->alignCenter()
            ->unsortable(),    

        ];
    }


    public function getProjectsProperty(){
        return Project::pluck('project_name');
    }

    public function getUsersProperty(){
        return User::where('role', 'user')->pluck('first_name');
    }

    public function getCustomersProperty(){
        return User::where('role', 'customer')->pluck('first_name');
    }


    /**
     * showAddModal
     * Show the modal
     * form to add New Assign
     *
     * @return void
     */
    public function showAddModal(){
        $this->projectId = NULL;
        //Emit event to clear users & Customer selections on Add New Modal
        $this->emit('clearUsersSelections');

        //Now Open Modal
        $this->isAddModalVisible = true;
    }

    /**
     * Store New Assigning
     *
     * @return void
     */
    public function store(){
        $this->validate([
            'projectId'=>['required'],
            'userId'=>['required'],
            'customerId'=>['required']
        ]);

        $insertArr = [];
        foreach($this->userId as $id){
            $insertArr[] = [
                'project_id'=>$this->projectId,
                'user_id'=>$id,
                'created_at'=>now()->toDateTimeString(),
                'updated_at'=>now()->toDateTimeString()
            ];
        }

        foreach($this->customerId as $cusId){
            $insertArr[] = [
                'project_id'=>$this->projectId,
                'user_id'=>$cusId,
                'created_at'=>now()->toDateTimeString(),
                'updated_at'=>now()->toDateTimeString()
            ];
        }

        ProjectUser::insert($insertArr);

        $this->projectId = $this->userId = $this->customerId = NULL;
        session()->flash('success', 'Assigned.');
        $this->isAddModalVisible = false;
    }
    
    /**
     * openEditModal
     * Opens the edit modal
     * and set edit properties values
     *
     * @param  mixed $id
     * @return void
     */
    public function openEditModal($projectId){
        $this->toEditProjectUserProjectId = $projectId;

        $theProjectUsers = ProjectUser::where('project_id', $projectId)->get();

        $this->userId = [];
        $this->customerId = [];

        foreach($theProjectUsers as $theProjectUser){
            $this->projectId = $theProjectUser->project_id;

            if($this->getUserRole($theProjectUser->user_id) == 'user'){
                $this->userId[] = $theProjectUser->user_id;
            }else{
                $this->customerId[] = $theProjectUser->user_id;
            }
        }

        //Emit event to populate preselcted Users & Customers on Edit Modal
        $this->emit('populatePreselectedUsers');

        //Now Open Modal
        $this->isEditModalVisible = true;
    }
    
    /**
     * update ProjectUser
     *
     * @return void
     */
    public function update(){
        $this->validate([
            'projectId'=>['required'],
            'userId'=>['required'],
            'customerId'=>['required']
        ]);

        $createdAt = ProjectUser::where('project_id', $this->toEditProjectUserProjectId)->first()->created_at;

        //Delete Old ones
        ProjectUser::where('project_id', $this->toEditProjectUserProjectId)->delete();

        $insertArr = [];
        foreach($this->userId as $id){
            $insertArr[] = [
                'project_id'=>$this->projectId,
                'user_id'=>$id,
                'created_at'=>$createdAt,
                'updated_at'=>now()->toDateTimeString()
            ];
        }

        foreach($this->customerId as $cusId){
            $insertArr[] = [
                'project_id'=>$this->projectId,
                'user_id'=>$cusId,
                'created_at'=>$createdAt,
                'updated_at'=>now()->toDateTimeString()
            ];
        }

        ProjectUser::insert($insertArr);

        $this->toEditProjectUserProjectId = $this->projectId = $this->userId = $this->customerId = NULL;
        session()->flash('success', 'Updated.');

        //Now Close Modal
        $this->isEditModalVisible = false;
    }
    
    /**
     * opendeleteModal
     * Open delete confirm modal
     * @param  mixed $id
     * @return void
     */
    public function openDeleteModal($projectId){
        $this->toDeleteProjectUserProjectId = $projectId;

        //Now Open Modal
        $this->isDeleteModalVisible = true;
    }

    
    /**
     * delete
     * custom delete
     * @param  mixed $id
     * @return void
     */
    public function customDelete(){
        ProjectUser::where('project_id', $this->toDeleteProjectUserProjectId)->delete();

        $this->toDeleteProjectUserProjectId = NULL;
        
        session()->flash('danger', 'Deleted.');

        //Now Close Modal
        $this->isDeleteModalVisible = false;
    }
    
    /**
     * getUserRole
     * function that returns user role
     * @param  mixed $id
     * @return void
     */
    public function getUserRole($id){
        return User::find($id)->role;
    }
}