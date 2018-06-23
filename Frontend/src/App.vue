<template>
  <v-app :dark="nightMode">

    <!--left sidebar menu-->
    <v-navigation-drawer app v-model="leftDrawer" clipped floating>

      <!--name and avatar-->
      <v-toolbar v-if="currentUser" flat class="transparent">
        <v-list class="pa-0">
          <v-list-tile avatar>
            <v-list-tile-avatar>
              <avatar :username="fullUserName"
                      :src="avatarUrl"
                      :size="45"
                      color="#fff"/>
            </v-list-tile-avatar>
            <v-list-tile-content>
              <v-list-tile-title>{{currentUser.first_name}} {{currentUser.last_name}}</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-toolbar>

      <v-list>

        <v-divider v-if="isAuthenticated" ></v-divider>
        <v-list-tile v-if="isAuthenticated"  to="/dashboard">
          <v-list-tile-action>
            <v-icon>dashboard</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Dashboard</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <!--Courses-->
        <v-list-group
          value="true"
          :prepend-icon="'assignment'"
          active-class="red--text"
          no-action
        >
          <v-list-tile slot="activator">
            <v-list-tile-content>
              <v-list-tile-title>Courses</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
          <v-list-tile to="/courses" exact>
            <v-list-tile-content class="ml-4">
              <v-list-tile-title>Explore</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
          <v-list-tile v-if="isTeacher || isAdmin" to="/courses/create" exact>
            <v-list-tile-content class="ml-4">
              <v-list-tile-title>Create</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list-group>

      </v-list>
    </v-navigation-drawer>

    <!--top navigation menu-->
    <v-toolbar app dark class="primary" clipped-left clipped-right>
      <v-toolbar-side-icon @click.stop="leftDrawer = !leftDrawer"></v-toolbar-side-icon>
      <router-link tag="v-toolbar-title" to="/" style="cursor: pointer">Smart Academy</router-link>
      <v-spacer></v-spacer>
      <v-toolbar-items>
        <v-btn flat @click.stop="nightMode = !nightMode">
          <v-icon>invert_colors</v-icon>
        </v-btn>

        <template v-if="isAuthenticated">
          <v-menu offset-y left>
            <v-btn flat dark slot="activator">
              <v-icon left>account_circle</v-icon>
              {{currentUser.name}}
            </v-btn>
            <v-list>
              <v-list-tile to="/myProfile">
                <v-list-tile-action><v-icon>account_circle</v-icon></v-list-tile-action>
                <v-list-tile-title>My Profile</v-list-tile-title>
              </v-list-tile>
              <v-list-tile @click="logout">
                <v-list-tile-action><v-icon>fa fa-sign-out-alt</v-icon></v-list-tile-action>
                <v-list-tile-title>Logout</v-list-tile-title>
              </v-list-tile>
            </v-list>
          </v-menu>

        </template>
        <template v-else>
          <v-btn to="/login" flat dark>
            <v-icon left>fa fa-sign-in-alt</v-icon>
            Log In
          </v-btn>
          <v-btn to="/register" flat dark>
            <v-icon left>account_circle</v-icon>
            Register
          </v-btn>
        </template>
      </v-toolbar-items>
    </v-toolbar>

    <!--dynamic content-->
    <v-content>
      <router-view/>
    </v-content>

    <!--snackbar(notification bar)-->
    <v-snackbar
      :timeout="snackBar.timeout"
      :top="snackBar.top === true"
      :right="snackBar.right === true"
      :color="snackBar.color"
      v-model="snackBar.show"
    >
      {{ snackBar.text }}
      <v-btn flat dark @click.native="snackBar.show = false">
        <v-icon>close</v-icon>
      </v-btn>
    </v-snackbar>

    <!--footer-->
    <v-footer app fixed>
      <v-spacer></v-spacer>
      <span class="mr-1">Smart Academy &copy; 2018</span>
    </v-footer>
  </v-app>
</template>

<script>
  import {apiUrl, backendUrl} from '@/config';
  import { mapGetters } from 'vuex';
  import Avatar from 'vue-avatar';

  export default {
    name: 'App',

    components: {
      Avatar
    },

    data () {
      return {
        leftDrawer: true,
        nightMode: false
      }
    },

    computed: {
      ...mapGetters([
        'currentUser',
        'isAuthenticated',
        'isTeacher',
        'isAdmin',
        'snackBar'
      ]),
      fullUserName(){
        return this.currentUser.first_name + ' ' + this.currentUser.last_name;
      },
      avatarUrl() {
        if (this.currentUser) {
          return this.currentUser.image_url ? backendUrl + this.currentUser.image_url : '';
        }
      }
    },

    methods: {
      logout() {
        this.$store.dispatch('logout')
          .then(response => {
            this.$router.push('/');
          })
          .catch(err => console.log(err));
      }
    }

  }
</script>

<style>
  .vjs-custom-skin > .video-js .vjs-big-play-button {
    top: 50%;
    left: 50%;
    margin-left: -1.5em;
    margin-top: -1em
  }

  .error-box {
    border: #ff0900 2px solid;
    background-color: #ffc1bc;
  }
</style>
