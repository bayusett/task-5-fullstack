<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ $title === 'Articles' ? '' : 'collapsed' }}" href="/articles">
                <i class="bi bi-newspaper"></i>
                <span>Articles</span>
            </a>
        </li><!-- End Articles Nav -->

        <li>
            <a class="nav-link {{ $title === 'Categories' ? '' : 'collapsed' }}" href="/categories">
                <i class="bi bi-grid"></i>
                <span>Categories</span>
            </a>
        </li><!-- End Categories Nav -->

        <li>
            <a class="nav-link {{ $title === 'Users' ? '' : 'collapsed' }}" href="/users">
                <i class="bi bi-people"></i>
                <span>Users</span>
            </a>
        </li><!-- End Users Nav -->


</aside><!-- End Sidebar-->
