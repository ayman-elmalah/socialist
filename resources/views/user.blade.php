@extends('layouts.app')

@section('title', $user->name)

@section('css')
<style media="screen">
  .search-form{position: relative;}
  .search-form .search-btn{position: absolute;top:0;right:0;}
  .infinite-loading-container{display:block;margin:50px auto;width: 100%;}
</style>
@endsection

@section('full-page-content')
<section class="cover-sec">
			<img src="{{ asset('images/background.jpg') }}" alt="Background">
</section>
<div class="main-section">
  <div class="container">
    <div class="main-section-data">
      <div class="row">
        <div class="col-lg-4">
          <div class="main-left-sidebar">
            <div class="user_profile">
              <div class="user-pro-img">
                <img src="{{ asset('images/avatar.png') }}" alt="">
              </div><!--user-pro-img end-->
              <div class="user_pro_status">
                <ul class="flw-hr">
                  <li><a href="{{ route('room.show', ['room' => null, 'user_id' => $user->id]) }}" title="" class="flww"><i class="fa fa-envelope fa-fw"></i> Message</a></li>
                </ul>
                <ul class="flw-status">
                  <li>
                    <span>Views</span>
                    <b>{{ $user->views }}</b>
                  </li>
                  <li>
                    <span>Posts</span>
                    <b>{{ $user->posts_count }}</b>
                  </li>
                </ul>
              </div><!--user_pro_status end-->
            </div><!--user_profile end-->
          </div><!--main-left-sidebar end-->
        </div>
        <div class="col-lg-8">
          <div class="main-ws-sec">
            <div class="user-tab-sec">
              <h3>{{ $user->name }}</h3>
              <div class="star-descp">
                <span>Joined {{ human_date($user->created_at) }}</span>
              </div><!--star-descp end-->
            </div><!--user-tab-sec end-->
            <div class="posts-section">
              <div class="post-bar" v-for="post in posts">
                <div class="post_topbar">
                  <div class="usy-dt">
                    <a :href="post.user_url"><img src="{{ asset('images/avatar.png') }}" :alt="post.user.name"></a>
                    <div class="usy-name">
                      <h3><a :href="post.user_url">@{{ post.user.name }}</a></h3>
                      <span>@{{ post.created_at }}</span>
                    </div>
                  </div>
                </div>
                <div class="job_descp">
                  <nl2br tag="p" :text="post.details"></nl2br>
                  <a class="float-right" :href="post.url" title="">view more</a>
                </div>
              </div>
              <infinite-loading @distance="1" @infinite="infiniteHandler" force-use-infinite-wrapper="true"></infinite-loading>
            </div>
          </div><!--main-ws-sec end-->
        </div>
      </div>
    </div><!-- main-section-data end-->
  </div>
</div>
@endsection

@section('js')
<script>
  const app = new Vue({
    el : '#app',
    data : {
      posts: [],
      page: 1,
      user_id : '{{ $user->id }}'
    },
    mounted() {

    },
    methods : {
      infiniteHandler($state) {
        let vm = this;
        axios.get('/users/' + this.user_id + '/posts', {
              params: {
                  page: this.page,
              },
          }).then(response => {
          if (response.data.posts.data.length > 0) {
            $.each(response.data.posts.data, function(key, value) {
                vm.posts.push(value);
            });
            $state.loaded();
          } else {
            $state.complete();
          }
          this.page = this.page + 1;
        });
      },
    }
  });
</script>
@endsection
