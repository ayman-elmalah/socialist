<div class="main-left-sidebar no-margin">
  <div class="user-data full-width">
    <div class="user-profile">
      <div class="username-dt">
        <div class="usr-pic">
          <img src="{{ asset('images/avatar.png') }}" alt="{{ Auth::user()->name }}">
        </div>
      </div><!--username-dt end-->
      <div class="user-specs">
        <h3>{{ Auth::user()->name }}</h3>
      </div>
    </div><!--user-profile end-->
    <ul class="user-fw-status">
      <li>
        <h4>Posts</h4>
        <span>{{ \App\Post::where('user_id', Auth::user()->id)->count() }}</span>
      </li>
      <li>
        <h4>Views</h4>
        <span>{{ Auth::user()->views }}</span>
      </li>
    </ul>
  </div>

  <div class="tags-sec full-width">
    <ul>
      <li><a href="#" title="">Help Center</a></li>
      <li><a href="#" title="">About</a></li>
      <li><a href="#" title="">Privacy Policy</a></li>
      <li><a href="#" title="">Community Guidelines</a></li>
      <li><a href="#" title="">Cookies Policy</a></li>
      <li><a href="#" title="">Career</a></li>
      <li><a href="#" title="">Language</a></li>
      <li><a href="#" title="">Copyright Policy</a></li>
    </ul>
    <div class="cp-sec">
      <p><img src="{{ asset('images/logo.png') }}" alt="">Copyright {{ date('Y') }} <a href="https://elmalah.000webhostapp.com/" target="_blank">Ayman Elmalah</a></p>
    </div>
  </div>
</div>
