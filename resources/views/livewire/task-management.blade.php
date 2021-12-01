<div>
    <x-jet-button wire:click="showAddModal">{{ __('+ Create Task') }}</x-jet-button>

    <select wire:model.lazy="projectIdToFilter" class="inline-flex items-center  bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest transition">
        <option value="">All Projects</option>
        @foreach(\App\Models\Project::latest()->get() as $item)
            <option value="{{$item->id}}">{{$item->project_name}}</option>
        @endforeach
    </select>

    <select wire:model.lazy="userIdToFilter" class="inline-flex items-center  bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest transition">
        <option value="">All Users</option>
        @foreach(\App\Models\User::where('role', 'user')->latest()->get() as $each)
            <option value="{{$each->id}}">{{$each->first_name}}</option>
        @endforeach
    </select>

    @if (Session('success'))
    <ul class="mt-3 list-disc list-inside text-sm text-green-600">
        <li class="font-medium">{{Session('success')}}</li>
    </ul>
    @endif

    @if (Session('danger'))
    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
        <li class="font-medium">{{Session('danger')}}</li>
    </ul>
    @endif

    <style>
    .portlet-placeholder {
        border: 1px dotted black;
        height: 75px;
    }
    </style>

    <!-- Different Task Boards depending on Task Status -->
    <div class="grid md:grid-cols-4 sm:grid-cols-1 lg:grid-cols-4 m-5 mb-10 gap-4">
        <div class="bg-white overflow-hidden border border-gray-200">
            <div class="m-2 text-justify text-sm">
                <h2 class="font-bold text-lg h-2 mb-8">Assigned</h2>
                <div class="column" id="assigned">
                    <div class="border-indigo-300 p-1">
                        <!-- Do not remove -->
                    </div>
                    @forelse ($assignedTasks as $item)
                    <div id="{{$item->id}}" class="border-gray-300 p-1" style="cursor: pointer">
                        <span class="text-red-600 float-right p-2">
                            <button wire:click="openDeleteModal({{$item->id}})" class="p-1 text-red-600 hover:bg-red-600 hover:text-white rounded">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>

                        <div wire:click="openEditModal({{$item->id}})" class="border hover:bg-red-100 p-3 rounded-lg">
                            <p class="leading-relaxed text-base">
                                {{$item->task_details}}
                            </p>     
                                             
                            <div class="text-center leading-none flex justify-between w-full">
                                <span class="mr-3 text-pink-600 inline-flex items-center leading-none text-xs italic py-1">
                                    #  {{$item->project->project_name}}
                                </span>
                                <span class="inline-flex text-pink-600 items-center leading-none text-xs italic">
                                    Assigned On: {{date('d M, Y', strtotime($item->created_at))}}
                                </span>                                    
                            </div>
                            <div class="text-center leading-none flex justify-between w-full">
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none {{$item->deadline <= today() ? 'text-red-600 bg-red-200' : 'text-green-600 bg-green-200'}} rounded-full">
                                    <svg class="fill-current w-4 h-4 mr-2 {{$item->deadline <= today() ? 'text-red-500' : 'text-green-500'}}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>               
                                    {{date('d M, Y', strtotime($item->deadline))}}
                                </span> 
                                <span class=" inline-flex items-center leading-none text-sm">
                                <img width="22" height="22" class="rounded-full" src="{{$item->user->profile_photo_url}}" alt="">
                                    &nbsp; {{$item->user->first_name}}
                                </span>                                    
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>


        <div class="bg-white overflow-hidden border border-gray-200">
            <div class="m-2 text-justify text-sm">
                <h2 class="font-bold text-lg h-2 mb-8">In Progress</h2>
                <div class="column" id="inProgress">
                    <div class="border-indigo-300 p-1">
                        <!-- Do not remove -->
                    </div>
                    @foreach ($inProgressTasks as $item)
                    <div id="{{$item->id}}" class="border-indigo-300 p-1" style="cursor: pointer">
                        <span class="text-red-600 float-right p-2">
                            <button wire:click="openDeleteModal({{$item->id}})" class="p-1 text-red-600 hover:bg-red-600 hover:text-white rounded">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>

                        <div wire:click="openEditModal({{$item->id}})" class="border hover:bg-indigo-100 p-3 rounded-lg">
                            <p class="leading-relaxed text-base">
                            {{$item->task_details}}
                            </p>  
                                        
                            <div class="text-center leading-none flex justify-between w-full">
                                <span class="mr-3 text-black inline-flex items-center leading-none text-xs italic py-1">
                                    #  {{$item->project->project_name}}
                                </span>
                                <span class="inline-flex text-black items-center leading-none text-xs italic">
                                    Assigned On: {{date('d M, Y', strtotime($item->created_at))}}
                                </span>                                    
                            </div>
                            <div class="text-center leading-none flex justify-between w-full">
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none {{$item->deadline <= today() ? 'text-red-600 bg-red-200' : 'text-green-600 bg-green-200'}} rounded-full">
                                    <svg class=" fill-current w-4 h-4 mr-2 {{$item->deadline <= today() ? 'text-red-500' : 'text-green-500'}}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>               
                                    {{date('d M, Y', strtotime($item->deadline))}}
                                </span> 
                                <span class=" inline-flex items-center leading-none text-sm">
                                <img width="22" height="22" class="rounded-full" src="{{$item->user->profile_photo_url}}" alt="">
                                    &nbsp; {{$item->user->first_name}}
                                </span>                                    
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>


        <div class="bg-white overflow-hidden border border-gray-200">
            <div class="m-2 text-justify text-sm">
                <h2 class="font-bold text-lg h-2 mb-8">Review</h2>
                <div class="column" id="review">
                    <div class="border-indigo-300 p-1">
                        <!-- Do not remove -->
                    </div>
                    @foreach ($reviewTasks as $item)
                    <div id="{{$item->id}}" class="p-1" style="cursor: pointer">
                        <span class="text-red-600 float-right p-2">
                            <button wire:click="openDeleteModal({{$item->id}})" class="p-1 text-red-600 hover:bg-red-600 hover:text-white rounded">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>

                        <div wire:click="openEditModal({{$item->id}})" class="border hover:bg-blue-300 p-3 rounded-lg">
                            <p class="leading-relaxed text-base">
                                {{$item->task_details}}
                            </p>    
                            
                            <div class="text-center leading-none flex justify-between w-full">
                                <span class="mr-3 text-blue-600 inline-flex items-center leading-none text-xs italic py-1">
                                    #  {{$item->project->project_name}}
                                </span>
                                <span class="inline-flex text-blue-600 items-center leading-none text-xs italic">
                                    Assigned On: {{date('d M, Y', strtotime($item->created_at))}}
                                </span>                                    
                            </div>
                            <div class="text-center leading-none flex justify-between w-full">
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none {{$item->deadline <= today() ? 'text-red-600 bg-red-200' : 'text-green-600 bg-green-200'}} rounded-full">
                                    <svg class=" fill-current w-4 h-4 mr-2 {{$item->deadline <= today() ? 'text-red-500' : 'text-green-500'}}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>               
                                    {{date('d M, Y', strtotime($item->deadline))}}
                                </span> 
                                <span class=" inline-flex items-center leading-none text-sm">
                                <img width="22" height="22" class="rounded-full" src="{{$item->user->profile_photo_url}}" alt="">
                                    &nbsp; {{$item->user->first_name}}
                                </span>                                    
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden border border-gray-200">
            <div class="m-2 text-justify text-sm">
                <h2 class="font-bold text-lg h-2 mb-8">Done</h2>
                <div class="column" id="done">
                    <div class="border-indigo-300 p-1">
                        <!-- Do not remove -->
                    </div>
                    @foreach ($doneTasks as $item)
                    <div id="{{$item->id}}" class="p-1" style="cursor: pointer">
                        <span class="text-red-600 float-right p-2">
                            <button wire:click="openDeleteModal({{$item->id}})" class="p-1 text-red-600 hover:bg-red-600 hover:text-white rounded">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>

                        <div wire:click="openEditModal({{$item->id}})" class="border hover:bg-green-100 p-3 rounded-lg">
                            <p class="leading-relaxed text-base">
                                {{$item->task_details}}
                            </p>  
                            
                            <div class="text-center leading-none flex justify-between w-full">
                                <span class="mr-3 text-green-600 inline-flex items-center leading-none text-xs italic py-1">
                                    #  {{$item->project->project_name}}
                                </span>
                                <span class="inline-flex text-green-600 items-center leading-none text-xs italic">
                                    Assigned On: {{date('d M, Y', strtotime($item->created_at))}}
                                </span>                                    
                            </div>
                            <div class="text-center leading-none flex justify-between w-full">
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-green-600 bg-green-200 rounded-full">
                                    <svg class=" fill-current w-4 h-4 mr-2 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>               
                                    {{date('d M, Y', strtotime($item->deadline))}}
                                </span> 
                                <span class=" inline-flex items-center leading-none text-sm">
                                <img width="22" height="22" class="rounded-full" src="{{$item->user->profile_photo_url}}" alt="">
                                    &nbsp; {{$item->user->first_name}}
                                </span>                                    
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>            
    </div>

    <script>
    $( function() {
        $( ".column" ).sortable({
            connectWith: ".column",
            placeholder: "portlet-placeholder",
            update: function(event, ui) {
                if(ui.sender != null){
                    // console.log('item id: ', ui.item.context.id);
                    // console.log('new status: ', event.target.id);
                    Livewire.emit('taskDragged', ui.item.context.id, event.target.id);
                }
            }
        });
    } );
  </script>
    
    <!-- Assign Task Modal -->
    <x-jet-dialog-modal wire:model="isAddModalVisible" maxWidth="2xl">
        <x-slot name="title">
            {{ __('Create Task') }}
            <p class="text-red-600">{{$infoMessage}}</p>
        </x-slot>

        <x-slot name="content">  
            <div class="col-span-6 sm:col-span-4 p-2">                
                <x-jet-label for="projectId" value="{{ __('Select Project') }}" />
                <select wire:model.lazy="projectId" name="projectId" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    <option value="">Select</option>
                    @foreach(\App\Models\Project::latest()->get() as $item)
                        <option value="{{$item->id}}">{{$item->project_name}}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="projectId" class="mt-2" />
            </div>     
            
            <div class="col-span-6 sm:col-span-4 p-2">                
                <x-jet-label for="userId" value="{{ __('Select User') }}" />
                <select wire:model.lazy="userId" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    <option value="">Select</option>
                    @if(!is_null($usersOfSelectedProject))
                    @foreach($usersOfSelectedProject as $each)
                        <option value="{{$each->id}}">{{$each->first_name}}</option>
                    @endforeach
                    @endif
                </select>
                <x-jet-input-error for="userId" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4 p-2">                
                <x-jet-label for="taskDetails" value="{{ __('Task Details') }}" />
                <textarea wire:model.lazy="taskDetails" class="mt-1 block w-full" cols="30" rows="5"></textarea>
                <x-jet-input-error for="taskDetails" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4 p-2">                
               <x-jet-label for="deadLine" value="{{ __('Deadline') }}" />
                <x-jet-input type="date" class="mt-1 block w-full" wire:model.lazy="deadLine"/>
                <x-jet-input-error for="deadLine" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('isAddModalVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}  
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="storeTask" wire:loading.attr="disabled">
                {{ __('Assign') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>




    <!-- Edit Task Modal -->
    <x-jet-dialog-modal wire:model="isEditModalVisible" maxWidth="2xl">
        <x-slot name="title">
            {{ __('Edit Task') }}
            <p class="text-red-600">{{$infoMessage}}</p>
        </x-slot>

        <x-slot name="content">  
            <div class="col-span-6 sm:col-span-4 p-2">                
                <x-jet-label for="projectId" value="{{ __('Select Project') }}" />
                <select wire:model.lazy="projectId" name="projectId" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    <option value="">Select</option>
                    @foreach(\App\Models\Project::latest()->get() as $item)
                        <option value="{{$item->id}}">{{$item->project_name}}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="projectId" class="mt-2" />
            </div>     
            
            <div class="col-span-6 sm:col-span-4 p-2">                
                <x-jet-label for="userId" value="{{ __('Select User') }}" />
                <select wire:model.lazy="userId" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    <option value="">Select</option>
                    @if(!is_null($usersOfSelectedProject))
                    @foreach($usersOfSelectedProject as $each)
                        <option value="{{$each->id}}">{{$each->first_name}}</option>
                    @endforeach
                    @endif
                </select>
                <x-jet-input-error for="userId" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4 p-2">                
                <x-jet-label for="taskDetails" value="{{ __('Task Details') }}" />
                <textarea wire:model.lazy="taskDetails" class="mt-1 block w-full" cols="30" rows="5"></textarea>
                <x-jet-input-error for="taskDetails" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4 p-2">                
               <x-jet-label for="deadLine" value="{{ __('Deadline') }}" />
                <x-jet-input type="date" class="mt-1 block w-full" wire:model.lazy="deadLine"/>
                <x-jet-input-error for="deadLine" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('isEditModalVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}  
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="updateTask" wire:loading.attr="disabled">
                {{ __('Update') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Delete Confirm Modal -->
    <x-jet-confirmation-modal wire:model="isDeleteModalVisible">
        <x-slot name="title">
            {{ __('Are You Sure?') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Once deleted can not be reversed.') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('isDeleteModalVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="destroy()" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>  