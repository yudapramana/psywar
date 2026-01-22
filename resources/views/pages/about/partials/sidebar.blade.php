<div class="about-sidebar-card">
    <h5 class="about-sidebar-title">About SYMCARD</h5>

    <ul class="about-sidebar-nav">
        <li>
            <a href="{{ route('about.overview') }}" class="{{ request()->routeIs('about.overview') ? 'active' : '' }}">
                Overview
            </a>
        </li>

        <li>
            <a href="{{ route('about.galleries') }}" class="{{ request()->routeIs('about.galleries') ? 'active' : '' }}">
                Gallery
            </a>
        </li>

        <li>
            <a href="{{ route('about.board-members') }}" class="{{ request()->routeIs('about.board-members') ? 'active' : '' }}">
                Boards Member
            </a>
        </li>

        {{-- Aktifkan jika sudah ada --}}



        {{-- <li>
            <a href="{{ route('about.faculty') }}" class="{{ request()->routeIs('about.faculty') ? 'active' : '' }}">
                Faculty
            </a>
        </li> --}}

    </ul>
</div>
