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

    <!--search & filter container-->
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

    <!--courses container-->
    <v-container grid-list-xl>
      <v-layout row wrap>
        <v-flex xs6 sm4 v-for="(course,index) in courses" :key="index" @click="goToCourse(course)" style="cursor: pointer">
          <v-card dark color="accent" >
            <v-card-media
              src="https://cdn-images-1.medium.com/max/825/1*CDlclChuNeeM5Shfev2RTg.jpeg"
              height="200px"
            >
            </v-card-media>
            <v-card-title primary-title>
              <div>
                <div class="headline">{{ course.name }}</div>
                <span class="white--text">{{ categoryIdToText(course.category) }}</span>
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
      <infinite-loading @infinite="infiniteHandler" ref="infiniteLoading" spinner="spiral">
        <span slot='no-more'>
          No more courses :(
        </span>
      </infinite-loading>
    </v-container>

  </div>
</template>

<script>
  import InfiniteLoading from 'vue-infinite-loading';
  import _ from 'lodash';
  import constants from '@/constants';
  import { mapGetters } from 'vuex';

  export default {
    name: 'Courses',

    components: {
      InfiniteLoading,
    },

    data() {
      return {
        showDescription: false,
        categories: constants.courseCategories,
        selectedCategories: [],
        searchTerm: ''
      }
    },

    computed: {
      ...mapGetters([
        'courses'
      ])
    },

    methods: {
      infiniteHandler($state) {

        // get courses from store
        let courses = this.$store.getters.courses;

        let itemsPerPage = 9;
        let page = courses.length / itemsPerPage + 1;

        let payload = {
          infHandlerState: $state,
          itemsPerPage,
          page,
          searchTerm: this.searchTerm,
          categories: this.selectedCategories.map(c => constants.courseCategoriesMapping[c])
        }

        //get courses from backend API
        this.$store.dispatch('getPaginatedCourses', payload);
      },

      searchTermChanged: _.debounce(function () {
        this.$store.dispatch('clearCourses');
        this.$nextTick(() => {
          this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
        });
      }, 450),

      categoryFilterChanged() {
        this.$store.dispatch('clearCourses');
        this.$nextTick(() => {
          this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
        });
      },

      categoryIdToText(categoryId) {
        return constants.courseCategories[categoryId]
      },

      goToCourse(course){
        this.$router.push('/courses/' + course.id);
      }

    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
