<div>
    <!-- Edit Modal -->
    <x-jet-dialog-modal wire:model="isEditModalVisible" maxWidth="2xl">
        <x-slot name="title">
            Edit Assigning Details
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
                    @foreach(\App\Models\Project::latest()->get() as $item)
                        <option value="{{$item->id}}">{{$item->project_name}}</option>
                    @endforeach
                </select>
            </div>

            
            <div wire:ignore wire:key="unique_identifier_second" class="col-span-6 sm:col-span-4 p-2">                
                <x-jet-label for="userId" value="{{ __('Select User') }}" />
                <select multiple id="userIdEdit" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    @foreach(\App\Models\User::where('role', 'user')->latest()->get() as $each)
                        <option value="{{$each->id}}">{{$each->first_name}}</option>
                    @endforeach
                </select>
            </div>

            <div wire:ignore wire:key="unique_identifier_third" class="col-span-6 sm:col-span-4 p-2">                
                <x-jet-label for="customerId" value="{{ __('Select Customer') }}" />
                <select multiple id="customerIdEdit" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    @foreach(\App\Models\User::where('role', 'customer')->latest()->get() as $each2)
                        <option value="{{$each2->id}}">{{$each2->first_name}}</option>
                    @endforeach
                </select>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('isEditModalVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}  
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                {{ __('Update') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Livewire Select2 JS -->
    <script>  
        //For Users Ids
        $('#userIdEdit').select2({
            placeholder: 'Select Users',
            allowClear: true
        });
        $('#userIdEdit').on('change', function(e){
            var data = $('#userIdEdit').select2("val");
            @this.set('userId', data);
        });

        //For Customers Ids
        $('#customerIdEdit').select2({
            placeholder: 'Select Customers',
            allowClear: true
        });    
        $('#customerIdEdit').on('change', function(e){
            var data = $('#customerIdEdit').select2("val");
            @this.set('customerId', data);
        });

        //Populate preselected users & customers on edit modal
        document.addEventListener('livewire:load', function(e){
            @this.on('populatePreselectedUsers', function(){
                let userIds = @this.get('userId');
                let customerIds = @this.get('customerId');

                $('#userIdEdit').val(userIds).trigger('change');
                $('#customerIdEdit').val(customerIds).trigger('change');
            });
        })
    </script>
</div>


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

        <x-jet-danger-button class="ml-2" wire:click="customDelete()" wire:loading.attr="disabled">
            {{ __('Delete') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-confirmation-modal>