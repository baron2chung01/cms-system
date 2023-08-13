<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('clients.index') }}" class="nav-link {{ Request::is('clients*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Clients</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('renovateCases.index') }}" class="nav-link {{ Request::is('renovateCases*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Renovate Cases</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('blogs.index') }}" class="nav-link {{ Request::is('blogs*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Blogs</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('materials.index') }}" class="nav-link {{ Request::is('materials*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Materials</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('quotations.index') }}" class="nav-link {{ Request::is('quotations*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Quotations</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('qnas.index') }}" class="nav-link {{ Request::is('qnas*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Qnas</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('shops.index') }}" class="nav-link {{ Request::is('shops*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Shops</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('assets.index') }}" class="nav-link {{ Request::is('assets*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Assets</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('articles.index') }}" class="nav-link {{ Request::is('articles*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Articles</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('materialsCategories.index') }}" class="nav-link {{ Request::is('materialsCategories*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Materials Categories</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('blogCategories.index') }}" class="nav-link {{ Request::is('blogCategories*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Blog Categories</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('renovateCourses.index') }}" class="nav-link {{ Request::is('renovateCourses*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Renovate Courses</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('categories.index') }}" class="nav-link {{ Request::is('categories*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Categories</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('specialPromotions.index') }}" class="nav-link {{ Request::is('specialPromotions*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Special Promotions</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('qnaCategories.index') }}" class="nav-link {{ Request::is('qnaCategories*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Qna Categories</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('shopsReviews.index') }}" class="nav-link {{ Request::is('shopsReviews*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Shops Reviews</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('regions.index') }}" class="nav-link {{ Request::is('regions*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Regions</p>
    </a>
</li>
