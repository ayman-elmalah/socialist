@extends('layouts.app')

@section('title', 'Users Page')

@section('css')
<style media="screen">
  .search-form{position: relative;}
  .search-form .search-btn{position: absolute;top:0;right:0;}
  .infinite-loading-container{display:block;margin:50px auto;width: 100%;}
</style>
@endsection

@section('content')

<section class="companies-info">
	<div class="container">
		<div class="company-title">
      @include('layouts.partials.flash-messages')

			<form class="search-form" action="{{ route('users.index') }}" method="get">
        <input type="text" name="name" value="{{ request()->name }}" class="form-control" placeholder="Search by name !!">
        <input type="submit" value="Search" class="btn btn-success search-btn">
      </form>
		</div><!--company-title end-->
		<div class="companies-list">
			<div class="row">
				<div class="col-lg-3 col-md-4 col-sm-6" v-for="user in users">
					<div class="company_profile_info">
						<div class="company-up-info">
							<img src="{{ asset('images/avatar.png') }}" :alt="user.name">
							<h3>@{{ user.name }}</h3>
							<h4>Joined @{{ user.created_at }}</h4>
							<ul>
								<li><a :href="user.profile_url" title="Show profile" class="follow">View Profile</a></li>
								<li><a :href="user.message_url" title="" class="message-us"><i class="fa fa-envelope"></i></a></li>
							</ul>
						</div>
					</div><!--company_profile_info end-->
				</div>
        <infinite-loading @distance="1" @infinite="infiniteHandler" force-use-infinite-wrapper="true"></infinite-loading>
      </div>
		</div><!--companies-list end-->
	</div>
</section>
@endsection

@section('js')
<script>
  const app = new Vue({
    el : '#app',
    data : {
      users: [],
      page: 1,
      search_url : '{{ route("users.get", ['name' => request()->name]) }}',
    },
    mounted() {

    },
    methods : {
      infiniteHandler($state) {
        let vm = this;
        axios.get(this.search_url, {
              params: {
                  page: this.page,
              },
          }).then(response => {
          if (response.data.users.data.length > 0) {
            $.each(response.data.users.data, function(key, value) {
                vm.users.push(value);
            });
            $state.loaded();
          } else {
            $state.complete();
          }
          this.page = this.page + 1;
        });
      }
    }
  });
</script>
@endsection
