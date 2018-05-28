<template>
  <v-container>
    <v-layout row>
      <v-flex xs12 sm10 md8 class="mx-auto">
        <v-card>
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
            <v-form v-model="formIsValid">
              <v-text-field prepend-icon="person"
                            name="firstName"
                            label="First Name"
                            type="text"
                            hint="First Name"
                            v-model="firstName"
                            :rules="nameRules"
              ></v-text-field>
              <v-text-field prepend-icon="person"
                            name="lastName"
                            label="Last Name"
                            type="text"
                            v-model="lastName"
                            :rules="nameRules"
              ></v-text-field>
              <v-text-field prepend-icon="email"
                            name="email"
                            label="Email"
                            type="text"
                            readonly
                            v-model="email"
              ></v-text-field>
              <v-text-field prepend-icon="subject"
                            name="aboutMe"
                            label="About Me"
                            type="text"
                            :rules="[(v) => v.length <= 512 || 'Max 512 characters']"
                            :counter="512"
                            multi-line clearable
                            v-model="aboutMe"
              ></v-text-field>

              <!--photo-->
              <v-layout row class="mt-3">
                <v-flex xs12 sm10 offset-sm1>
                  <v-btn style="width: 170px; margin-left: 0px;" @click="$refs.imageUploadInput.click()">Change Profile
                    Photo
                  </v-btn>
                  <input type="file" style="display: none" ref="imageUploadInput" @change="onImageSelected"
                         accept="image/*">
                  <img :src="imagePreview" width="75" class="d-block mt-2">
                </v-flex>
              </v-layout>

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
            <v-btn color="secondary" :disabled="!formIsValid" @click="updateUser">Save</v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>

    <v-layout row>
      <v-flex xs12 sm10 md8 mt-4 class="mx-auto">
        <v-card>
          <v-card-title class="title">
            Change Password
          </v-card-title>
          <v-card-text>
            <v-form v-model="passwordFormIsValid">
              <v-text-field prepend-icon="lock"
                            name="oldPassword"
                            label="Old Password"
                            type="password"
                            v-model="oldPassword"
                            :rules="passwordRules">
              </v-text-field>
              <v-text-field prepend-icon="lock"
                            name="password"
                            label="New Password"
                            type="password"
                            v-model="password"
                            :rules="passwordRules"
              ></v-text-field>
              <v-text-field prepend-icon="lock"
                            name="repeatPassword"
                            label="Repeat Password"
                            type="password"
                            v-model="passwordRepeat"
                            :rules="passwordRules"
              ></v-text-field>
            </v-form>
            <!--errors-->
            <v-layout v-if="this.passwordErrors.length" row class="mt-4">
              <v-flex>
                <v-list class="error-box">
                  <v-list-tile v-for="(error,index) in passwordErrors" :key="index">
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
            <v-btn color="secondary" :disabled="!passwordFormIsValid" @click="changePassword">Change</v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import {apiUrl, backendUrl} from '@/config';
  import {mapGetters} from 'vuex';
  import Avatar from 'vue-avatar';

  export default {
    name: 'MyProfile',

    components: {
      Avatar
    },

    data() {
      return {
        firstName: '',
        lastName: '',
        email: '',
        aboutMe: '',
        oldPassword: '',
        password: '',
        passwordRepeat: '',
        errors: [],
        formIsValid: false,
        passwordFormIsValid: false,
        passwordErrors: [],
        nameRules: [
          v => !!v || 'Name is required'
        ],
        passwordRules: [
          v => !!v || 'Password is required',
          v => (v && v.length >= 5) || 'Password must be greater than or equal to 5 characters'
        ],
        image: '',
        imagePreview: ''
      }
    },

    computed: {
      ...mapGetters([
        'currentUser',
        'snackBar'
      ]),
      fullUserName() {
        if (this.currentUser) {
          return this.currentUser.first_name + ' ' + this.currentUser.last_name;
        }
      },
      avatarUrl() {
        if (this.currentUser) {
          return this.currentUser.image_url ? backendUrl + this.currentUser.image_url : '';
        }
      }
    },

    created() {
      if (this.currentUser) {
        this.firstName = this.currentUser.first_name;
        this.lastName = this.currentUser.last_name;
        this.aboutMe = this.currentUser.about_me;
        this.email = this.currentUser.email;
        this.image = this.currentUser.image_url;
      }
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

      changePassword() {
        if (!this.passwordFormIsValid)
          return;

        if(this.password !== this.passwordRepeat) {
          this.showSnackbar('Passwords do not match', 'error', true, false);
          return;
        }

        let payload = {
          oldPassword: this.oldPassword,
          password: this.password,
        }

        // dispatch store action
        this.$store.dispatch('changePassword', payload)
          .then(response => {
            this.showSnackbar('Password changed', 'success', true, true);
            this.passwordErrors = [];
          })
          .catch(err => {
            this.passwordErrors = err;
          });
      },


      /**
       * Called when user has selected an image for upload
       *
       * @param event
       */
      onImageSelected(event) {
        if (!event.target.files.length) {
          return;
        }

        let validExtensions = ['png', 'PNG', 'jpg', 'jpeg'];
        let file = event.target.files[0];
        let fileNameTokens = file.name.split('.');
        if (fileNameTokens.length < 2 || validExtensions.indexOf(fileNameTokens[1]) == -1) {
          this.showSnackbar('Please choose a valid image file!', 'error', true, false);
          return;
        }

        this.image = event.target.files[0];

        // show image preview
        let reader = new FileReader();
        reader.onload = (e) => {
          this.imagePreview = e.target.result;
        }
        reader.readAsDataURL(this.image);

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
