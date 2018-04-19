<template>
  <div>
    <!--loading spinner-->
    <v-container v-if="!course.name">
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

    <div>
      Course component {{course}} with id {{this.$route.params}}
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    name: 'Course',

    data() {
      return {

      }
    },

    computed: {
      ...mapGetters({
        course: 'selectedCourse'
      })
    },

    created() {
      // clear cached selected course if the new selected course is different
      if(this.$store.getters.selectedCourse.id != this.$route.params.id){
        this.$store.dispatch('clearSelectedCourse');
      }

      // get the new selected course
      this.$store.dispatch('getSelectedCourse', this.$route.params.id);
    },

    methods: {

    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
