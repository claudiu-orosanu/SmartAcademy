<template>
  <div>

        <v-card-title class="display-1 justify-center">Teachers</v-card-title>

        <v-container>
          <v-layout row wrap >
            <v-flex class="mx-auto" xs12 sm10 md9 mb-3 v-for="(teacher, index) in teachers" :key="index">
              <v-card>
                <v-card-title primary-title>
                  <avatar
                    :username="teacher.first_name + ' ' + teacher.last_name"
                    color="#fff"
                  />
                  <div>
                    <div class="headline pl-3">{{ teacher.first_name + ' ' + teacher.last_name }}</div>
                  </div>
                </v-card-title>
                <v-card-text>{{teacher.qualifications}}</v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="success" @click="acceptTeacher(teacher.id)">Accept</v-btn>
                  <v-btn color="error" @click="declineTeacher(teacher.id)">Decline</v-btn>
                </v-card-actions>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
  </div>
</template>

<script>
  import {backendUrl} from '@/config';
  import constants from '@/constants';
  import Avatar from 'vue-avatar';

  export default {
    name: 'Teachers',

    components: {
      Avatar
    },

    data() {
      return {
        teachers: [],
        backendUrl: backendUrl,
      }
    },

    created() {
      // get the user
      this.$store.dispatch('getTeachers')
        .then((response) => {
          this.teachers = response.data.teachers;
        })
        .catch(err => console.log(err))
    },

    methods: {
      acceptTeacher(teacherId) {
        this.$store.dispatch('acceptTeacher', teacherId)
          .then((response) => {
            this.teachers = this.teachers.filter(t => t.id !== teacherId);
          })
          .catch(err => console.log(err))
      },

      declineTeacher(teacherId){
        this.$store.dispatch('declineTeacher', teacherId)
          .then((response) => {
            this.teachers = this.teachers.filter(t => t.id !== teacherId);
          })
          .catch(err => console.log(err))
      },
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
