<template>
  <v-app>

    <!--right sidebar menu-->
    <v-navigation-drawer app v-model="sideNav" persistent clipped>
      <v-list>
        <v-list-tile>
          <v-list-tile-action>
            <v-icon>supervisor_account</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            View professors
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>

    <!--top navigation menu-->
    <v-toolbar app dark class="primary" clipped-left>
      <v-toolbar-side-icon @click.stop="sideNav = !sideNav"></v-toolbar-side-icon>
      <v-toolbar-title>Smart Academy</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-toolbar-items>
        <v-btn flat dark>
          <v-icon left>supervisor_account</v-icon>
          View professors
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
    data () {
      return {
        sideNav: false
      }
    },
    name: 'App'
  }
</script>
