{{--
    resources/views/partials/mobile-nav.blade.php
    Facebook-style mobile bottom nav + slide drawer (≤767px এ active)
    দুই dashboard এ </body> এর ঠিক আগে একবার:  @include('partials.mobile-nav')
    Desktop এ পুরোপুরি hidden — তোমার d-none d-md-block sidebar অপরিবর্তিত থাকবে।
--}}
@php
    $mUser      = Auth::user();
    $mPending   = \App\Models\Friendship::where('receiver_id', Auth::id())
                    ->where('status', 'pending')
                    ->with('sender:id,name,role,profile_picture')
                    ->latest()->limit(10)->get();
    $mActive    = $activeUsers ?? collect();   // controller থেকেই আসছে
    $mSuggested = $suggested   ?? collect();   // controller থেকেই আসছে

    $mRole = match($mUser->role) {
        'alumni'  => ['Alumni',  'bi-mortarboard-fill', '#fef3c7', '#d97706'],
        'teacher' => ['Teacher', 'bi-easel2-fill',      '#f3e8ff', '#7c3aed'],
        default   => ['Student', 'bi-backpack-fill',    '#eef2ff', '#4f46e5'],
    };
@endphp

<style>
/* ===== Desktop এ পুরো block off ===== */
.bb-mobile-bottomnav,.bb-drawer,.bb-drawer-overlay{ display:none; }

@media (max-width:767.98px){
    body{ padding-bottom:62px; }   /* bottom nav যাতে content না ঢাকে */

    /* ---------- Bottom nav ---------- */
    .bb-mobile-bottomnav{
        display:flex; position:fixed; bottom:0; left:0; right:0; z-index:1040;
        height:60px; background:#fff; border-top:1px solid #e4e6eb;
        box-shadow:0 -2px 10px rgba(0,0,0,.05);
    }
    .bb-mbn-item{
        flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center;
        gap:2px; border:none; background:transparent; color:#65676b;
        font-size:10px; font-weight:600; text-decoration:none; cursor:pointer; position:relative;
    }
    .bb-mbn-item i{ font-size:21px; line-height:1; }
    .bb-mbn-item.active{ color:#4f46e5; }
    .bb-mbn-badge{
        position:absolute; top:6px; left:calc(50% + 7px);
        background:#ef4444; color:#fff; font-size:9px; font-weight:700;
        min-width:16px; height:16px; padding:0 4px; border-radius:9px;
        display:flex; align-items:center; justify-content:center; line-height:1;
    }

    /* ---------- Overlay ---------- */
    .bb-drawer-overlay{
        display:block; position:fixed; inset:0; z-index:1050;
        background:rgba(0,0,0,.45); opacity:0; visibility:hidden; transition:opacity .25s;
    }
    .bb-drawer-overlay.show{ opacity:1; visibility:visible; }

    /* ---------- Drawer ---------- */
    .bb-drawer{
        display:flex; flex-direction:column; position:fixed; top:0; left:0; bottom:0; z-index:1060;
        width:86%; max-width:340px; background:#fff;
        transform:translateX(-100%); transition:transform .28s cubic-bezier(.4,0,.2,1);
        box-shadow:2px 0 24px rgba(0,0,0,.18);
    }
    .bb-drawer.open{ transform:translateX(0); }

    .bb-dr-head{ padding:18px 16px; background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff; display:flex; align-items:center; gap:12px; }
    .bb-dr-head .av{ width:50px; height:50px; border-radius:50%; overflow:hidden; flex-shrink:0; background:rgba(255,255,255,.25); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:20px; }
    .bb-dr-head .av img{ width:100%; height:100%; object-fit:cover; }
    .bb-dr-head .nm{ font-weight:700; font-size:15px; line-height:1.2; }
    .bb-dr-head .rl{ font-size:11px; opacity:.9; margin-top:2px; }
    .bb-dr-close{ margin-left:auto; background:rgba(255,255,255,.2); border:none; color:#fff; width:30px; height:30px; border-radius:50%; font-size:14px; cursor:pointer; }

    .bb-dr-body{ flex:1; overflow-y:auto; -webkit-overflow-scrolling:touch; padding-bottom:14px; }
    .bb-dr-link{ display:flex; align-items:center; gap:13px; padding:13px 16px; color:#1e1f24; text-decoration:none; font-size:14px; font-weight:600; }
    .bb-dr-link:active{ background:#f3f4f8; }
    .bb-dr-link i{ font-size:18px; width:24px; text-align:center; }
    .bb-dr-link .cnt{ margin-left:auto; background:#4f46e5; color:#fff; font-size:10px; font-weight:700; min-width:18px; height:18px; padding:0 5px; border-radius:9px; display:flex; align-items:center; justify-content:center; }
    .bb-dr-sep{ height:8px; background:#f0f2f5; margin:6px 0; }
    .bb-dr-sect{ font-size:11px; font-weight:800; letter-spacing:.5px; text-transform:uppercase; color:#65676b; padding:12px 16px 6px; }

    .bb-dr-row{ display:flex; align-items:center; gap:11px; padding:9px 16px; }
    .bb-dr-av{ width:42px; height:42px; border-radius:50%; flex-shrink:0; overflow:hidden; background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:16px; position:relative; text-decoration:none; }
    .bb-dr-av img{ width:100%; height:100%; object-fit:cover; }
    .bb-dr-info{ flex:1; min-width:0; }
    .bb-dr-name{ font-size:13.5px; font-weight:700; color:#1e1f24; text-decoration:none; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; }
    .bb-dr-meta{ font-size:11px; color:#65676b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .bb-dr-dot{ position:absolute; bottom:0; right:0; width:11px; height:11px; border-radius:50%; border:2px solid #fff; }
    .bb-dr-btn{ width:34px; height:34px; border-radius:50%; border:1px solid #d0d3d9; background:#fff; color:#4f46e5; font-size:14px; cursor:pointer; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
    .bb-dr-acts .btn{ font-size:11px; }
    .bb-dr-empty{ font-size:12px; color:#9ca3af; text-align:center; padding:10px 16px; }
}
</style>

{{-- ==================== BOTTOM NAV ==================== --}}
<nav class="bb-mobile-bottomnav">
    <a href="{{ route('home') }}" class="bb-mbn-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="bi bi-house-door-fill"></i><span>Home</span>
    </a>
    <a href="{{ route('friends.index') }}" class="bb-mbn-item {{ request()->routeIs('friends.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i><span>Friends</span>
        @if($mPending->count() > 0)<span class="bb-mbn-badge">{{ $mPending->count() }}</span>@endif
    </a>
    <a href="{{ route('jobs.all') }}" class="bb-mbn-item {{ request()->routeIs('jobs.*') ? 'active' : '' }}">
        <i class="bi bi-briefcase-fill"></i><span>Jobs</span>
    </a>
    <a href="{{ route('saved.index') }}" class="bb-mbn-item {{ request()->routeIs('saved.*') ? 'active' : '' }}">
        <i class="bi bi-bookmark-heart-fill"></i><span>Saved</span>
    </a>
    <button type="button" id="bbMenuBtn" class="bb-mbn-item" onclick="bbOpenDrawer()">
        <i class="bi bi-list"></i><span>Menu</span>
    </button>
</nav>

{{-- ==================== DRAWER ==================== --}}
<div class="bb-drawer-overlay" id="bbDrawerOverlay" onclick="bbCloseDrawer()"></div>

<aside class="bb-drawer" id="bbDrawer">

    {{-- Header --}}
    <div class="bb-dr-head">
        <a href="{{ route('profile.show') }}" class="av" style="text-decoration:none;color:#fff;">
            @if($mUser->profile_picture)
                <img src="{{ asset('storage/'.$mUser->profile_picture) }}">
            @else
                {{ strtoupper(substr($mUser->name ?? 'U',0,1)) }}
            @endif
        </a>
        <div style="min-width:0;">
            <div class="nm">{{ \Illuminate\Support\Str::limit($mUser->name, 18) }}</div>
            <div class="rl"><i class="bi {{ $mRole[1] }}"></i> {{ $mRole[0] }}</div>
        </div>
        <button type="button" class="bb-dr-close" onclick="bbCloseDrawer()"><i class="bi bi-x-lg"></i></button>
    </div>

    <div class="bb-dr-body">

        {{-- Primary links --}}
        <a href="{{ route('profile.show') }}" class="bb-dr-link"><i class="bi bi-person-circle text-primary"></i> View Profile</a>
        <a href="{{ route('friends.index') }}" class="bb-dr-link"><i class="bi bi-people-fill text-info"></i> See Friend List</a>
        <a href="{{ route('saved.index') }}" class="bb-dr-link"><i class="bi bi-bookmark-heart-fill text-warning"></i> Saved</a>
        <a href="{{ route('jobs.myApplications') }}" class="bb-dr-link"><i class="bi bi-briefcase-fill text-primary"></i> Job History</a>
        <a href="{{ route('search.index') }}" class="bb-dr-link"><i class="bi bi-search text-primary"></i> Search People</a>

        {{-- Friend Requests --}}
        @if($mPending->count() > 0)
        <div class="bb-dr-sep"></div>
        <div class="bb-dr-sect">Friend Requests <span style="color:#4f46e5;">({{ $mPending->count() }})</span></div>
        @foreach($mPending as $req)
        <div class="bb-dr-row" id="bbd-freq-{{ $req->sender->id }}">
            <a href="{{ route('profile.view', $req->sender) }}" class="bb-dr-av">
                @if($req->sender->profile_picture)
                    <img src="{{ asset('storage/'.$req->sender->profile_picture) }}">
                @else {{ strtoupper(substr($req->sender->name,0,1)) }} @endif
            </a>
            <div class="bb-dr-info">
                <a href="{{ route('profile.view', $req->sender) }}" class="bb-dr-name">{{ $req->sender->name }}</a>
                <div class="bb-dr-acts d-flex gap-1 mt-1">
                    <button class="btn btn-primary btn-sm py-0 px-2" onclick="bbDrawerFriend('accept', {{ $req->sender->id }}, this)">Accept</button>
                    <button class="btn btn-light btn-sm py-0 px-2" onclick="bbDrawerFriend('decline', {{ $req->sender->id }}, this)">Decline</button>
                </div>
            </div>
        </div>
        @endforeach
        @endif

        {{-- Active Now --}}
        <div class="bb-dr-sep"></div>
        <div class="bb-dr-sect"><i class="bi bi-circle-fill text-success" style="font-size:8px;"></i> Active Now</div>
        @forelse($mActive as $au)
            @php
                $isOnline = $au->last_seen && $au->last_seen >= now()->subSeconds(40);
                $lastSeenText = \App\Http\Controllers\PostController::formatLastSeen($au->last_seen);
                $dotColor = $isOnline ? '#22c55e' : '#9ca3af';
            @endphp
            <div class="bb-dr-row" style="cursor:pointer;"
                 onclick="bbCloseDrawer(); openChatBox({{ $au->id }}, '{{ e($au->name) }}', '{{ $au->profile_picture ? asset('storage/'.$au->profile_picture) : '' }}', '{{ $lastSeenText }}', '{{ $isOnline ? '1' : '0' }}', '{{ $au->hashid }}')">
                <div class="bb-dr-av">
                    @if($au->profile_picture)
                        <img src="{{ asset('storage/'.$au->profile_picture) }}">
                    @else {{ strtoupper(substr($au->name,0,1)) }} @endif
                    <span class="bb-dr-dot" style="background:{{ $dotColor }};"></span>
                </div>
                <div class="bb-dr-info">
                    <span class="bb-dr-name">{{ $au->name }}</span>
                    <span class="bb-dr-meta">{{ $lastSeenText }}</span>
                </div>
                <i class="bi bi-chat-dots-fill" style="color:#4f46e5;"></i>
            </div>
        @empty
            <div class="bb-dr-empty">No friends active recently.</div>
        @endforelse

        {{-- Suggested People --}}
        <div class="bb-dr-sep"></div>
        <div class="bb-dr-sect">
            Suggested People
            <a href="{{ route('friends.suggested') }}" style="float:right;color:#4f46e5;text-transform:none;letter-spacing:0;font-weight:700;text-decoration:none;">See all</a>
        </div>
        @forelse($mSuggested as $su)
        <div class="bb-dr-row" id="bbd-suggest-{{ $su->id }}">
            <a href="{{ route('profile.view', hashid($su->id)) }}" class="bb-dr-av">
                @if($su->profile_picture)
                    <img src="{{ asset('storage/'.$su->profile_picture) }}">
                @else {{ strtoupper(substr($su->name,0,1)) }} @endif
            </a>
            <div class="bb-dr-info">
                <a href="{{ route('profile.view', hashid($su->id)) }}" class="bb-dr-name">{{ $su->name }}</a>
                @if(isset($su->mutual) && $su->mutual > 0)
                    <span class="bb-dr-meta" style="cursor:pointer;" onclick="bbCloseDrawer(); showMutualFriends({{ $su->id }}, '{{ e($su->name) }}')">
                        <i class="bi bi-people-fill text-primary"></i> {{ $su->mutual }} mutual
                    </span>
                @elseif($su->role === 'alumni' && $su->current_company)
                    <span class="bb-dr-meta"><i class="bi bi-briefcase-fill"></i> {{ \Illuminate\Support\Str::limit($su->current_company, 18) }}</span>
                @else
                    <span class="bb-dr-meta">{{ ucfirst($su->role) }}</span>
                @endif
            </div>
            @if($su->is_pending)
                <button type="button" class="bb-dr-btn" style="background:#4f46e5;border-color:#4f46e5;color:#fff;" onclick="bbDrawerSuggest('cancel', {{ $su->id }}, this)"><i class="bi bi-check-lg"></i></button>
            @else
                <button type="button" class="bb-dr-btn" onclick="bbDrawerSuggest('send', {{ $su->id }}, this)"><i class="bi bi-person-plus"></i></button>
            @endif
        </div>
        @empty
            <div class="bb-dr-empty">No suggestions right now.</div>
        @endforelse

        {{-- Logout --}}
        <div class="bb-dr-sep"></div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bb-dr-link" style="width:100%;border:none;background:transparent;color:#dc2626;text-align:left;">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</aside>

<script>
(function(){
    var drawer  = document.getElementById('bbDrawer');
    var overlay = document.getElementById('bbDrawerOverlay');
    var menuBtn = document.getElementById('bbMenuBtn');
    if(!drawer || !overlay) return;

    window.bbOpenDrawer = function(){
        drawer.classList.add('open'); overlay.classList.add('show');
        if(menuBtn) menuBtn.classList.add('active');
        document.body.style.overflow = 'hidden';
    };
    window.bbCloseDrawer = function(){
        drawer.classList.remove('open'); overlay.classList.remove('show');
        if(menuBtn) menuBtn.classList.remove('active');
        document.body.style.overflow = '';
    };
    document.addEventListener('keydown', function(e){ if(e.key === 'Escape') window.bbCloseDrawer(); });

    // Friend request: তোমার existing friendAction() backend handle করে, এখানে শুধু drawer row সরাই
    window.bbDrawerFriend = function(action, id, btn){
        if(typeof friendAction === 'function') friendAction(action, id, btn);
        var row = btn.closest('.bb-dr-row');
        if(row){ row.style.transition='opacity .25s'; row.style.opacity='0'; setTimeout(function(){ row.remove(); }, 260); }
    };
    // Suggested: existing suggestAction() backend handle করে, এখানে drawer button toggle করি
    window.bbDrawerSuggest = function(action, id, btn){
        if(typeof suggestAction === 'function') suggestAction(action, id, btn);
        if(action === 'send'){
            btn.innerHTML = '<i class="bi bi-check-lg"></i>';
            btn.style.background='#4f46e5'; btn.style.borderColor='#4f46e5'; btn.style.color='#fff';
            btn.setAttribute('onclick', "bbDrawerSuggest('cancel',"+id+",this)");
        } else {
            btn.innerHTML = '<i class="bi bi-person-plus"></i>';
            btn.style.background='#fff'; btn.style.borderColor='#d0d3d9'; btn.style.color='#4f46e5';
            btn.setAttribute('onclick', "bbDrawerSuggest('send',"+id+",this)");
        }
    };
})();
</script>