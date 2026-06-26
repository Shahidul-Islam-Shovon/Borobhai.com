{{--
    resources/views/partials/inner-navbar.blade.php
    সব inner page এ @include('partials.inner-navbar') দিয়ে use করুন
--}}
@php $authUser = Auth::user(); @endphp

<nav style="background:#fff;box-shadow:0 2px 4px rgba(0,0,0,.08);padding:.5rem 1rem;position:sticky;top:0;z-index:100;">
    <div class="container-fluid d-flex align-items-center gap-2">

        {{-- Brand --}}
        <a href="{{ route('home') }}" style="font-weight:800;color:#4f46e5;font-size:1.3rem;letter-spacing:-.5px;text-decoration:none;">
            Borobhai.online
        </a>

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