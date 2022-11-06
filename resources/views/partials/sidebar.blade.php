<ul>
    @if (Auth::check() && Auth::user()->role == 'admin')
        <li class="nav-item">
            <a href="{{ url('dashboard72') }}" class="nav-link">
                <p>
                    Dashboard
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('agama72') }}" class="nav-link">
                <p>
                    Crud Agama
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('logout72') }}" class="nav-link">
                <p>
                    Logout
                </p>
            </a>
        </li>
    @else
        <li class="nav-item">
            <a href="{{ url('profile72') }}" class="nav-link">
                <p>
                    Dashboard
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('/changePassword72') }}" class="nav-link">
                <p>
                    Ganti Password
                </p>
            </a>
        </li>


        <li class="nav-item">
            <a href="{{ url('logout72') }}" class="nav-link">
                <p>
                    Logout
                </p>
            </a>
        </li>
    @endif
</ul>
