<header>
	<div class="container">
		<div class="header-data">
			<div class="logo">
				<a href="{{ route('home') }}" title=""><img src="{{ asset('images/logo.png') }}" alt="Home"></a>
			</div><!--logo end-->
			<nav>
				<ul>
					<li><a href="{{ route('home') }}" title=""><i class="fa fa-home fa-fw navbar-icon"></i>Home</a></li>
					<li><a href="{{ route('users.index') }}" title=""><i class="fa fa-users fa-fw navbar-icon"></i>All users</a></li>
					<messages :user_id="{{ Auth::user()->id }}" :messages_url="'{{ route('messages.index') }}'"></messages>
					<notifications :user_id="{{ Auth::user()->id }}"></notifications>
				</ul>
			</nav><!--nav end-->
			<div class="menu-btn">
				<a href="javascript:void(0);" title=""><i class="fa fa-bars"></i></a>
			</div><!--menu-btn end-->
			<div class="user-account">
				<div class="user-info">
					<img src="{{ asset('images/avatar.png') }}" alt="">
					<a href="javascript:void(0);" title="User Name">{{ Auth::user()->name }}</a>
					<i class="la la-sort-down"></i>
				</div>
				<div class="user-account-settingss">
					<ul class="us-links">
						<li><a href="{{ route('profile.edit') }}" title="My Profile">My Profile</a></li>
						<li><a href="{{ route('users.show', Auth::user()->id) }}" title="My Posts">My Posts</a></li>
					</ul>
					<h3 class="tc"><a  class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></h3>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
        </div><!--user-account-settingss end-->
			</div>
		</div><!--header-data end-->
	</div>
</header>

@section('js')
<script>
  const app = new Vue({
    el : '#app',
  });
</script>
@endsection
