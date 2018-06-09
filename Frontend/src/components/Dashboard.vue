<template>
  <div>

    <v-container>
      <!--most popular courses-->
      <v-card>
        <v-card-title class="display-1">Most popular courses</v-card-title>
        <v-divider></v-divider>
        <v-container grid-list-xl>
          <v-layout row wrap>
            <v-flex xs12 sm6 md4 v-for="(item,index) in mostPopularCourses" :key="index" @click="goToCourseDetails(item.course)" style="cursor: pointer">
              <v-card dark color="accent">
                <v-card-media
                  :src="backendUrl + item.course.image_url"
                  height="200px"
                >
                </v-card-media>
                <v-card-title primary-title>
                  <div>
                    <div class="headline">{{ item.course.name }}</div>
                    <span class="white--text">{{ categoryIdToText(item.course.category) }}</span>
                  </div>
                </v-card-title>
                <v-card-text>{{item.students}} students enrolled</v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card>

      <!--best rated courses-->
      <v-card class="mt-5">
        <v-card-title class="display-1">Best rated courses</v-card-title>
        <v-divider></v-divider>
        <v-container grid-list-xl>
          <v-layout row wrap>
            <v-flex xs12 sm6 md4 v-for="(item,index) in bestRatedCourses" :key="index" @click="goToCourseDetails(item.course)" style="cursor: pointer">
              <v-card dark color="accent">
                <v-card-media
                  :src="backendUrl + item.course.image_url"
                  height="200px"
                >
                </v-card-media>
                <v-card-title primary-title>
                  <div>
                    <div class="headline">{{ item.course.name }}</div>
                    <span class="white--text">{{ categoryIdToText(item.course.category) }}</span>
                  </div>
                </v-card-title>
                <v-card-text>{{item.score}} out of 5 stars</v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card>
    </v-container>

  </div>
</template>

<script>
  import {backendUrl} from '@/config';
  import constants from '@/constants';

  export default {
    name: 'Dashboard',

    data() {
      return {
        mostPopularCourses: [],
        bestRatedCourses: [],
        backendUrl: backendUrl,
      }
    },

    created() {
      // get the user
      this.$store.dispatch('getDashboardData')
        .then((response) => {
          console.log(response);
          this.mostPopularCourses = response.data.mostPopularCourses;
          this.bestRatedCourses = response.data.bestRatedCourses;
        })
        .catch(err => console.log(err))
    },

    methods: {
      categoryIdToText(categoryId) {
        return constants.courseCategories[categoryId];
      },

      goToCourseDetails(course){
        this.$router.push('/courses/' + course.id +'/details');
      },
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
