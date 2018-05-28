<template>
  <div>

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

    <v-container v-show="!loading">
      <v-layout row>
        <v-flex xs12 sm11 md9 class="mx-auto">
          <v-card>

            <v-card-media :src="backendUrl + course.image_url" height="400"></v-card-media>

            <v-card-title>

              <!--course name-->
              <div class="display-1">{{course.name}}</div>

              <v-spacer></v-spacer>

              <!--social media sharing buttons-->
              <social-sharing :url="shareUrl" inline-template>
                <v-speed-dial
                  v-model="socialMediaButtonFab"
                  direction="left"
                  transition="slide-y-reverse-transition"
                >
                  <v-btn
                    slot="activator"
                    v-model="socialMediaButtonFab"
                    color="accent" dark small fab
                  >
                    <v-icon>share</v-icon>
                    <v-icon>close</v-icon>
                  </v-btn>
                  <network network="facebook">
                    <v-btn fab dark small color="blue darken-1">
                      <i class="fab fa-facebook"></i>
                    </v-btn>
                  </network>
                  <network network="googleplus">
                    <v-btn fab dark small color="red lighten-1">
                      <i class="fab fa-google-plus"></i>
                    </v-btn>
                  </network>
                  <network network="linkedin">
                    <v-btn fab dark small color="blue lighten-1">
                      <i class="fab fa-linkedin"></i>
                    </v-btn>
                  </network>
                </v-speed-dial>
              </social-sharing>

              <!--course price-->
              <div v-show="!course.isEnrolled" class="title">({{course.price}}$)</div>

              <!--start/enroll/purchase button-->
              <v-btn v-if="parseInt(this.course.price) === 0" color="success" large @click="onEnrollButtonClicked">
                <v-icon left v-show="course.isEnrolled">launch</v-icon>
                {{course.isEnrolled ? 'Start' : 'Enroll'}}
              </v-btn>

              <v-btn v-else color="success" large @click="checkout">
                Purchase
              </v-btn>
            </v-card-title>

            <v-tabs height="60%" fixed-tabs grow>
              <v-tab>
                <v-icon class="pr-2" medium color="primary">description</v-icon>
                Overview
              </v-tab>
              <v-tab>
                <v-icon class="pr-2" medium color="primary">dashboard</v-icon>
                Sections
              </v-tab>
              <v-tab>
                <v-icon class="pr-2" medium color="primary">person</v-icon>
                Teacher
              </v-tab>
              <v-tab>
                <v-icon class="pr-2" medium color="primary">grade</v-icon>
                Reviews
              </v-tab>
              <v-tab>
                <v-icon class="pr-2" medium color="primary">help</v-icon>
                FAQ
              </v-tab>

              <v-tabs-items>

                <!--overview-->
                <v-tab-item>
                  <v-divider></v-divider>
                  <v-card-text>
                    <span style="white-space: pre-wrap;">{{course.description}}</span>
                  </v-card-text>
                </v-tab-item>

                <!--sections-->
                <v-tab-item>
                  <v-divider></v-divider>
                  <v-expansion-panel expand>
                    <v-expansion-panel-content v-for="(section,i) in course.sections" :key="i">
                      <div slot="header">{{section.name}}</div>
                      <v-card>
                        <v-card-text class="grey lighten-3">
                          <span style="white-space: pre-wrap;">{{section.description}}</span>
                        </v-card-text>
                      </v-card>
                    </v-expansion-panel-content>
                  </v-expansion-panel>
                </v-tab-item>

                <!--teacher-->
                <v-tab-item>
                  <v-divider></v-divider>
                  <v-card>
                    <v-card-title>
                      <avatar
                        :username="teacherName"
                        :src="teacherAvatarUrl"
                        :size="48"
                        color="#fff"
                      ></avatar>
                      <span class="title ml-3">
                        {{teacherName}}
                      </span>
                    </v-card-title>
                    <v-card-text v-show="course.teacher && course.teacher.about_me">
                      <span style="white-space: pre-wrap;">{{course.teacher && course.teacher.about_me}}</span>
                    </v-card-text>
                    <v-card-actions>
                      <v-btn flat color="secondary" :to="teacherProfileUrl">View Profile</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-tab-item>

                <!--reviews-->
                <v-tab-item>
                  <v-divider></v-divider>

                  <v-container>
                    <v-layout v-show="!course.isReviewedByUser" row>
                      <v-flex class="text-xs-right">
                        <v-btn color="accent lighten" @click="showReviewWindow = !showReviewWindow">
                          Review
                        </v-btn>
                      </v-flex>
                    </v-layout>

                    <v-layout row v-for="(review, i) in course.reviews" :key="i">
                      <v-flex>
                        <v-card flat>
                          <v-card-title>
                            <avatar
                              :username="review.user.first_name + ' ' + review.user.last_name"
                              :src="review.user.image_url ? backendUrl + review.user.image_url : ''"
                              :size="48"
                            ></avatar>
                            <span class="title ml-3">
                        {{review.user.first_name}} {{review.user.last_name}}
                      </span>
                          </v-card-title>
                          <v-card-text>
                            <star-rating
                              :increment="0.5"
                              :star-size="30"
                              :glow="3"
                              :rating="parseInt(review.score)"
                              read-only
                            ></star-rating>
                          </v-card-text>
                          <v-card-text>
                            {{review.text}}
                          </v-card-text>

                          <v-divider></v-divider>
                        </v-card>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-tab-item>

                <!--faq-->
                <v-tab-item>

                </v-tab-item>

                <!--test-->
              </v-tabs-items>
            </v-tabs>
          </v-card>

        </v-flex>
      </v-layout>

    </v-container>

    <!--enroll confirmation window-->
    <v-dialog v-model="showEnrollModal" max-width="390">
      <v-card>
        <v-card-title class="headline">Are you sure you want to enroll in this course?</v-card-title>
        <v-card-text>This course is FREE so you will be able to start learning immediately!</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="success" flat="flat" @click.native="showEnrollModal = false">Cancel</v-btn>
          <v-btn color="success" flat="flat" @click.native="onEnroll">Enroll</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!--review window-->
    <v-dialog v-model="showReviewWindow" max-width="500" scrollable>
      <v-card>
        <v-card-title class="headline">What did you think about this course?</v-card-title>
        <form @submit.prevent="onReview">
          <v-container>
            <star-rating
              :increment="0.5"
              :star-size="40"
              :glow="5"
              v-model="reviewScore"
            >
            </star-rating>
            <v-text-field
              class="mt-3"
              name="reviewText"
              label="Type here"
              :rules="[(v) => v.length <= 255 || 'Max 255 characters']"
              :counter="255"
              v-model="reviewText"
              multi-line
              required
            ></v-text-field>
          </v-container>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="success" flat="flat" @click.native="showReviewWindow = false">Cancel</v-btn>
            <v-btn type="submit" color="success" flat="flat">Review</v-btn>
          </v-card-actions>
        </form>
      </v-card>
    </v-dialog>

  </div>
</template>

<script>
  import {mapGetters} from 'vuex';
  import {backendUrl} from '@/config';
  import StarRating from 'vue-star-rating';
  import Avatar from 'vue-avatar';

  export default {
    name: 'CourseDetails',

    components: {
      StarRating,
      Avatar
    },

    data() {
      return {
        backendUrl: backendUrl,
        loading: true,
        showEnrollModal: false,

        showReviewWindow: false,
        reviewText: '',
        reviewScore: 0,

        socialMediaButtonFab: false,

      }
    },

    computed: {
      ...mapGetters({
        course: 'selectedCourse',
        isAuthenticated: 'isAuthenticated'
      }),
      teacherName() {
        if (this.course.teacher) {
          return this.course.teacher.first_name + ' ' + this.course.teacher.last_name;
        }
        return '';
      },
      teacherAvatarUrl() {
        if (this.course.teacher) {
          return this.course.teacher.image_url ? backendUrl + this.course.teacher.image_url : '';
        }
      },
      teacherProfileUrl() {
        if (this.course.teacher) {
          return '/users/' + this.course.teacher.id;
        }
      },
      shareUrl() {
        return 'localhost:9999' + this.$router.currentRoute.fullPath;
      }
    },

    watch: {},

    created() {
      // get the new selected course
      this.$store.dispatch('getSelectedCourse', this.$route.params.id)
        .then(() => {
          this.loading = false;
        })
        .catch(err => console.log(err))
    },

    methods: {

      /**
       * User clicks Enroll button -> opens enrollment window.
       */
      onEnrollButtonClicked() {
        if (!this.isAuthenticated) {
          this.$router.push('/login');
          return;
        }

        if (this.course.isEnrolled) {
          this.$router.push('/courses/' + this.course.id);
          return;
        }

        this.showEnrollModal = true;
      },

      /**
       * User enrolls in a course.
       */
      onEnroll() {
        this.$store.dispatch('enroll', this.course.id)
          .then(res => {
            this.$router.push('/courses/' + this.course.id);
            this.showEnrollModal = false;
          })
          .catch(err => console.log(err))
      },

      /**
       * User reviews a course.
       */
      onReview() {
        let payload = {
          courseId: this.course.id,
          score: this.reviewScore,
          text: this.reviewText
        }

        this.$store.dispatch('reviewCourse', payload)
          .then(res => {
            this.showReviewWindow = false;
            this.showSnackbar('You have successfully reviewed this course!', 'success', true, true);
          })
          .catch(err => console.log(err))
      },

      /**
       * User purchases a course.
       */
      checkout() {
        this.$checkout.open({
          name: this.course.name,
          description: this.course.description && this.course.description.substring(0, 50),
          currency: 'USD',
          amount: parseInt(this.course.price) * 100,
          image: this.backendUrl + this.course.image_url,
          token: (token) => {
            console.log(token);
            this.token = token;
          }
        });
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
</style>
