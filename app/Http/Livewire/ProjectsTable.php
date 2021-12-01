<?php

namespace App\Http\Livewire;

use App\Models\Project;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ProjectsTable extends LivewireDatatable
{
    public $model = Project::class;

    protected $listeners = ['newProjectAdded'=>'columns'];
    public $exportable = false;
    public $searchable = "id, project_name, project_description";
    public $hideable = 'select';
    public $afterTableSlot = 'livewire.project-table-modals';

    /*Properties for Edit User*/
    public $isEditModalVisible = false;
    public $toEditProjectId;
    public $projectName, $projectDescription;
    
    /**
     * Livewire DataTable
     * to show the Projects
     *
     * @return void
     */
    public function columns(){
        return [
            NumberColumn::name('id')
            ->label('ID')
            ->alignCenter(),

            Column::name('project_name')
            ->label('Project Name')
            ->alignCenter(),

            Column::name('project_description')
            ->label('Project Description')
            ->alignCenter(),

            Column::callback(['id','is_active'], function($id, $isActive){
                if($isActive == 1) {
                    $activeStatus = 'Active'; 
                    $isActiveColor = 'blue-500';
                }else{
                    $activeStatus = 'Inactive'; 
                    $isActiveColor = 'red-600';
                }
                return "<a wire:click='toogleActiveStatus($id)' href='javascript:void(0)'><span class='px-2 py-1 mr-2 text-xs font-bold leading-none text-white bg-$isActiveColor rounded-full'>$activeStatus</span></a>";
            })->label('Project Status')
            ->alignCenter()
            ->unsortable(),

            DateColumn::name('created_at')
            ->label('Added On')
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
     * This change the project's ic_active status
     *
     * @param  mixed $id
     * @return void
     */
    public function toogleActiveStatus($id){
        $theProject = Project::find($id);
        if($theProject->is_active == 1){
            $newIsActive = 0;
        }else{
            $newIsActive = 1;
        }

        $theProject->is_active = $newIsActive;
        $theProject->save();
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
        $this->toEditProjectId = $id;

        $theProject = Project::find($id);

        $this->projectName = $theProject->project_name;
        $this->projectDescription = $theProject->project_description;

        //Now Open Modal
        $this->isEditModalVisible = true;
    }
    
    /**
     * updateProject
     * Update Project's info
     *
     * @return void
     */
    public function updateProject(){
        $this->validate([
            'projectName'=>['required', 'string', 'max:255'],
            'projectDescription'=>['required', 'string'],
        ]);

        Project::find($this->toEditProjectId)->update([
            'project_name'=>$this->projectName, 
            'project_description'=>$this->projectDescription,
        ]);

        $this->toEditProjectId = $this->projectName = $this->projectDescription = NULL;
        session()->flash('success', 'Project Details Updated.');

        //Now Close Modal
        $this->isEditModalVisible = false;
    }
}