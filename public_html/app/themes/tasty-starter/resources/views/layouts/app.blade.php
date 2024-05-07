<!doctype html>
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php(do_action('get_header'))
    @php(wp_head())
  </head>

  <body @php(body_class())>
    @php(wp_body_open())

    <div id="app">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ __('Skip to content') }}
      </a>

      @include('sections.header')
      <div class="container py-6 md:flex md:flex-row md:items-stretch grow">
        <main id="main" class="main md:grow md:w-3/4">
          @yield('content')
        </main>

        @hasSection('sidebar')
          <aside class="sidebar md:grow-0 md:w-1/4">
            @yield('sidebar')
          </aside>
        @endif
      </div>
      @include('sections.footer')
    </div>

    @php(do_action('get_footer'))
    @php(wp_footer())
  </body>
</html>
