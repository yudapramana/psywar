<nav class="menu d-flex flex-column">

    {{-- MY SCHEDULE --}}
    <a href="{{ route('dashboard.my-schedule') }}" class="menu-item {{ request()->routeIs('dashboard.my-schedule') ? 'active' : '' }}">
        <i class="bi bi-calendar-event"></i>
        <span>My Schedule</span>
    </a>

    {{-- MY PACKAGE --}}
    <a href="{{ route('dashboard.my-package') }}" class="menu-item {{ request()->routeIs('dashboard.my-package') ? 'active' : '' }}">
        <i class="bi bi-box"></i>
        <span>My Package</span>
    </a>

    {{-- SUBMISSION --}}
    <a href="{{ route('dashboard.submission.index') }}" class="menu-item {{ request()->routeIs('dashboard.submission.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i>
        <span>Submission</span>
    </a>


</nav>
