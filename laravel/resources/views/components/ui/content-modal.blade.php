@props([
    'title' => '',
])
<div class="fixed inset-0 bg-black/50 flex items-center justify-center">
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">
            <div class="w-full max-w-4xl rounded-xl bg-white shadow-xl">
                <!-- Header -->
                <div class="border-b p-6">
                    <h2 class="text-lg font-semibold">
                        {{ $title }}
                        <button type="button" wire:click="$set('activemodal', null)" class="float-right">
                            ✕
                        </button>
                    </h2>
                </div>
                <!-- Scrollable Body -->
                <div class="max-h-[65vh] overflow-y-auto p-6">
                    {{ $slot }}
                </div>
                <!-- Footer -->
                <div class="flex justify-end gap-2 border-t p-6">
                    <h2 class="text-lg font-semibold">
                        <button type="button" wire:click="$set('activemodal', null)" class="float-right">
                            Batal
                        </button>
                    </h2>
                </div>
            </div>
        </div>
    </div>