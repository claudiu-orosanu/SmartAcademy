<template>
  <v-app>

    <!--left sidebar menu-->
    <app-sidebar-menu :side-nav="sideNav"></app-sidebar-menu>

    <!--top navigation menu-->
    <v-toolbar app dark class="primary" clipped-left>
      <v-toolbar-side-icon @click.stop="sideNav = !sideNav"></v-toolbar-side-icon>
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
  import SidebarMenu from './components/SidebarMenu'

  export default {
    name: 'App',

    components: {
      'app-sidebar-menu': SidebarMenu
    },

    data () {
      return {
        sideNav: true
      }
    },

  }
</script>
