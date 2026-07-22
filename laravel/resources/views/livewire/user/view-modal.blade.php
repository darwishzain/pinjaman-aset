<?php

use function Livewire\Volt\{state,with,on};
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use App\Models\User;
new class extends Component {
    public bool $showprofile = false;
    public string $title = '';
    public string $userid;
    public User $user;
    #[On('loaduserprofile')]
    public function loaduserprofile($id){
        $this->showprofile = true;
        $this->user = User::findOrFail($id);
        $this->userid = $this->user->id;
        $this->title = 'Profil Pengguna';
    }
}


?>

<div>
    @if($showprofile)
        @can('view:users')
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center">
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">
                <div class="w-full max-w-4xl rounded-xl bg-white shadow-xl">
                    <!-- Header -->
                    <div class="border-b p-6">
                        <h2 class="text-lg font-semibold">
                            {{ $title }}

                            <button wire:click="$set('showprofile', false)" class="float-right">
                                ✕
                            </button>
                        </h2>
                    </div>

                    <!-- Scrollable Body -->
                    <div class="max-h-[65vh] overflow-y-auto p-6">
                        {{ $this->user->name }}
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-2 border-t p-6">
                        <h2 class="text-lg font-semibold">
                            <button wire:click="$set('showprofile', false)" class="float-right">
                                Batal
                            </button>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    @endif
</div>
