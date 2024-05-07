<footer class="site-footer bg-slate-50 py-6">
    <div class="container md:flex md:flex-row md:items-stretch md:justify-between">
        @if (is_active_sidebar( 'sidebar-footer' ))
            <div class="footer-widgets">
                @php(dynamic_sidebar('sidebar-footer'))
            </div>
        @endif
        @if (has_nav_menu('footer_navigation'))
            <nav class="nav-footer" aria-label="{{ wp_get_nav_menu_name('footer_navigation') }}">
                {!! wp_nav_menu(['theme_location' => 'footer_navigation', 'menu_class' => 'footer nav', 'echo' => false]) !!}
            </nav>
        @endif
    </div>
</footer>
