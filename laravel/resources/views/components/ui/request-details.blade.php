@props(['request'])
<div class="grid grid-cols-1 md:grid-cols-3">
    <div>
        Pemohon
        <x-ui.user-list-item :user="$request->user" />
        {{ $request->T30_created_at }}
    </div>
    @if ($request->supportBy)
        <div>
            Disokong Oleh
            <x-ui.user-list-item :user="$request->supportBy" />
            {{ $request->T30_support_at }}
        </div>
    @else
        <div><x-ui.status-pill :status="$request->T30_support_status"></x-ui.status-pill></div>
    @endif
    @if ($request->approveBy)
        <div>
            Disahkan Oleh
            <x-ui.user-list-item :user="$request->approveBy" />
            {{ $request->T30_approve_at }}
        </div>
    @else
        <div><x-ui.status-pill :status="$request->T30_support_status"></x-ui.status-pill></div>
    @endif
</div>