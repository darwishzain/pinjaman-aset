<!-- 1 Column Data View-->
<div class="grid grid-cols-1 gap-4">
    <div>
        <div class="text-sm text-gray-500">Nama</div>
        <div class="font-medium">{{ $user->name }}</div>
    </div>
</div>
<!-- 2 Column Data View-->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <div class="text-sm text-gray-500">Nama</div>
        <div class="font-medium">{{ $user->name }}</div>
    </div>
    <div>
        <div class="text-sm text-gray-500">E-mel</div>
        <div class="font-medium">{{ $user->email }}</div>
    </div>
</div>
<!--Input: 1 column-->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block mb-1">Name</label>
        <input
            type="text"
            wire:model="name"
            class="w-full rounded border-gray-300 focus:ring-indigo-500"
        >
    </div>
</div>
<!--Input: 2 column on Web 1 on Mobile-->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block mb-1">Name</label>
        <input
            type="text"
            wire:model="name"
            class="w-full rounded border-gray-300 focus:ring-indigo-500"
        >
    </div>
    <div>
        <label class="block mb-1">Email</label>
        <input
            type="email"
            wire:model="email"
            class="w-full rounded border-gray-300 focus:ring-indigo-500"
        >
    </div>
</div>
<!--Table-->
<table class="w-full border border-collapse">
</table>