@props(['user'])
<div
    wire:click="$dispatch('loaduserprofile',{id:'{{ $user->id }}'})"
    class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200 rounded-lg p-2 flex items-center"
>
    <div class="shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600">
        {{ substr($user->name, 0, 1) }}
    </div>
    <div class="ml-4">
        <div class="text-sm font-semibold text-gray-900 dark:text-white">
            {{ $user->name }}
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400">
            {{ $user->groupName() }}
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400">
            {{ $user->email }}
        </div>
    </div>
</div>