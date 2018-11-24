<template>
  <li class="relative_li">
    <i v-if="this.notseen_messages_count > 0" class="badge badge-danger absolute_i">{{ notseen_messages_count }}</i>
    <a :href="messages_url" title="">
      <i class="fa fa-envelope fa-fw navbar-icon"></i>
      Messages
    </a>
  </li>
</template>

<script>
    export default {
      name: "MessagesComponent",

      data () {
          return {
            notseen_messages_count : 0,
            auth_rooms : [],
          }
      },

      props : ['user_id', 'messages_url'],

      mounted() {
        this.getNotseenMessages();
        this.getAuthRooms();
        this.listen();
        this.busEvents();
      },

      methods : {
        getNotseenMessages() {
          axios.get('/messages/not_seen').then(response => {
            this.notseen_messages_count = response.data.notseen_messages_count;
          });
        },
        getAuthRooms() {
          axios.get('/auth_rooms').then(response => {
            this.auth_rooms = response.data.auth_rooms;
          });
        },
        listen() {
        let vm = this;
          Echo.private('messages')
              .listen('NewMessage', (message) => {
                this.auth_rooms.filter(function(room){
                    if(room == message.message.room_id) {
                        vm.notseen_messages_count += 1;
                    }
                });
              });
        },
        busEvents() {
          this.$bus.$on('message_seen', (message) => {
              this.notseen_messages_count -= 1;
              axios.post('/message/seen', {message_id: message});
          })
        }
      },
    }
</script>

<style>

</style>
