<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Project;

class ProjectAdd extends Component
{
    public $isAddModalVisible = false;
    public $projectName, $projectDescription;
    
    /**
     * showAddModal
     * Show the modal
     * form to add New Project
     *
     * @return void
     */
    public function showAddModal(){
        $this->isAddModalVisible = true;
    }


    public function render()
    {
        return view('livewire.project-add');
    }


    public function storeProject(){
        $this->validate([
            'projectName'=>['required', 'string', 'max:255'],
            'projectDescription'=>['required', 'string'],
        ]);

        Project::create([
            'project_name'=>$this->projectName,
            'project_description'=>$this->projectDescription
        ]);

        $this->projectName = $this->projectDescription = NULL;
        $this->emit('newProjectAdded'); 
        session()->flash('success', 'New Project Added.');
        $this->isAddModalVisible = false;
    }
}

