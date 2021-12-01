<div class="p-2">
    <x-jet-button wire:click="showAddModal">{{ __('+ Add New Project') }}</x-jet-button>
    @if (Session('success'))
    <ul class="mt-3 list-disc list-inside text-sm text-green-600">
        <li class="font-medium">{{Session('success')}}</li>
    </ul>
    @endif

    
    <!-- Add User Modal -->
    <x-jet-dialog-modal wire:model="isAddModalVisible" maxWidth="2xl">
        <x-slot name="title">
            {{ __('Add New Project') }}
        </x-slot>

        <x-slot name="content">
            <div class="col-span-6 sm:col-span-4 p-2">                
                <x-jet-label for="projectName" value="{{ __('Project Name') }}" />
                <x-jet-input type="text" class="mt-1 block w-full" wire:model.lazy="projectName"/>
                <x-jet-input-error for="projectName" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4 p-2">                
                <x-jet-label for="projectDescription" value="{{ __('Project Description') }}" />
                <textarea wire:model.lazy="projectDescription" class="mt-1 block w-full" cols="30" rows="5"></textarea>
                <x-jet-input-error for="projectDescription" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('isAddModalVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}  
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="storeProject" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>  