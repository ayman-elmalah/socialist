@extends('layouts.app')

@section('title', 'Room')

@section('css')
<style media="screen">
  main{padding-bottom:0 !important;}
  .typing{background-color: #fff;padding: 10px 20px;width: 100%;display: none;position: absolute;bottom:0;}
</style>
@endsection

@section('content')
<div class="messages-sec">
	<div class="row">
		<div class="col-lg-4 col-md-12 no-pdd">
			<div class="msgs-list">
				<div class="msg-title">
					<h3>Rooms</h3>
				</div><!--msg-title end-->
				<div class="messages-list">
					<ul>
            @if (isset($user) && $user != null)
            <li class="active">
              <a href="javascript:void(0);">
                <div class="usr-msg-details">
                <div class="usr-ms-img">
                  <img src="{{ asset('images/avatar.png') }}" alt="{{ $user->name }}">
                </div>
                <div class="usr-mg-info">
                  <h3>{{ $user->name }}</h3>
                </div><!--usr-mg-info end-->
                <span class="posted_time">{{ date('Y-m-d h:i:s') }}</span>
              </div><!--usr-msg-details end-->
              </a>
            </li>
            @endif
						<li :class="{'active':(room.id == room_id)}" v-for="room in rooms">
              <a :href="room.url">
                <div class="usr-msg-details">
								<div class="usr-ms-img">
									<img src="{{ asset('images/avatar.png') }}" :alt="room.other_member.user.name">
								</div>
								<div class="usr-mg-info">
									<h3>@{{ room.other_member.user.name }}</h3>
								</div><!--usr-mg-info end-->
								<span class="posted_time">@{{ room.latest_message.created_at }}</span>
								<span class="msg-notifc" v-if="room.notseen_messages_count > 0">@{{ room.notseen_messages_count }}</span>
							</div><!--usr-msg-details end-->
              </a>
						</li>
            <infinite-loading @distance="1" @infinite="infiniteRoomsHandler"></infinite-loading>
					</ul>
				</div><!--messages-list end-->
			</div><!--msgs-list end-->
		</div>
		<div class="col-lg-8 col-md-12 pd-right-none pd-left-none">
      <div class="message-bar-head">
        <div class="usr-msg-details">
          <div class="usr-ms-img">
            <img src="{{ asset('images/avatar.png') }}" alt="">
          </div>
          <div class="usr-mg-info">
            <h3 v-if="this.room != null">
              <a :href="this.room.user_url">
                @{{ this.room.other_member.user.name }}
              </a>
            </h3>
            @if (isset($user) && $user != null)
                <h3>
                  <a href="{{ route('users.show', $user->id) }}">
                    {{ $user->name }}
                  </a>
                </h3>
            @endif
          </div><!--usr-mg-info end-->
        </div>
      </div><!--message-bar-head end-->
      <div class="main-conversation-box">
        <ul v-if="this.room_id != ''" v-chat-scroll="{always: false, smooth: true}" class="v-chat-scroll">
          <infinite-loading @distance="1" @infinite="infiniteMessagesHandler" direction="top"></infinite-loading>
          <li class="" v-for="message in messages">
            <div class="main-message-box st3" v-if="message.user_id == auth_id">
              <div class="message-dt st3">
                <div class="message-inner-dt">
                  <p>@{{ message.details }}</p>
                </div><!--message-inner-dt end-->
                <span>@{{ message.created_at }}</span>
              </div><!--message-dt end-->
            </div>
            <div class="main-message-box ta-right" v-else>
              <div class="message-dt">
                <div class="message-inner-dt">
                  <p>@{{ message.details }}</p>
                </div><!--message-inner-dt end-->
                <span>@{{ message.created_at }}</span>
              </div><!--message-dt end-->
              <div class="messg-usr-img">
                <img src="{{ asset('images/avatar.png') }}" alt="" class="mCS_img_loaded">
              </div><!--messg-usr-img end-->
            </div><!--main-message-box end-->
          </li>
        </ul>
        <div class="typing">
          <span v-if="this.room != null">@{{ this.room.other_member.user.name }}</span> Is Typing now ...
        </div>
			</div>
      <div class="message-send-area">
        <form @submit.prevent="sendMessage()">
          <div class="mf-field">
            <input type="text" name="message" placeholder="Type a message here" v-model="text_message">
            <button type="submit">Send</button>
          </div>
        </form>
      </div><!--message-send-area end-->
		</div>
	</div>
</div>
@endsection

@section('js')
<script>
  const app = new Vue({
    el : '#app',
    data : {
      rooms: [],
      rooms_page: 1,
      messages : [],
      messages_page : 1,
      room_id : '{{ request()->room }}',
      user_id : '{{ request()->user_id }}',
      room : null,
      auth_id : '{{ Auth::user()->id }}',
      text_message : '',
      push_status : true,
    },
    mounted() {
      this.listen();
    },
    methods : {
      infiniteRoomsHandler($state) {
        let vm = this;
        axios.get('/rooms', {
              params: {
                  page: this.rooms_page,
              },
          }).then(response => {
          if (response.data.rooms.data.length > 0) {
            $.each(response.data.rooms.data, function(key, value) {
                vm.rooms.push(value);
            });
            $state.loaded();
          } else {
            $state.complete();
          }
          this.rooms_page = this.rooms_page + 1;
        });
      },
      infiniteMessagesHandler($state) {
        let vm = this;
        if (this.room_id != '') {
          axios.get('/rooms/'+this.room_id+'/messages', {
                params: {
                    page: this.messages_page,
                },
            }).then(response => {
              this.room = response.data.room;
            if (response.data.messages.data.length > 0) {
              $.each(response.data.messages.data, function(key, value) {
                  vm.messages.unshift(value);
              });
              $state.loaded();
            } else {
              $state.complete();
            }
            this.messages_page = this.messages_page + 1;
          });
        }
      },
      sendMessage() {
        let vm = this;
        if (this.text_message != '') {
          axios.post('/message/send', {
            text_message: this.text_message,
            room_id: this.room_id,
            user_id: this.user_id,
          })
          .then(function (response) {
            vm.text_message = '';
            vm.messages.push(response.data.message);
            let room_id = response.data.message.room_id;
            if (response.data.redirect_url) {
              window.location = response.data.redirect_url;
            } else {
              vm.rooms.filter(function(room){
                  if (room.id == room_id) {
                    vm.updateRoomOrdering(vm.rooms.indexOf(room));
                  }
              });
            }
          })
          .catch(error => {
              alert(error.response.data.message);
          });
        }
      },
      listen() {
        let vm = this;
          Echo.private('messages')
              .listen('NewMessage', (message) => {
                this.rooms.filter(function(room){
                    if (room.id == vm.room_id && vm.room_id == message.message.room_id) {
                      vm.messages.push(message.message);
                      vm.$bus.$emit('message_seen', message.message.id);
                      vm.updateRoomOrdering(vm.rooms.indexOf(room));
                      vm.push_status = false;
                    } else if (room.id != vm.room_id && room.id == message.message.room_id) {
                      room.notseen_messages_count += 1;
                      vm.updateRoomOrdering(vm.rooms.indexOf(room));
                      vm.push_status = false;
                    }
                });
                if (this.push_status == true) {
                  this.pushUnfoundRoom(message.message.room_id);
                }
              });

            Echo.private('room.' + this.room_id)
              .listenForWhisper('typing', (e) => {
                e.typing ? $('.typing').show() : $('.typing').hide()
              })
              setTimeout( () => {
                $('.typing').hide()
              }, 2000);
      },
      updateRoomOrdering(indexOfRoom) {
        let object = this.rooms[indexOfRoom];
        this.rooms.splice(indexOfRoom, 1) // remove item from old location
        this.rooms.splice(0, 0, object) // reinsert it at new location
      },
      pushUnfoundRoom(room_id) {
        if (this.push_status == true) {
          axios.get('/auth_user/room/'+room_id).then(response => {
            if (response.data.room != null) {
              this.rooms.unshift(response.data.room);
            }
          });
        }
        this.push_status = false;
      }
    },
    watch : {
      text_message: function () {
        let channel = Echo.private('room.' + this.room_id)

        if (this.text_message != '') {
          setTimeout( () => {
            channel.whisper('typing', {
              typing: true
            })
          }, 300)
        } else {
          setTimeout( () => {
            channel.whisper('typing', {
              typing: false
            })
          }, 300)
        }
      },
    }
  });
</script>
@endsection
