<nav class="bottom-nav">
    <div class="bottom-nav-inner">
        <a href="/student" class="nav-btn {{ Request::is('student*') ? 'active' : 'inactive' }}">
            <div class="nav-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path
                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </div>
            <span class="nav-label">Student</span>
        </a>

        <a href="/driver" class="nav-btn {{ Request::is('driver*') ? 'active' : 'inactive' }}">
            <div class="nav-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 12C12 12 8 12 5 12C5 16 8 19 12 19" />
                    <path d="M12 12C12 12 16 12 19 12C19 16 16 19 12 19" />
                </svg>
            </div>
            <span class="nav-label">Driver</span>
        </a>


    </div>
</nav>
