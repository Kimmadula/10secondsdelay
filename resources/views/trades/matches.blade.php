@extends('layouts.app')

@section('content')
<main style="padding:32px; max-width:1100px; margin:0 auto;">
    <h1 style="font-size:1.5rem; margin-bottom:1rem;">Matching Trades</h1>

    @if(session('success'))
        <div style="background:#def7ec; color:#03543f; padding:10px 12px; border-radius:6px; margin-bottom:16px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#fde8e8; color:#9b1c1c; padding:10px 12px; border-radius:6px; margin-bottom:16px;">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($noTradePosted) && $noTradePosted)
        <div style="background:#fef3c7; color:#92400e; padding:20px; border-radius:8px; text-align:center; margin-bottom:16px;">
            <div style="font-size:1.2rem; margin-bottom:8px;">📝 Post a Trade First</div>
            <div style="margin-bottom:16px;">You need to post your own trade before you can see matching trades from other users.</div>
            <a href="{{ route('trades.create') }}" style="display:inline-block; padding:10px 20px; background:#2563eb; color:#fff; text-decoration:none; border-radius:6px; font-weight:600;">
                Post Your Trade
            </a>
        </div>
    @endif

    @if(isset($skillMismatch) && $skillMismatch)
        <div style="background:#fde8e8; color:#9b1c1c; padding:20px; border-radius:8px; text-align:center; margin-bottom:16px;">
            <div style="font-size:1.2rem; margin-bottom:8px;">⚠️ Skill Mismatch</div>
            <div style="margin-bottom:16px;">Your trade is offering a skill that doesn't match your registered skill. You can only offer your registered skill.</div>
            <a href="{{ route('trades.create') }}" style="display:inline-block; padding:10px 20px; background:#2563eb; color:#fff; text-decoration:none; border-radius:6px; font-weight:600;">
                Fix Your Trade
            </a>
        </div>
    @endif

    <div style="display:grid; gap:12px;">
        @forelse($trades as $t)
            <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:16px;">
                <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:12px;">
                    <div style="flex:1;">
                        <div style="font-weight:700; font-size:1.1rem;">{{ $t->use_username ? $t->user->username : ($t->user->firstname.' '.$t->user->lastname) }}</div>
                        <div style="color:#374151; margin-top:4px;">
                            <strong>Offering:</strong> {{ optional($t->offeringSkill)->name }} 
                            <span style="margin:0 8px;">→</span>
                            <strong>Looking for:</strong> {{ optional($t->lookingSkill)->name }}
                        </div>
                        <div style="color:#6b7280; font-size:0.9rem; margin-top:4px;">
                            📍 {{ $t->location ?: 'Any location' }} • {{ strtoupper($t->session_type) }} • {{ ucfirst($t->user->gender ?? 'Not specified') }}
                        </div>
                        @if($t->start_date && $t->end_date)
                            <div style="color:#6b7280; font-size:0.9rem;">
                                📅 {{ \Carbon\Carbon::parse($t->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($t->end_date)->format('M d, Y') }}
                            </div>
                        @endif
                        @if($t->available_from && $t->available_to)
                            <div style="color:#6b7280; font-size:0.9rem;">
                                🕒 {{ $t->available_from }} - {{ $t->available_to }}
                            </div>
                        @endif
                        @if($t->preferred_days && count($t->preferred_days) > 0)
                            <div style="color:#6b7280; font-size:0.9rem;">
                                📅 {{ implode(', ', $t->preferred_days) }}
                            </div>
                        @endif
                    </div>
                    <div style="text-align:right; margin-left:16px;">
                        <div style="background:#10b981; color:#fff; padding:4px 8px; border-radius:4px; font-size:0.8rem; font-weight:600;">
                            {{ $t->compatibility_score ?? 0 }}% Match
                        </div>
                        <div style="font-size:0.8rem; color:#6b7280; margin-top:4px;">
                            Flexible matching
                        </div>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('trades.request', $t->id) }}" style="display:flex; gap:8px; align-items:center;">
                    @csrf
                    <button type="submit" style="padding:8px 16px; background:#2563eb; color:#fff; border:none; border-radius:6px; cursor:pointer; white-space:nowrap;">
                        Request Trade
                    </button>
                </form>
            </div>
        @empty
            @if(!isset($noTradePosted) || !$noTradePosted)
                <div style="color:#6b7280; text-align:center; padding:32px;">
                    <div style="font-size:1.2rem; margin-bottom:8px;">No matches found</div>
                    <div>Try posting a trade or check back later for new opportunities.</div>
                </div>
            @endif
        @endforelse
    </div>
</main>
@endsection


