<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        {{-- LOGO --}}
        <a href="index.html" class="logo d-flex align-items-center">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <img src="{{ asset('projects/assets/img/symcardlogo.png') }}" alt="">
            {{-- <h1 class="sitename">SYMCARD</h1> --}}
        </a>

        {{-- NAV --}}
        <nav id="navmenu" class="navmenu">
            <ul>

                {{-- HOME --}}
                <li>
                    <a href="{{ route('landing') }}" class="{{ request()->routeIs('landing') ? 'active' : '' }}">
                        Home
                    </a>
                </li>

                {{-- ABOUT --}}
                <li class="dropdown">
                    <a href="#" class="{{ request()->is('about*') ? 'active' : '' }}">
                        <span>About</span>
                        <i class="bi bi-chevron-down toggle-dropdown"></i>
                    </a>

                    <ul>
                        <li>
                            <a href="{{ route('about.overview') }}" class="{{ request()->routeIs('about.overview') ? 'active' : '' }}">
                                Overview
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('about.galleries') }}" class="{{ request()->routeIs('about.galleries') ? 'active' : '' }}">
                                Galleries
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('about.board-members') }}" class="{{ request()->routeIs('about.board-members') ? 'active' : '' }}">
                                Board Members
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- PROGRAM --}}
                <li class="dropdown">
                    <a href="#" class="{{ request()->is('program*') ? 'active' : '' }}">
                        <span>Program</span>
                        <i class="bi bi-chevron-down toggle-dropdown"></i>
                    </a>

                    <ul>
                        <li>
                            <a href="{{ route('program.full-program') }}" class="{{ request()->routeIs('program.meeting-at-glance') ? 'active' : '' }}">
                                Meeting at Glance
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('program.resources') }}" class="{{ request()->routeIs('program.resources') ? 'active' : '' }}">
                                Program Resources
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- <li><a href="schedule.html">Schedule</a></li>
                <li><a href="speakers.html">Speakers</a></li> --}}
                <li><a href="{{ route('venue') }}">Venue Information</a></li>
                <li class="dropdown"><a href="#"><span>More Pages</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="speaker-details.html">Speaker Details</a></li>
                        <li><a href="tickets.html">Tickets</a></li>
                        <li><a href="buy-tickets.html">Buy Tickets</a></li>
                        <li><a href="gallery.html">Gallery</a></li>
                        <li><a href="terms.html">Terms</a></li>
                        <li><a href="privacy.html">Privacy</a></li>
                        <li><a href="404.html">404</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="#">Dropdown 1</a></li>
                        <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="#">Deep Dropdown 1</a></li>
                                <li><a href="#">Deep Dropdown 2</a></li>
                                <li><a href="#">Deep Dropdown 3</a></li>
                                <li><a href="#">Deep Dropdown 4</a></li>
                                <li><a href="#">Deep Dropdown 5</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Dropdown 2</a></li>
                        <li><a href="#">Dropdown 3</a></li>
                        <li><a href="#">Dropdown 4</a></li>
                    </ul>
                </li>
                <li><a href="contact.html">Contact</a></li>

            </ul>

            {{-- MOBILE TOGGLE --}}
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        {{-- CTA --}}
        <a class="btn-getstarted" href="{{ route('register') }}">
            Register
        </a>

    </div>
</header>
