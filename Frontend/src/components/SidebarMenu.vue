<template>
  <v-navigation-drawer app v-model="sideNav" clipped>
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
        <router-link to="courses" tag="v-list-tile">
            <v-list-tile-content>
              <v-list-tile-title>Explore</v-list-tile-title>
            </v-list-tile-content>
        </router-link>
      </v-list-group>

      <router-link to="teachers" tag="v-list-tile">
        <v-list-tile-action>
          <v-icon>face</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>Teachers</v-list-tile-title>
        </v-list-tile-content>
      </router-link>

    </v-list>
  </v-navigation-drawer>
</template>

<script>
    export default {
      props: {
        'sideNav': Boolean
      },

      data() {
        return {

        }
      }
    }
</script>

<style scoped>
  .router-link-active {
    background: #94c7d6;
  }
</style>
