<template>
  <v-container>
    <v-layout row>
      <v-flex xs12 sm10 md8 class="mx-auto">

        <!--loading spinner-->
        <v-container v-show="loading">
          <v-layout>
            <v-flex xs12 class="text-xs-center">
              <v-progress-circular
                indeterminate
                class="primary--text"
                :width="4"
                :size="40"
              ></v-progress-circular>
            </v-flex>
          </v-layout>
        </v-container>

        <v-card v-show="!loading">
          <div style="height: 20px"></div>
          <v-layout row class="justify-center">
            <avatar
              :username="fullUserName"
              :src="avatarUrl"
              :size="75"
              color="#fff"
            />
          </v-layout>
          <v-card-title class="display-1 justify-center">{{fullUserName}}</v-card-title>
          <v-card-text>
              <v-text-field prepend-icon="person"
                            name="firstName"
                            label="First Name"
                            type="text"
                            hint="First Name"
                            readonly
                            v-model="user.first_name"
              ></v-text-field>
              <v-text-field prepend-icon="person"
                            name="lastName"
                            label="Last Name"
                            type="text"
                            readonly
                            v-model="user.last_name"
              ></v-text-field>
              <v-text-field prepend-icon="email"
                            name="email"
                            label="Email"
                            type="text"
                            readonly
                            v-model="user.email"
              ></v-text-field>
              <v-text-field prepend-icon="subject"
                            name="aboutMe"
                            label="About Me"
                            readonly
                            type="text"
                            multi-line
                            v-model="user.about_me"
              ></v-text-field>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>

  </v-container>
</template>

<script>
  import {apiUrl, backendUrl} from '@/config';
  import Avatar from 'vue-avatar';

  export default {
    name: 'Profile',

    components: {
      Avatar
    },

    data() {
      return {
        user: '',
        loading: true
      }
    },

    computed: {
      fullUserName() {
        return this.user.first_name + ' ' + this.user.last_name;
      },
      avatarUrl() {
        return this.user.image_url ? backendUrl + this.user.image_url : '';
      }
    },

    created() {
      // get the user
      this.$store.dispatch('getUser', this.$route.params.id)
        .then((response) => {
          this.user = response.data;
          this.loading = false;
        })
        .catch(err => console.log(err))
    },

    methods: {
      updateUser() {
        if (!this.formIsValid)
          return;

        let user = {
          id: this.currentUser.id,
          firstName: this.firstName,
          lastName: this.lastName,
          email: this.email,
          aboutMe: this.aboutMe,
          image: this.image,
          token: this.currentUser.token
        }

        // dispatch store action
        this.$store.dispatch('updateUser', user)
          .then(response => {
            this.showSnackbar('User updated', 'success', true, true);
            this.errors = [];
          })
          .catch(err => {
            this.errors = err;
          });
      },

      /**
       * Show snackbar (notification bar)
       *
       * @param text
       * @param color
       * @param top
       * @param right
       */
      showSnackbar(text, color, top, right) {
        let settings = {
          timeout: 5000,
          text: text,
          color: color,
          top: top,
          right: right,
          show: true
        }
        this.$store.commit('showSnackbar', settings)
      }
    }

  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
