<?php

use Livewire\Volt\Component;
use App\Models\Request;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\WithPagination;

new class extends Component {
    use withPagination;
    public string $title = "Senarai Permohonan";
    public function mount()
    {
        if(!auth()->user()->canAny(['create:requests','view:requests','view-any:requests','update:requests','support:requests','approve:requests']))
        {
            abort(403,"Tiada kebenaran untuk mengakses halaman ini");
        }
    }
    public function requests()
    {
        return Request::paginate(10);
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $this->title ?? __('Aset') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <x-ui.module-nav-group>
                </x-ui.module-nav-group>
            </div>
        </div>
    </div>
</div>
