{{-- ================= TOPBAR ISICAM STYLE ================= --}}
<div class="topbar-iscam fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

        {{-- LOGO --}}
        <div class="topbar-logo d-flex align-items-center">
            <img src="{{ asset('projects/assets/img/symcardlogolong.png') }}" alt="SYMCARD 2026">
        </div>

        {{-- INFO (DESKTOP ONLY) --}}
        <div class="topbar-info d-none d-lg-flex align-items-center">

            <div class="info-item">
                <span class="info-icon">
                    <i class="bi bi-calendar-event"></i>
                </span>
                <div class="info-text">
                    <small>Pre-Congress Workshop</small>
                    <strong>June 05, 2026</strong>
                </div>
            </div>

            <div class="info-divider"></div>

            <div class="info-item">
                <span class="info-icon">
                    <i class="bi bi-calendar-check"></i>
                </span>
                <div class="info-text">
                    <small>Save the date</small>
                    <strong>06 – 08 June, 2026</strong>
                </div>
            </div>

            <div class="info-divider"></div>

            <div class="info-item">
                <span class="info-icon">
                    <i class="bi bi-geo-alt"></i>
                </span>
                <div class="info-text">
                    <strong>ZHM Premiere Hotel</strong>
                </div>
            </div>

        </div>


        {{-- MOBILE TOGGLE (TOP RIGHT) --}}
        <i class="mobile-nav-toggle bi bi-list d-xl-none"></i>

    </div>
</div>



<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-center">




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
                            <a href="{{ route('program.meeting-at-glance') }}" class="{{ request()->routeIs('program.meeting-at-glance') ? 'active' : '' }}">
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

                {{-- E-SCIENCE STATION --}}
                <li class="dropdown">
                    <a href="#" class="{{ request()->is('e-science*') ? 'active' : '' }}">
                        <span>E-Science Station</span>
                        <i class="bi bi-chevron-down toggle-dropdown"></i>
                    </a>

                    <ul>
                        <li>
                            <a href="{{ route('escience.abstracts-cases-submission') }}" class="{{ request()->routeIs('escience.abstracts-cases-submission') ? 'active' : '' }}">
                                Abstracts & Cases Submission
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('login') }}" class="{{ request()->routeIs('escience.accepted-abstracts-cases') ? 'active' : '' }}">
                                Accepted Abstract & Case
                            </a>
                        </li>
                    </ul>
                </li>



                <li><a href="{{ route('venue') }}">Venue Information</a></li>
                {{-- <li class="dropdown"><a href="#"><span>More Pages</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
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
                </li> --}}
                <li><a href="{{ route('register') }}">Register Now</a></li>
                <li><a href="{{ route('login') }}">Login to account</a></li>

            </ul>

            {{-- MOBILE TOGGLE --}}
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>



    </div>
</header>
