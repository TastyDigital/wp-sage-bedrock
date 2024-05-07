<form role="search" method="get" class="search-form" action="{{ home_url('/') }}">
  <label for="s" class="sr-only">
      {{ _x('Search for:', 'label', 'sage') }}
  </label>
  <div class="flex">
    <input
      type="search"
      placeholder="{!! esc_attr_x('Search &hellip;', 'placeholder', 'sage') !!}"
      value="{{ get_search_query() }}"
      name="s"
      id="s"
    >
    <button class="py-2 px-4">{{ _x('Search', 'submit button', 'sage') }}</button>
  </div>
</form>
