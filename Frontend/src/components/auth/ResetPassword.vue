<template>
  <v-container class="fluid fill-height">
    <v-layout class="justify-center align-center">
      <v-flex xs12 sm8 md4>
        <v-card>
          <v-toolbar dark color="primary">
            <v-toolbar-title class="mx-auto">Reset password</v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form v-model="formIsValid">
              <v-text-field prepend-icon="email"
                            name="email"
                            label="Email"
                            type="text"
                            v-model="email"
                            :rules="emailRules"
                            @keyup.enter="resetPassword"
              ></v-text-field>
              <v-text-field prepend-icon="lock"
                            name="password"
                            label="New password"
                            type="password"
                            v-model="password"
                            :rules="passwordRules"
                            @keyup.enter="resetPassword"
              ></v-text-field>
              <v-text-field prepend-icon="lock"
                            name="repeatPassword"
                            label="Repeat Password"
                            type="password"
                            v-model="passwordRepeat"
                            :rules="passwordRules"
                            @keyup.enter="resetPassword"
              ></v-text-field>
            </v-form>


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
            <v-btn color="secondary" :disabled="!formIsValid" @click="resetPassword">Reset password</v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import {apiUrl, backendUrl} from '@/config';

  export default {
    name: 'ResetPassword',

    data() {
      return {
        email: '',
        password: '',
        passwordRepeat: '',
        errors: [],
        formIsValid: false,
        emailRules: [
          v => !!v || 'E-mail is required',
          v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
        ],
        passwordRules: [
          v => !!v || 'Password is required',
          v => (v && v.length >= 5) || 'Password must be greater than or equal to 5 characters'
        ],
      }
    },

    computed: {
    },

    methods: {
      resetPassword(){
        if(!this.formIsValid)
          return;

        if(this.password !== this.passwordRepeat) {
          this.errors = ['Passwords do not match.']
          return;
        }

        let credentials = {
          email: this.email,
          password: this.password,
          password_confirmation: this.password,
          token: Object.keys(this.$router.currentRoute.query)[0]
        }

        this.$store.dispatch('resetPassword', credentials)
          .then(() => {
            this.errors = [];
            this.$store.commit('showSnackbar', { timeout:15000, top: true, right: true, show: true,
              color:'success',
              text: 'Your password has been reset!'
            })
          })
          .catch(err => {
            this.errors = err;
          });
      }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
