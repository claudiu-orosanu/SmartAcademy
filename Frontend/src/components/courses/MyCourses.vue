<template>
  <div>
    <v-container class="text-xs-center">
      <v-layout column>
        <v-flex>
          <div class="display-1">Your courses</div>
        </v-flex>
        <v-flex>
          <div class="subheading">These are the courses that you enrolled in. Continue your learning!</div>
        </v-flex>
      </v-layout>
    </v-container>

    <v-container>
      <v-layout row>
        <v-flex xs5 md4>
          <v-text-field
            name="search-course"
            label="Search course"
            v-model="searchTerm"
            single-line
            append-icon="search"
            @input="searchTermChanged()"
          ></v-text-field>
        </v-flex>
        <v-spacer></v-spacer>
        <v-flex xs5 md4>
          <v-select
            label="Filter by category"
            :items="categories"
            v-model="selectedCategories"
            multiple
            chips
            deletable-chips
            @input="categoryFilterChanged()"
          ></v-select>
        </v-flex>
      </v-layout>
    </v-container>

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

    <!--courses container-->
    <v-container grid-list-xl v-show="!loading && courses.length">
      <v-layout row wrap>
        <v-flex xs6 sm4 v-for="(course,index) in filteredCourses" :key="index" @click="goToCourseDetails(course)" style="cursor: pointer">
          <v-card dark color="accent">
            <v-card-media
              :src="backendUrl + course.image_url"
              height="200px"
            >
            </v-card-media>
            <v-card-title primary-title>
              <div>
                <div class="headline">{{ course.name }}</div>
                <span class="white--text">{{ categoryIdToText(course.category) }}</span>
              </div>
            </v-card-title>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>

    <!--no courses text-->
    <v-container grid-list-xl v-show="!loading && !courses.length">
      <v-layout row wrap>
        <v-flex class="text-xs-center">
          <div class="subheading">No courses found.</div>
        </v-flex>
      </v-layout>
    </v-container>

  </div>
</template>

<script>
  import _ from 'lodash';
  import constants from '@/constants';
  import {backendUrl} from '@/config';

  export default {
    name: 'MyCourses',

    components: {
    },

    data() {
      return {
        courses: [],
        filteredCourses: [],
        categories: constants.courseCategories,
        selectedCategories: [],
        searchTerm: '',
        backendUrl: backendUrl,
        loading: true
      }
    },

    computed: {
    },

    created() {
      this.getMyCourses();
    },

    methods: {

      searchTermChanged: _.debounce(function() {

        if(this.searchTerm === '') {
          this.filteredCourses = this.courses;
        }

        this.filteredCourses = this.courses.filter(course => {
          return course.name.includes(this.searchTerm);
        })
      }, 450),

      categoryFilterChanged() {
        if(!this.selectedCategories.length) {
          this.filteredCourses = this.courses;
        }

        this.filteredCourses = this.courses.filter(course => {
          let selectedCategories = Object.keys(this.selectedCategories).map(cat => parseInt(cat));
          return selectedCategories.includes(course.category);
        })
      },

      categoryIdToText(categoryId) {
        return constants.courseCategories[categoryId]
      },

      goToCourseDetails(course){
        this.$router.push('/courses/' + course.id +'/details');
      },

      getMyCourses: function() {
        this.$store.dispatch('getMyCourses')
          .then(response => {
            this.courses = response.data;
            this.filteredCourses = this.courses;
            this.loading = false;
          })
          .catch(err => console.log(err))
      },

    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
