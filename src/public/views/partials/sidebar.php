<nav class="sidebar">
        <div class="logo">
                <a href="/dashboard" class="logo-link">
                        <div class="logo-small">
                                <?php include 'public/resources/logo.svg'; ?>
                        </div>
                        Programmar
                </a>
        </div>

        <ul class="nav-links">
                <li><a href="/dashboard" class="<?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">Dashboard</a></li>
                <li><a href="/my-courses" class="<?= ($currentPage ?? '') === 'my_courses' ? 'active' : '' ?>">My Courses</a></li>
                <li><a href="/courses" class="<?= ($currentPage ?? '') === 'courses' ? 'active' : '' ?>">Courses</a></li>
                <li><a href="/settings" class="<?= ($currentPage ?? '') === 'settings' ? 'active' : '' ?>">Settings</a></li>
        </ul>

        <div class="sidebar-footer">
                <a href="/logout" class="logout-link">
                        Logout
                </a>
        </div>
</nav>
