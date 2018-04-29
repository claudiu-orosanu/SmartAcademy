<template>
  <div>

    <v-tabs height="73%" fixed-tabs grow>
      <v-tab>
        <v-icon class="pr-2" medium color="primary">video_library</v-icon>
        Video Lecture
      </v-tab>
      <v-tab>
        <v-icon class="pr-2" medium color="primary">library_books</v-icon>
        Document
      </v-tab>

      <v-tabs-items>

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

        <!--<v-parallax :src='this.backendUrl + this.course.image_url'></v-parallax>-->

        <div v-show="!loading">
          <!--video lecture-->
          <v-tab-item>
            <v-container style="width: 670px">
              <video-player class="vjs-custom-skin"
                            ref="videoPlayer"
                            :options="playerOptions"
                            :playsinline="true"
                            @ready="playerReady">
              </video-player>
            </v-container>
          </v-tab-item>

          <!--document-->
          <v-tab-item>
            <v-container>
              <embed :src='documentUrl'
                     width="100%"
                     height="550px"
                     type="application/pdf"/>
            </v-container>
          </v-tab-item>
        </div>
      </v-tabs-items>
    </v-tabs>

    <span style="white-space: pre-wrap;">{{course.description}}</span>

    <div v-show="!loading">
      <!--right sidebar menu-->
      <v-navigation-drawer app right clipped class="rightMenu" width="190">
        <v-list>
          <v-list-tile v-for="(section,index) in course.sections" :key="section.id"
                       @click="activeSection = index"
                       :class="{rightMenuSelected: activeSection === index}"
          >
            <v-list-tile-title class="pl-5">Section {{index + 1}}</v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-navigation-drawer>
    </div>
  </div>
</template>

<script>
  import {mapGetters} from 'vuex';
  import config from '@/config';

  import 'video.js/dist/video-js.css';
  import {videoPlayer} from 'vue-video-player';

  export default {
    name: 'Course',

    components: {
      videoPlayer
    },

    data() {
      return {
        backendUrl: config.backendUrl,
        documentUrl: null,
        activeSection: 0,

        loading: true,

        // videojs options
        playerOptions: {
          height: '360',
          autoplay: false,
          muted: true,
          language: 'en',
          playbackRates: [0.5, 0.75, 1.0, 1.5, 1.75, 2.0],
          sources: [{
            type: "video/mp4",
            src: ''
          }],
          poster: '',
        }
      }
    },

    computed: {
      ...mapGetters({
        course: 'selectedCourse'
      }),

      player() {
        return this.$refs.videoPlayer.player
      }
    },

    watch: {
      activeSection: function (newValue, oldValue) {
        this.$set(this.playerOptions.sources, 0, {
          type: "video/mp4",
          src: this.backendUrl + this.course.sections[newValue].videos[0].url,
        })
        this.documentUrl = this.backendUrl + this.course.sections[newValue].documents[0].url;
      }
    },

    created() {
      // clear cached selected course if the new selected course is different
      if (this.$store.getters.selectedCourse.id != this.$route.params.id) {
        this.$store.dispatch('clearSelectedCourse');
      }

      // get the new selected course
      this.$store.dispatch('getSelectedCourse', this.$route.params.id)
        .then(() => {
          this.loading = false;

          //this.playerOptions.poster = this.backendUrl + this.course.image_url;

          this.$set(this.playerOptions.sources, 0, {
            type: "video/mp4",
            src: this.backendUrl + this.course.sections[0].videos[0].url,
          })

          this.documentUrl = this.backendUrl + this.course.sections[0].documents[0].url;
        })
        .catch(err => console.log(err))

    },

    methods: {
      playerReady(player) {
        player.currentTime(1);
      }
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .rightMenuSelected {
    background-color: #92cbdb;
  }
</style>
