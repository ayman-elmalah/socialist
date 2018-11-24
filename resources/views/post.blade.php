@extends('layouts.app')

@section('title', 'Post Page')

@section('css')
<style media="screen">
  .comments-section .comment-box{
    border: 1px dashed #ccc;
    padding: 15px;
    color: #ccc;
    display: inline-flex;
    background-color: #fff;
    width: 100%;
  }
  .comments-section .avatar{
    width:50px;
    height:50px;
    float: left;
  }
  .comments-section .body{
    margin-left: 20px;
    width:100%;
  }
  .comments-section .body a{
    color: #66b34e;
    text-decoration: none;
  }
  .comments-section .body span{
    float:right;
  }
</style>
@endsection

@section('content')
<div class="row">
  <div class="col-lg-8 col-md-8 no-pd">
    <div class="main-ws-sec">
      <div class="posts-section">
        <div class="post-bar">
          <div class="post_topbar">
            <div class="usy-dt">
              <a href="{{ route('users.show', $post->user->id) }}"><img src="{{ asset('images/avatar.png') }}" alt="{{ $post->user->name }}"></a>
              <div class="usy-name">
                <h3><a href="{{ route('users.show', $post->user->id) }}">{{ $post->user->name }}</a></h3>
                <span>{{ human_date($post->created_at) }}</span>
              </div>
            </div>
          </div>
          <div class="job_descp">
            <p>{!! nl2br($post->details) !!}</p>
          </div>
        </div>
      </div>
    </div>
    <div class="main-ws-sec">
      <div class="form-group" style="position:relative;">
        <input class="form-control" type="text" v-model="comment.details" @keypress.enter="saveComment()" placeholder="You Comment !">
        <button class="btn btn-success" type="button" @click="saveComment()" style="position: absolute;top: 0;right: 1px;">Comment</button>
      </div>
      <div class="comments-section">
      <div class="comment-box" v-for="comment in comments">
        <img class="avatar" alt="avatar" src="{{ asset('images/avatar.png') }}">
        <div class="body">
          <a :href="comment.user.url">@{{ comment.user.name }}</a>
          <span> @{{ comment.created_at }}</span>
          <br><br>
          <nl2br tag="p" :text="comment.details"></nl2br>
        </div>
      </div>
    </div>
    </div>
  </div>
  <div class="d-none d-sm-none d-md-block col-lg-4 col-md-4 pd-left-none">
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
      comments: [],
      comment : {
        details : '',
      },
      comment_url : '{{ route('comments.store', $post->id) }}',
      post : {!! $post !!}
    },
    mounted() {
      this.listen();
      this.comments = {!! $post->comments !!}
    },
    methods : {
      saveComment() {
        if (this.comment.details.length > 0) {
          let vm = this;
          axios.post(this.comment_url, this.comment)
          .then(function (response) {
            if (response.data.status == 1) {
              vm.comments.unshift(response.data.comment);
              vm.comment.details = '';
            } else {
              alert(response.data.message);
            }
          })
          .catch(function (error) {
            console.log(error);
          });
        }
      },
      listen() {
        Echo.private('post.'+this.post.id+'.comment')
            .listen('NewComment', (comment) => {
              this.comments.unshift(comment.comment);
            });
      }
    }
  });
</script>
@endsection
