<nav class="menu d-flex flex-column">

    <a href="{{ route('dashboard.my-schedule') }}" class="menu-item {{ request()->routeIs('dashboard.my-schedule') ? 'active' : '' }}">
        <i class="bi bi-calendar-event"></i>
        <span>My Schedule</span>
    </a>

    <a href="{{ route('dashboard.my-package') }}" class="menu-item {{ request()->routeIs('dashboard.my-package') ? 'active' : '' }}">
        <i class="bi bi-box"></i>
        <span>My Package</span>
    </a>

    <a href="#" class="menu-item disabled">
        <i class="bi bi-file-earmark-text"></i>
        <span>Submission</span>
    </a>

    <a href="#" class="menu-item disabled">
        <i class="bi bi-building"></i>
        <span>Hotel Reservation</span>
    </a>

</nav>
