<template>
  <li class="relative_li" @click="toggleActiveUl()">
    <i v-if="this.notseend_notifications_count > 0" class="badge badge-danger absolute_i">{{ notseend_notifications_count }}</i>
    <a href="javascript:void(0);" title="">
      <i class="fa fa-bell fa-fw navbar-icon"></i>
      Notifications
    </a>
    <ul class="child-ul" :class="{'active':(active_ul == true)}">
      <li v-for="notification in notifications" class="li" :class="{'seen':(notification.status == 'seen')}">
        <a :href="notification.url">
          <img :src="notification.user_image" class="avatar_image">
          <span>{{ notification.details }}</span>
          <i class="date">{{ notification.created_at }}</i>
        </a>
      </li>
    <infinite-loading @distance="1" @infinite="infiniteHandler"></infinite-loading>
    </ul>
  </li>
</template>

<script>
    export default {
      name: "NotificationsComponent",

      data () {
          return {
            notifications: [],
            page: 1,
            notseend_notifications_count : 0,
            active_ul : false,
          }
      },

      props : ['user_id'],

      mounted() {
        this.listen();
      },

      methods : {
        infiniteHandler($state) {
            let vm = this;
            axios.get('/notifications', {
                  params: {
                      page: this.page,
                  },
              }).then(response => {
              this.notseend_notifications_count = response.data.notseend_notifications_count;
              if (response.data.notifications.data.length > 0) {
                $.each(response.data.notifications.data, function(key, value) {
                    vm.notifications.push(value);
                });
                $state.loaded();
              } else {
                $state.complete();
              }
              this.page = this.page + 1;
            });
        },
        toggleActiveUl() {
          if (this.active_ul == false) {
            axios.post('/set-notifications-seen')
            this.notseend_notifications_count = 0;
          }
          this.active_ul = ! this.active_ul;
        },
        listen() {
          Echo.private('notification.'+this.user_id+'.user')
              .listen('NewNotification', (notification) => {
                this.notifications.unshift(notification.notification);
                this.notseend_notifications_count += 1;
              });
        }
      },
    }
</script>

<style>
.avatar_image{width: 35px;height: 35px;border-radius: 50%;border: 1px solid #ccc;}
.li a{margin-left:10px;font-size:12px;}
.li a span{margin: 7px 5px;padding-left: 10px;display: block;color: #000;overflow: hidden;max-height: 25px;height: 25px;}
.li a .date{color: #797979;display: block;margin: 0 30px;float: right;}
.relative_li{position:relative;}
.relative_li .absolute_i{position:absolute;top:15px;right:15px;}
.child-ul{visibility:hidden;max-height: 500px;overflow-y: scroll;overflow-x: hidden;}
.child-ul.active{visibility:visible;}
.seen{background-color:#fff;}
.infinite-loading-container{height:50px;margin-bottom:20px;}
</style>
