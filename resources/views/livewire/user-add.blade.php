<div class="p-2">
    <x-jet-button wire:click="showAddModal">{{ __('+ Add New User') }}</x-jet-button>
    @if (Session('success'))
    <ul class="mt-3 list-disc list-inside text-sm text-green-600">
        <li class="font-medium">{{Session('success')}}</li>
    </ul>
    @endif

    
    <!-- Add User Modal -->
    <x-jet-dialog-modal wire:model="isAddModalVisible" maxWidth="2xl">
        <x-slot name="title">
            {{ __('Add New User') }}
        </x-slot>

        <x-slot name="content">              
            <div class="col-span-6 sm:col-span-4 p-2">                
                <x-jet-label for="role" value="{{ __('User Role') }}" />
                <select wire:model.lazy="role" name="role" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full">
                    <option value="">Select</option>
                    <option value="user">User</option>
                    <option value="customer">Customer</option>
                </select>
                <x-jet-input-error for="role" class="mt-2" />
            </div>

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
            
            <div class="col-span-6 sm:col-span-4 p-2">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input type="email" class="mt-1 block w-full" wire:model.lazy="email" />
                <x-jet-input-error for="email" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4 p-2">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input type="password" class="mt-1 block w-full" wire:model.lazy="password" />
                <x-jet-input-error for="password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4 p-2">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input type="password" class="mt-1 block w-full" wire:model.lazy="password_confirmation" />
                <x-jet-input-error for="password_confirmation" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('isAddModalVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}  
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="storeUser" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>  