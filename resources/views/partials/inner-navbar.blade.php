{{--
    resources/views/partials/inner-navbar.blade.php
    সব inner page এ @include('partials.inner-navbar') দিয়ে use করুন
--}}
@php $authUser = Auth::user(); @endphp

<style>
/* ===== Inner navbar live search ===== */
.ibb-search-wrap { position: relative; margin-left: 14px; }
.ibb-search-box {
    background:#f0f2f5; border-radius:50px; padding:.45rem 1rem;
    display:flex; align-items:center; width:280px; transition:width .2s ease;
}
.ibb-search-box:focus-within { width:330px; background:#e4e6eb; }
.ibb-search-box input {
    background:transparent; border:none; outline:none;
    margin-left:8px; font-size:.88rem; width:100%;
}
.ibb-search-dropdown {
    position:absolute; top:calc(100% + 8px); left:0; width:360px;
    background:#fff; border-radius:14px; z-index:9999; display:none;
    overflow:hidden; box-shadow:0 8px 32px rgba(16,24,40,.14); border:1px solid #eceef1;
}
.ibb-search-dropdown.show { display:block; animation:ibbIn .18s ease; }
@keyframes ibbIn { from{opacity:0;transform:translateY(-6px);} to{opacity:1;transform:translateY(0);} }
.ibb-sd-label { font-size:11px; font-weight:700; color:#6b7280; letter-spacing:.5px; text-transform:uppercase; padding:12px 14px 6px; }
.ibb-sd-item { display:flex; align-items:center; gap:11px; padding:9px 14px; cursor:pointer; transition:background .12s; text-decoration:none; }
.ibb-sd-item:hover, .ibb-sd-item.active { background:#f3f4f8; }
.ibb-sd-avatar { width:42px; height:42px; border-radius:50%; flex-shrink:0; overflow:hidden; background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:17px; }
.ibb-sd-avatar img { width:100%; height:100%; object-fit:cover; }
.ibb-sd-info { flex-grow:1; min-width:0; }
.ibb-sd-name { font-size:.9rem; font-weight:700; color:#1e1f24; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.ibb-sd-sub { font-size:.78rem; color:#6b7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:1px; }
.ibb-sd-topic { font-size:.74rem; color:#4f46e5; margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.ibb-sd-rolechip { font-size:9.5px; font-weight:700; padding:1px 7px; border-radius:12px; flex-shrink:0; }
.ibb-sd-student { background:#eef2ff; color:#4f46e5; }
.ibb-sd-alumni  { background:#fef3c7; color:#d97706; }
.ibb-sd-teacher { background:#f3e8ff; color:#7c3aed; }
.ibb-sd-footer { border-top:1px solid #eceef1; padding:10px 14px; font-size:.86rem; font-weight:700; color:#4f46e5; text-align:center; text-decoration:none; display:block; transition:background .12s; }
.ibb-sd-footer:hover { background:#f3f4f8; }
.ibb-sd-spinner { text-align:center; padding:20px; color:#6b7280; font-size:.88rem; }
.ibb-sd-empty { text-align:center; padding:20px 14px; color:#9ca3af; font-size:.86rem; }
@media (max-width:767px){
    /* ভিতরের পেজে horizontal scrollbar fix */
    html, body{ overflow-x:hidden; }

    /* search box কে fixed width না দিয়ে বাকি জায়গা fill করাই → navbar আর overflow করে না */
    .ibb-search-wrap{ flex:1 1 auto; min-width:0; margin-left:10px; }
    .ibb-search-box,
    .ibb-search-box:focus-within{ width:100%; }

    /* dropdown কে viewport-এ fix করে দিলাম, তাই কখনো ডানে/বামে overflow করবে না */
    .ibb-search-dropdown{
        position:fixed; top:56px; left:10px; right:10px; width:auto;
    }
}
</style>

<nav style="background:#fff;box-shadow:0 2px 4px rgba(0,0,0,.08);padding:.5rem 1rem;position:sticky;top:0;z-index:100;">
    <div class="container-fluid d-flex align-items-center gap-2">

        {{-- Brand --}}
        <a href="{{ route('home') }}" style="font-weight:800;color:#4f46e5;font-size:1.3rem;letter-spacing:-.5px;text-decoration:none;">
            Borobhai.online
        </a>

        {{-- 🆕 Live search box (ফিডের মতো) --}}
        <div class="ibb-search-wrap">
            <div class="ibb-search-box">
                <i class="bi bi-search" style="color:#6b7280;font-size:.9rem;"></i>
                <input type="text" id="ibbLiveSearch" placeholder="Search Borobhai..." autocomplete="off">
            </div>
            <div class="ibb-search-dropdown" id="ibbSearchDropdown"></div>
        </div>

        {{-- Right side --}}
        <div class="d-flex align-items-center gap-2 ms-auto">

            {{-- Profile pic + name + role badge --}}
            <div class="d-flex align-items-center gap-2">
                {{-- Avatar --}}
                <a href="{{ route('profile.show') }}"
                   style="width:36px;height:36px;border-radius:50%;overflow:hidden;flex-shrink:0;background:linear-gradient(135deg,#4f46e5,#7c73f0);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;text-decoration:none;">
                    @if($authUser->profile_picture)
                        <img src="{{ asset('storage/'.$authUser->profile_picture) }}"
                             style="width:100%;height:100%;object-fit:cover;">
                    @else
                        {{ strtoupper(substr($authUser->name ?? 'U', 0, 1)) }}
                    @endif
                </a>

                {{-- Name + role --}}
                <div class="d-none d-sm-flex flex-column" style="line-height:1.2;">
                    <a href="{{ route('profile.show') }}"
                       style="font-size:13px;font-weight:700;color:#1e1f24;text-decoration:none;white-space:nowrap;">
                        {{ $authUser->name }}
                    </a>
                    @php
                        $roleLabel = match($authUser->role) {
                            'alumni'  => ['Alumni',  'bi-mortarboard-fill',  'background:#fef3c7;color:#d97706;'],
                            'teacher' => ['Teacher', 'bi-easel2-fill',       'background:#f3e8ff;color:#7c3aed;'],
                            default   => ['Student', 'bi-backpack-fill',      'background:#eef2ff;color:#4f46e5;'],
                        };
                    @endphp
                    <span style="font-size:9.5px;font-weight:700;padding:1px 7px;border-radius:12px;width:fit-content;{{ $roleLabel[2] }}display:inline-flex;align-items:center;gap:3px;">
                        <i class="bi {{ $roleLabel[1] }}" style="font-size:8px;"></i>
                        {{ $roleLabel[0] }}
                    </span>
                </div>
            </div>

            {{-- Home button --}}
            <a href="{{ route('home') }}"
               style="width:38px;height:38px;border-radius:50%;background:#e4e6eb;display:flex;align-items:center;justify-content:center;color:#050505;text-decoration:none;font-size:1.1rem;"
               title="Home">
                <i class="bi bi-house-door-fill"></i>
            </a>

            {{-- Dropdown --}}
            <div class="dropdown">
                <button style="width:38px;height:38px;border-radius:50%;background:#e4e6eb;border:none;display:flex;align-items:center;justify-content:center;font-size:1.1rem;cursor:pointer;"
                        data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                    <li>
                        <a href="{{ route('profile.show') }}" class="dropdown-item py-2">
                            <i class="bi bi-person-circle me-2 text-primary"></i> View Profile
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('friends.index') }}" class="dropdown-item py-2">
                            <i class="bi bi-people-fill me-2 text-info"></i> Friends
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('saved.index') }}" class="dropdown-item py-2">
                            <i class="bi bi-bookmark-heart-fill me-2 text-warning"></i> Saved
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('jobs.myApplications') }}" class="dropdown-item py-2">
                            <i class="bi bi-briefcase-fill me-2 text-primary"></i> Job History
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger py-2">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>

{{-- ===== Inner navbar live search JS (ফিডের SECTION 21 এর মতো) ===== --}}
<script>
(function () {
    var input    = document.getElementById('ibbLiveSearch');
    var dropdown = document.getElementById('ibbSearchDropdown');
    if (!input || !dropdown) return;

    var timer = null, activeIdx = -1;

    input.addEventListener('focus', function () {
        if (this.value.trim().length < 2) showRecent();
    });

    input.addEventListener('input', function () {
        clearTimeout(timer);
        var q = this.value.trim();
        if (q.length < 2) { showRecent(); return; }
        dropdown.innerHTML = '<div class="ibb-sd-spinner"><i class="bi bi-search me-1"></i> Searching...</div>';
        dropdown.classList.add('show');
        timer = setTimeout(function () { doSearch(q); }, 320);
    });

    input.addEventListener('keydown', function (e) {
        var items = dropdown.querySelectorAll('.ibb-sd-item');
        if (e.key === 'ArrowDown')      { e.preventDefault(); activeIdx = Math.min(activeIdx + 1, items.length - 1); hl(items); }
        else if (e.key === 'ArrowUp')   { e.preventDefault(); activeIdx = Math.max(activeIdx - 1, 0); hl(items); }
        else if (e.key === 'Enter')     { e.preventDefault(); if (activeIdx >= 0 && items[activeIdx]) items[activeIdx].click(); else go(); }
        else if (e.key === 'Escape')    { close(); input.blur(); }
    });

    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) close();
    });

    function close()   { dropdown.classList.remove('show'); dropdown.innerHTML = ''; activeIdx = -1; }
    function go()      { var q = input.value.trim(); if (q) window.location.href = '/search?q=' + encodeURIComponent(q); }
    function hl(items) { items.forEach(function (el, i) { el.classList.toggle('active', i === activeIdx); }); }

    function showRecent() {
        fetch('/search/recent', { headers: { 'Accept': 'application/json' } })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (!d.searches || !d.searches.length) { close(); return; }
            var html = '<div class="ibb-sd-label" style="display:flex;align-items:center;justify-content:space-between;"><span>Recent Searches</span>'
                     + '<button onclick="ibbClearAllRecent(event)" style="font-size:11px;font-weight:600;color:#4f46e5;border:none;background:transparent;cursor:pointer;padding:0;">Clear all</button></div>';
            d.searches.forEach(function (s) {
                html += '<div class="ibb-sd-item" onclick="window.location.href=\'/search?q=' + encodeURIComponent(s.query) + '\'">'
                      + '<div class="ibb-sd-avatar" style="background:#f3f4f8;color:#6b7280;font-size:16px;"><i class="bi bi-clock-history"></i></div>'
                      + '<div class="ibb-sd-info"><div class="ibb-sd-name">' + esc(s.query) + '</div></div>'
                      + '<button onclick="ibbDeleteRecent(event,' + s.id + ')" style="border:none;background:transparent;color:#9ca3af;cursor:pointer;padding:2px 6px;border-radius:6px;" title="Remove"><i class="bi bi-x-lg" style="font-size:11px;"></i></button>'
                      + '</div>';
            });
            dropdown.innerHTML = html;
            dropdown.classList.add('show');
        })
        .catch(function () { close(); });
    }

    window.ibbDeleteRecent = function (e, id) {
        e.stopPropagation();
        fetch('/search/recent/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } })
        .then(function () { showRecent(); });
    };
    window.ibbClearAllRecent = function (e) {
        e.stopPropagation();
        fetch('/search/recent', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } })
        .then(function () { close(); });
    };

    function doSearch(q) {
        fetch('/search/live?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (r) { return r.json(); })
        .then(function (data) { render(data.results || [], q); })
        .catch(function () { dropdown.innerHTML = '<div class="ibb-sd-empty">Something went wrong.</div>'; });
    }

    function render(results, q) {
        activeIdx = -1;
        if (!results.length) { dropdown.innerHTML = '<div class="ibb-sd-empty"><i class="bi bi-search me-1"></i> No results for "' + esc(q) + '"</div>'; return; }
        var html = '<div class="ibb-sd-label">People</div>';
        results.forEach(function (r) {
            var av  = r.avatar ? '<img src="' + esc(r.avatar) + '" alt="">' : esc(r.initial || 'U');
            var top = r.topic ? '<div class="ibb-sd-topic"><i class="bi bi-journal-text"></i> ' + esc(r.topic.substring(0, 55)) + (r.topic.length > 55 ? '…' : '') + '</div>' : '';
            html += '<a href="/search?q=' + encodeURIComponent(r.name) + '" class="ibb-sd-item">'
                  + '<div class="ibb-sd-avatar">' + av + '</div>'
                  + '<div class="ibb-sd-info"><div class="ibb-sd-name">' + hlq(esc(r.name), q) + '</div>'
                  + (r.sub ? '<div class="ibb-sd-sub">' + esc(r.sub) + '</div>' : '') + top + '</div>'
                  + '<span class="ibb-sd-rolechip ibb-sd-' + r.role + '">' + esc(r.role_label || r.role) + '</span>'
                  + '</a>';
        });
        html += '<a href="/search?q=' + encodeURIComponent(q) + '" class="ibb-sd-footer"><i class="bi bi-search me-1"></i> See all results for "' + esc(q) + '"</a>';
        dropdown.innerHTML = html;
        dropdown.classList.add('show');
    }

    function hlq(text, q) {
        if (!q) return text;
        return text.replace(new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi'), '<mark style="background:#dbeafe;padding:0 2px;border-radius:2px;">$1</mark>');
    }
    function esc(s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
})();
</script>