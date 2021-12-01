<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Task;

class TaskManagement extends Component
{
    protected $listeners = ['taskDragged'=>'updateStatus'];

    public $assignedTasks, $inProgressTasks, $reviewTasks, $doneTasks;

    public $projectIdToFilter, $userIdToFilter;

    public $projectId, $userId, $taskDetails, $deadLine;
    public $usersOfSelectedProject;
    public $isAddModalVisible = false;
    public $toEditTaskId;
    public $isEditModalVisible = false;
    public $infoMessage;

    public $isDeleteModalVisible = false;
    
    public function render()
    {
        // Get Assigned Tasks
        $assignedTasks = Task::where('status', '1');
        if($this->projectIdToFilter){
            $assignedTasks = $assignedTasks->where('project_id', $this->projectIdToFilter);
        }
        if($this->userIdToFilter){
            $assignedTasks = $assignedTasks->where('user_id', $this->userIdToFilter);
        }
        $this->assignedTasks = $assignedTasks->latest()->get();

        // Get InProgress Tasks
        $inProgressTasks = Task::where('status', '2');
        if($this->projectIdToFilter){
            $inProgressTasks = $inProgressTasks->where('project_id', $this->projectIdToFilter);
        }
        if($this->userIdToFilter){
            $inProgressTasks = $inProgressTasks->where('user_id', $this->userIdToFilter);
        }
        $this->inProgressTasks = $inProgressTasks->latest()->get();


        // Get Review Tasks
        $reviewTasks = Task::where('status', '3');
        if($this->projectIdToFilter){
            $reviewTasks = $reviewTasks->where('project_id', $this->projectIdToFilter);
        }
        if($this->userIdToFilter){
            $reviewTasks = $reviewTasks->where('user_id', $this->userIdToFilter);
        }
        $this->reviewTasks = $reviewTasks->latest()->get();

        // Get Done Tasks
        $doneTasks = Task::where('status', '4');
        if($this->projectIdToFilter){
            $doneTasks = $doneTasks->where('project_id', $this->projectIdToFilter);
        }
        if($this->userIdToFilter){
            $doneTasks = $doneTasks->where('user_id', $this->userIdToFilter);
        }
        $this->doneTasks = $doneTasks->latest()->get();

        return view('livewire.task-management');
    }

    public function showAddModal(){
        $this->projectId = $this->userId = $this->taskDetails = $this->deadLine = NULL;
        $this->isAddModalVisible = true;
    }

    public function UpdatedprojectId(){
        if(!empty($this->projectId)){
            $userIdsArr = ProjectUser::where('project_id', $this->projectId)
            ->groupBy('user_id')
            ->pluck('user_id')
            ->toArray();

            $usersOfSelectedProject = User::whereIn('id', $userIdsArr)
            ->where('role', 'user')
            ->get();

            if($usersOfSelectedProject->count() > 0){
                $this->infoMessage = NULL;
                $this->usersOfSelectedProject = $usersOfSelectedProject;
            }else{
                $this->infoMessage = 'No users assigned to this project.';
                $this->usersOfSelectedProject = NULL;
            }
        }
    }

    public function storeTask(){
        $this->validate([
            'projectId'=>['required'],
            'userId'=>['required'],
            'taskDetails'=>['required'],
            'deadLine'=>['required', 'date'],
        ]);

        Task::create([
            'project_id'=>$this->projectId,
            'user_id'=>$this->userId,
            'task_details'=>$this->taskDetails,
            'status'=>'1',
            'deadline'=>$this->deadLine,
        ]);

        $this->projectId = $this->userId = $this->taskDetails = $this->deadLine = NULL;
        session()->flash('success', 'Assigned.');
        $this->isAddModalVisible = false;
    }

    public function openEditModal($id){
        $this->toEditTaskId = $id;

        $theTask = Task::find($id);

        $this->projectId = $theTask->project_id;
        $this->UpdatedprojectId();
        $this->userId = $theTask->user_id;
        $this->taskDetails = $theTask->task_details;
        $this->deadLine = $theTask->deadline;

        //Now Open Modal
        $this->isEditModalVisible = true;
    }

    public function updateTask(){
        $this->validate([
            'projectId'=>['required'],
            'userId'=>['required'],
            'taskDetails'=>['required'],
            'deadLine'=>['required', 'date'],
        ]);

        Task::find($this->toEditTaskId)->update([
            'project_id'=>$this->projectId,
            'user_id'=>$this->userId,
            'task_details'=>$this->taskDetails,
            'deadline'=>$this->deadLine,
        ]);

        $this->projectId = $this->userId = $this->taskDetails = $this->deadLine = NULL;
        session()->flash('success', 'Updated.');
        $this->isEditModalVisible = false;
    }
    

    public function openDeleteModal($id){
        $this->toDeleteTaskId = $id;
        $this->isDeleteModalVisible = true;
    }

    public function destroy(){
        Task::find($this->toDeleteTaskId)->delete();

        $this->toDeleteTaskId = NULL;
        session()->flash('danger', 'Deleted.');
        $this->isDeleteModalVisible = false;
    }
    
    /**
     * changeStatus
     * This function chnages the status
     * when task is dragged to another column
     * @param  mixed $taskId
     * @param  mixed $newStatus
     * @return void
     */
    public function updateStatus($taskId, $newStatus){
        switch ($newStatus) {
            case 'assigned':
                $status = '1';
                break;
            
            case 'inProgress':
                $status = '2';
                break;
        
            case 'review':
                $status = '3';
                break;
        
            case 'done':
                $status = '4';
                break;
            
            default:
                # code...
                break;
        }

        Task::find($taskId)->update([
            'status'=>$status,
        ]);
    }
}
