@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
<div class="row">
  <div class="d-none d-sm-none d-md-block col-lg-3 col-md-2 pd-left-none">
    @include('layouts.partials.card')
  </div>
  <div class="col-lg-6 col-md-8 no-pd">
    <div class="main-ws-sec">
      <div class="post-topbar">
        <div class="details">
          <textarea v-model="post.details" class="form-control" placeholder="What you are thinking about !"></textarea>
        </div>
        <div class="post-st">
          <ul>
            <li><button class="post" @click="savePost">Post</a></li>
          </ul>
        </div><!--post-st end-->
      </div><!--post-topbar end-->
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
    </div>
  </div>
  <div class="d-none d-sm-none d-md-block col-lg-3 col-md-2 pd-left-none">
    <div class="right-sidebar">
      <div class="widget suggestions full-width">
        <div class="sd-title">
          <h3>Most Viewed People</h3>
        </div><!--sd-title end-->
        <div class="suggestions-list">
          @forelse($most_viewed_users as $most_viewed_user)
            <div class="suggestion-usd">
              <img src="{{ asset('images/avatar.png') }}" alt="{{ $most_viewed_user->name }}">
              <div class="sgt-text">
                <h4>
                  <a href="{{ route('users.show', $most_viewed_user->id) }}">
                    {{ $most_viewed_user->name }}
                  </a>
                </h4>
                <span>{{ $most_viewed_user->views }} view</span>
              </div>
            </div>
          @empty
          <div class="suggestion-usd">
            <div class="sgt-text">
              <h3>There is no users until now</h3>
            </div>
          </div>
          @endforelse
          <div class="view-more">
            <a href="{{ route('users.index') }}" title="">View All</a>
          </div>
        </div><!--suggestions-list end-->
      </div>
    </div><!--right-sidebar end-->



    <div class="right-sidebar">
      <div class="widget suggestions full-width">
        <div class="sd-title">
          <h3>Recently Joined Users</h3>
        </div><!--sd-title end-->
        <div class="suggestions-list">
          @forelse($recently_joined_users as $recently_joined_user)
            <div class="suggestion-usd">
              <img src="{{ asset('images/avatar.png') }}" alt="{{ $recently_joined_user->name }}">
              <div class="sgt-text">
                <h4>
                  <a href="{{ route('users.show', $recently_joined_user->id) }}">
                    {{ $recently_joined_user->name }}
                  </a>
                </h4>
                <span>Joined {{ $recently_joined_user->created_at->diffForHumans() }}</span>
              </div>
            </div>
          @empty
          <div class="suggestion-usd">
            <div class="sgt-text">
              <h3>There is no users until now</h3>
            </div>
          </div>
          @endforelse
          <div class="view-more">
            <a href="{{ route('users.index') }}" title="">View All</a>
          </div>
        </div><!--suggestions-list end-->
      </div>
    </div><!--right-sidebar end-->
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
      post : {
        details : '',
      }
    },
    mounted() {

    },
    methods : {
      infiniteHandler($state) {
        let vm = this;
        axios.get('/posts', {
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
      savePost() {
        let vm = this;
        axios.post('/posts/store', this.post)
        .then(function (response) {
          vm.$toaster.success('Posted Successfully.')
          vm.post.details = '';
        })
        .catch(function (error) {
          console.log(error);
        });
      }
    }
  });
</script>
@endsection
