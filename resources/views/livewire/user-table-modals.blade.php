@if (Session('success'))
<ul class="mt-3 list-disc list-inside text-sm text-green-600">
    <li class="font-medium">{{Session('success')}}</li>
</ul>
@endif

<!-- Edit User Modal -->
<x-jet-dialog-modal wire:model="isEditModalVisible" maxWidth="2xl">
    <x-slot name="title">
        Edit User Info

    </x-slot>

    <x-slot name="content"> 
        <div class="col-span-6 sm:col-span-4 p-2">                
            <x-jet-label for="firstName" value="{{ __('First Name') }}" />
            <x-jet-input type="text" class="mt-1 block w-full" wire:model.lazy="firstName"/>
            <x-jet-input-error for="firstName" class="mt-2" />
        </div>
        
        <div class="col-span-6 sm:col-span-4 p-2">
            <x-jet-label for="lastName" value="{{ __('Last Name') }}" />
            <x-jet-input type="text" class="mt-1 block w-full" wire:model.lazy="lastName"/>
            <x-jet-input-error for="lastName" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('isEditModalVisible')" wire:loading.attr="disabled">
            {{ __('Cancel') }}  
        </x-jet-secondary-button>

        <x-jet-button class="ml-2" wire:click="updateUser" wire:loading.attr="disabled">
            {{ __('Update') }}
        </x-jet-button>
    </x-slot>
</x-jet-dialog-modal>