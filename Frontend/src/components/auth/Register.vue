<template>
  <v-container class="fluid fill-height">
    <v-layout class="justify-center align-center">
      <v-flex xs12 sm8 md6>
        <v-card>
          <v-toolbar dark color="primary">
            <v-toolbar-title class="mx-auto">Register</v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form v-model="formIsValid">
              <v-text-field prepend-icon="person"
                            name="firstName"
                            label="First Name"
                            type="text"
                            v-model="firstName"
                            :rules="nameRules"
                            @keyup.enter="register"
              ></v-text-field>
              <v-text-field prepend-icon="person"
                            name="lastName"
                            label="Last Name"
                            type="text"
                            v-model="lastName"
                            :rules="nameRules"
                            @keyup.enter="register"
              ></v-text-field>
              <v-text-field prepend-icon="email"
                            name="email"
                            label="Email"
                            type="text"
                            v-model="email"
                            :rules="emailRules"
                            @keyup.enter="register"
              ></v-text-field>
              <v-text-field prepend-icon="lock"
                            name="password"
                            label="Password"
                            type="password"
                            v-model="password"
                            :rules="passwordRules"
                            @keyup.enter="register"
              ></v-text-field>
              <v-text-field prepend-icon="lock"
                            name="repeatPassword"
                            label="Repeat Password"
                            type="password"
                            v-model="passwordRepeat"
                            :rules="passwordRules"
                            @keyup.enter="register"
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
            <v-btn color="secondary" :disabled="!formIsValid" @click="register">Register</v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import {apiUrl, backendUrl} from '@/config';

  export default {
    name: 'Register',

    data() {
      return {
        firstName: '',
        lastName: '',
        email: '',
        password: '',
        passwordRepeat: '',
        errors: [],
        formIsValid: false,
        nameRules: [
          v => !!v || 'Name is required'
        ],
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
      register(){
        if(!this.formIsValid)
          return;

        if(this.password !== this.passwordRepeat) {
          this.errors = ['Passwords do not match.']
          return;
        }

        // call register api
        let credentials = {
          firstName: this.firstName,
          lastName: this.lastName,
          email: this.email,
          password: this.password
        }

        this.$store.dispatch('register', credentials)
          .then(() => {
            this.errors = [];
            this.$store.commit('showSnackbar', { timeout:15000, top: true, right: true, show: true,
              color:'success',
              text: 'You have successfully registered! Check your email to verify your account!'
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
