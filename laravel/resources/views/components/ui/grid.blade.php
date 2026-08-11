@props(['min'=>'1','max'=>'1'])
<div class="grid grid-cols-{{$min}} md:grid-cols-{{$max}} gap-4">
    {{ $slot }}
</div>