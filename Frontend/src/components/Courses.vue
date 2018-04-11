<template>
  <div>
    <v-container class="text-xs-center">
      <v-layout column>
        <v-flex>
          <div class="display-1">Available courses</div>
        </v-flex>
        <v-flex>
          <div class="subheading">Are you going to learn something new today? Pick a course and start improving your skills!</div>
        </v-flex>
      </v-layout>
    </v-container>
    <v-container grid-list-xl>
      <v-layout row wrap>
        <v-flex xs6 sm4 v-for="course in courses" :key="course.id">
          <v-card dark color="accent">
            <v-card-media
              src="https://cdn-images-1.medium.com/max/825/1*CDlclChuNeeM5Shfev2RTg.jpeg"
              height="200px"
            >
            </v-card-media>
            <v-card-title primary-title>
              <div>
                <div class="headline">{{ course.name }}</div>
                <span class="white--text">{{ course.category }}</span>
              </div>
            </v-card-title>
            <v-card-actions>
              <v-btn color="secondary">Enroll</v-btn>
              <v-spacer></v-spacer>
              <v-btn icon @click.native="showDescription = !showDescription">
                <v-icon>{{ showDescription ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
              </v-btn>
            </v-card-actions>
            <v-slide-y-transition>
              <v-card-text v-show="showDescription">
                {{ course.description }}
              </v-card-text>
            </v-slide-y-transition>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>

  </div>
</template>

<script>
  import axios from 'axios';

  export default {
    name: 'Courses',
    data() {
      return {
        courses: [],
        showDescription: false
      }
    },

    created() {
      axios.get('http://smart-academy.test/api/v1/courses')
        .then(response => {
          this.courses = response.data;
        })
        .catch(err => {
          console.log(err);
        })
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
