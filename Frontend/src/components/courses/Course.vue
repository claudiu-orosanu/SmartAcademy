<template>
  <div>

    <v-tabs v-show="!isFinalExam" height="73%" fixed-tabs grow>
      <v-tab>
        <v-icon class="pr-2" medium color="primary">video_library</v-icon>
        Video Lecture
      </v-tab>
      <v-tab>
        <v-icon class="pr-2" medium color="primary">library_books</v-icon>
        Document
      </v-tab>
      <v-tab>
        <v-icon class="pr-2" medium color="primary">assignment</v-icon>
        Test
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
          <v-tab-item style="overflow-x: auto">
            <v-container style="width: 935px" class="mt-4">
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

          <!--test-->
          <v-tab-item>

            <v-alert :value="score !== ''" :type="score >=  0.5 ? 'success' : 'error'">
              {{testResultsText}}
            </v-alert>

            <v-container>
              <v-layout row>
                <v-flex xs10 class="mx-auto">

                  <v-card v-for="(question, index) in exam.questions" :key="index" class="mb-3">
                    <v-card-title class="rightMenuSelected">
                      <div class="title">{{index+1}}. {{question.text}}</div>
                    </v-card-title>

                    <v-divider></v-divider>

                    <v-card-text>
                      <v-radio-group v-model="testData[question.id]">
                        <v-radio v-for="answer in exam.questions[index].answers" :key="answer.text"
                                 :label="answer.text"
                                 :value="answer.id"
                                 :color="answerColor(question.id)"
                                 class="mb-1 ml-3"
                        ></v-radio>
                      </v-radio-group>


                      <p v-show="Object.keys(examResults).length != 0"
                         class="subheading"
                      >{{answerFeedback(question.id)}}</p>

                    </v-card-text>
                  </v-card>

                </v-flex>
              </v-layout>
            </v-container>

            <!--Submit test button-->
            <v-layout row class="mt-2">
              <v-flex class="text-xs-center">
                <v-btn
                  :loading="loadingResults"
                  :disabled="loadingResults"
                  class="secondary"
                  @click="onSubmitTest"
                >Submit test
                </v-btn>
              </v-flex>
            </v-layout>

          </v-tab-item>

        </div>
      </v-tabs-items>
    </v-tabs>

    <!--FINAL EXAM-->
    <div v-show="isFinalExam">
      <v-alert :value="score !== ''" :type="score >=  0.5 ? 'success' : 'error'">
        {{testResultsText}}
      </v-alert>

      <v-container>

        <v-layout row class="mb-3">
          <v-flex class="text-xs-center">
            <div class="display-1">Final Exam</div>
          </v-flex>
        </v-layout>

        <v-layout row>
          <v-flex xs10 class="mx-auto">

            <v-card v-for="(question, index) in exam.questions" :key="index" class="mb-3">
              <v-card-title class="rightMenuSelected">
                <div class="title">{{index+1}}. {{question.text}}</div>
              </v-card-title>

              <v-divider></v-divider>

              <v-card-text>
                <v-radio-group v-model="testData[question.id]">
                  <v-radio v-for="answer in exam.questions[index].answers" :key="answer.text"
                           :label="answer.text"
                           :value="answer.id"
                           :color="answerColor(question.id)"
                           class="mb-1 ml-3"
                  ></v-radio>
                </v-radio-group>


                <p v-show="Object.keys(examResults).length != 0"
                   class="subheading"
                >{{answerFeedback(question.id)}}</p>

              </v-card-text>
            </v-card>

          </v-flex>
        </v-layout>
      </v-container>

      <!--Submit test button-->
      <v-layout row class="mt-2">
        <v-flex class="text-xs-center">
          <v-btn
            :loading="loadingResults"
            :disabled="loadingResults"
            class="secondary"
            @click="onSubmitTest"
          >Submit exam
          </v-btn>
        </v-flex>
      </v-layout>
    </div>

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
          <v-list-tile @click="activeSection = -1" :class="{rightMenuSelected: activeSection === -1}">
            <v-list-tile-title class="pl-5">Final Exam</v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-navigation-drawer>
    </div>

  </div>
</template>

<script>
  import {mapGetters} from 'vuex';
  import {backendUrl} from '@/config';

  import 'video.js/dist/video-js.css';
  import {videoPlayer} from 'vue-video-player';

  export default {
    name: 'Course',

    components: {
      videoPlayer
    },

    data() {
      return {
        backendUrl: backendUrl,
        documentUrl: null,
        exam: [],
        activeSection: 0,

        isFinalExam: false,
        testData: {},
        examResults: {},
        score: '',

        loading: true,
        loadingResults: false,

        // videojs options
        playerOptions: {
          height: '510',
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
      },

      testResultsText() {
        let text;

        if (this.score >= 0.5) {
          text = this.isFinalExam ? 'Congratulations! You graduated this course!' : 'Congratulations! You passed this test!';
        }
        else {
          text = this.isFinalExam ? 'Unfortunately, you did not pass the final exam. Study more and try again!' : 'Unfortunately, you did not pass the test. Study more and try again!';
        }

        text += ' (Your score: ' + this.score * 100 + ').';

        return text;
      }
    },

    watch: {
      activeSection: function (newValue, oldValue) {
        this.testData = {};
        this.examResults = {};
        this.score = '';

        if(newValue === -1) { // final exam
          this.exam = this.course.finalExam;
          this.isFinalExam = true;
          return;
        }

        this.isFinalExam = false;

        this.$set(this.playerOptions.sources, 0, {
          type: "video/mp4",
          src: this.backendUrl + this.course.sections[newValue].videos[0].url,
        })
        this.documentUrl = this.backendUrl + this.course.sections[newValue].documents[0].url;
        this.exam = this.course.sections[newValue].exams[0];
      }
    },

    created() {

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
          this.exam = this.course.sections[0].exams[0];
        })
        .catch(err => console.log(err))

    },

    methods: {
      playerReady(player) {
        player.currentTime(1);
      },

      answerColor: function(i) {
        let color = 'info';
        if(this.examResults[i] && this.examResults[i].correct) {
          color = 'success';
        }
        else if (this.examResults[i] && !this.examResults[i].correct) {
          color = 'error';
        }
        return color;
      },

      answerFeedback: function(i) {
        let text = 'info';
        if(this.examResults[i] && this.examResults[i].correct) {
          text = 'Your answer is correct!';
        }
        else if (this.examResults[i] && !this.examResults[i].correct) {
          text = 'Your answer is wrong!';

          if(this.isFinalExam) {
            let relatedSection = this.examResults[i].relatedSection;
            text += ' You should revisit Section ' + relatedSection.order_number + ' (' + relatedSection.name + '). ' +
              'You will find there the concepts related to this question.'
          }
        }
        return text;
      },

      /**
       * Handle test submit
       *
       */
      onSubmitTest() {
        // check if user has answered all questions
        if (Object.keys(this.testData).length != this.exam.questions.length) {
          this.showSnackbar('You must answer all the questions before submitting the test!', 'error', true, false);
          return;
        }

        this.loadingResults = true;

        let payload = {
          examId: this.exam.id,
          sectionNumber: this.activeSection + 1,
          courseId: this.$route.params.id,
          testData: this.testData
        }

        // send test data to backend to verify the answers
        this.$store.dispatch('submitTest', payload)
          .then(res => {
            this.examResults = res.data.testResults;
            this.score = res.data.score;
            this.loadingResults = false;

            // scroll to top
            window.scrollTo(0, 0);
          })
          .catch(err => console.log(err));
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
    },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .rightMenuSelected {
    background-color: #76b3d3;
  }
</style>
