<template>
  <v-app>

    <!--left sidebar menu-->
    <v-navigation-drawer app v-model="leftDrawer" clipped floating>
      <v-toolbar flat class="transparent">
        <v-list class="pa-0">
          <v-list-tile avatar>
            <v-list-tile-avatar>
              <img src="https://randomuser.me/api/portraits/men/85.jpg">
            </v-list-tile-avatar>
            <v-list-tile-content>
              <v-list-tile-title>Claudiu Orosanu</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-toolbar>

      <v-list>

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
          <router-link to="/courses" tag="v-list-tile">
            <v-list-tile-content class="ml-4">
              <v-list-tile-title>Explore</v-list-tile-title>
            </v-list-tile-content>
          </router-link>
          <router-link to="/courses/create" tag="v-list-tile">
            <v-list-tile-content class="ml-4">
              <v-list-tile-title>Create</v-list-tile-title>
            </v-list-tile-content>
          </router-link>
        </v-list-group>

        <router-link to="/teachers" tag="v-list-tile">
          <v-list-tile-action>
            <v-icon>face</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Teachers</v-list-tile-title>
          </v-list-tile-content>
        </router-link>

      </v-list>
    </v-navigation-drawer>

    <!--top navigation menu-->
    <v-toolbar app dark class="primary" clipped-left clipped-right>
      <v-toolbar-side-icon @click.stop="leftDrawer = !leftDrawer"></v-toolbar-side-icon>
      <router-link tag="v-toolbar-title" to="/" style="cursor: pointer">Smart Academy</router-link>
      <v-spacer></v-spacer>
      <v-toolbar-items>
        <v-btn flat dark>
          <v-icon left>account_circle</v-icon>
          Claudiu
        </v-btn>
      </v-toolbar-items>
    </v-toolbar>

    <!--dynamic content-->
    <v-content>
      <router-view/>
    </v-content>

    <!--footer-->
    <v-footer app fixed>
      <v-spacer></v-spacer>
      <span class="mr-1">Smart Academy &copy; 2018</span>
    </v-footer>
  </v-app>
</template>

<script>
  export default {
    name: 'App',

    data () {
      return {
        leftDrawer: true,
      }
    },

  }
</script>
