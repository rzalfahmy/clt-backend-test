@if (session('status'))
    <div class="panel-soft border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="panel-soft border-rose-400/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
        <ul class="space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
