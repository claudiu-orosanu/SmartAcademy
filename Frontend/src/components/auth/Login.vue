<template>
  <v-container class="fluid fill-height">
    <v-layout class="justify-center align-center">
      <v-flex xs12 sm8 md4>
        <v-card>
          <v-toolbar dark color="primary">
            <v-toolbar-title class="mx-auto">Login</v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form v-model="formIsValid">
              <v-text-field prepend-icon="email"
                            name="email"
                            label="Email"
                            type="text"
                            v-model="email"
                            :rules="emailRules"
                            @keyup.enter="login"
              ></v-text-field>
              <v-text-field prepend-icon="lock"
                            name="password"
                            label="Password"
                            type="password"
                            v-model="password"
                            :rules="passwordRules"
                            @keyup.enter="login"
              ></v-text-field>
            </v-form>

            <a class="accent--text body-1" @click.stop="resetPassword">Forgot your password?</a>

            <!--errors-->
            <v-layout v-if="this.errors.length" row class="mt-4">
              <v-flex>
                <v-list class="error-box">
                  <v-list-tile v-for="(error,index) in errors" :key="index">
                    <v-list-tile-action>
                      <v-icon color="red">error</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>{{error}}</v-list-tile-content>
                  </v-list-tile>
                </v-list>
              </v-flex>
            </v-layout>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="secondary" :disabled="!formIsValid" @click="login">Login</v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import {apiUrl, backendUrl} from '@/config';

  export default {
    name: 'Login',

    data() {
      return {
        email: '',
        password: '',
        errors: [],
        formIsValid: false,
        emailRules: [
          v => !!v || 'E-mail is required',
          v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
        ],
        passwordRules: [
          v => !!v || 'Password is required',
          v => (v && v.length >= 5) || 'Password must be greater than or equal to 5 characters'
        ]
      }
    },

    computed: {
    },

    methods: {
      login(){
        if(!this.formIsValid)
          return;

        // call login api
        let credentials = {
          email: this.email,
          password: this.password
        }

        this.$store.dispatch('login', credentials)
          .then(() => {
            this.errors = [];
            this.$router.back();
          })
          .catch(err => {
            this.errors = err;
          });
      },
      resetPassword() {
        this.$router.push('/recoverPassword');
      }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
