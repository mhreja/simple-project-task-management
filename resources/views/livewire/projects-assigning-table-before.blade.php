<div class="p-2" style="margin-top: -35px;">
    <x-jet-button wire:click="showAddModal">{{ __('+ Assign Project') }}</x-jet-button>
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

    
    <!-- Add User Modal -->
    <x-jet-dialog-modal wire:model="isAddModalVisible" maxWidth="2xl">
        <x-slot name="title">
            {{ __('Assign Project') }}
        </x-slot>

        <x-slot name="content">
            @if ($errors->any())
                <div class="col-span-6 sm:col-span-4 p-2 text-red-600">
                    <ul class="list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif  

            <div wire:key="unique_identifier_first" class="col-span-6 sm:col-span-4 p-2">   
                <x-jet-label for="projectId" value="{{ __('Select Project') }}" />
                <select wire:model.lazy="projectId" name="projectId" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    <option value="">Select</option>
                    @foreach(\App\Models\Project::whereNotIn('id', function($query){
                        $query->select('project_id')->from('project_users');
                    })->latest()->get() as $item)
                        <option value="{{$item->id}}">{{$item->project_name}}</option>
                    @endforeach
                </select>
            </div>

            <div wire:ignore wire:key="unique_identifier_second" class="col-span-6 sm:col-span-4 p-2">    
                <x-jet-label for="userId" value="{{ __('Select User') }}" />
                <select multiple name="userId" id="userId" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    @foreach(\App\Models\User::where('role', 'user')->latest()->get() as $each)
                        <option value="{{$each->id}}">{{$each->first_name}}</option>
                    @endforeach
                </select>
            </div>

            <div wire:ignore wire:key="unique_identifier_third" class="col-span-6 sm:col-span-4 p-2">            
                <x-jet-label for="customerId" value="{{ __('Select Customer') }}" />
                <select multiple name="customerId" id="customerId" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    @foreach(\App\Models\User::where('role', 'customer')->latest()->get() as $each2)
                        <option value="{{$each2->id}}">{{$each2->first_name}}</option>
                    @endforeach
                </select>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('isAddModalVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}  
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="store" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
    
    <!-- Livewire Select2 JS -->
    <script>  
        //For Users Ids
        $('#userId').select2({
            placeholder: 'Select Users',
            allowClear: true
        });
        $('#userId').on('change', function(e){
            var data = $('#userId').select2("val");
            @this.set('userId', data);
        });

        //For Customers Ids
        $('#customerId').select2({
            placeholder: 'Select Customers',
            allowClear: true
        });    
        $('#customerId').on('change', function(e){
            var data = $('#customerId').select2("val");
            @this.set('customerId', data);
        });

        //Clear selections after Inserting
        document.addEventListener('livewire:load', function(e){
            @this.on('clearUsersSelections', function(){
                $('#userId').val(null).trigger('change');
                $('#customerId').val(null).trigger('change');
            });
        })
    </script>
</div>