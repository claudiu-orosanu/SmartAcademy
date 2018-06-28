<template>
  <div>
    <v-container>
      <v-layout row>
        <v-flex xs12 class="text-xs-center">
          <div class="display-1">Create your course</div>
        </v-flex>
      </v-layout>

      <v-layout row>
        <v-flex xs12>
          <form @submit.prevent="onCreateCourse">

            <!--name-->
            <v-layout row class="mt-3">
              <v-flex xs12 sm10 offset-sm1>
                <v-text-field
                  name="name"
                  label="Name"
                  :rules="[(v) => v.length <= 50 || 'Max 50 characters']"
                  :counter="50"
                  v-model="name"
                  required
                ></v-text-field>
              </v-flex>
            </v-layout>

            <!--category-->
            <v-layout row class="mt-3">
              <v-flex xs12 sm10 offset-sm1>
                <v-select
                  name="category"
                  label="Category"
                  :items="categories"
                  required
                  v-model="selectedCategory"
                ></v-select>
              </v-flex>
            </v-layout>

            <!--price-->
            <v-layout row class="mt-3">
              <v-flex xs12 sm10 offset-sm1>
                <v-text-field
                  name="price"
                  label="Price"
                  type="number"
                  min="0"
                  max="1000000"
                  step="0.01"
                  v-model="price"
                  suffix="$"
                  required
                ></v-text-field>
              </v-flex>
            </v-layout>

            <!--description-->
            <v-layout row class="mt-3">
              <v-flex xs12 sm10 offset-sm1>
                <v-text-field
                  name="description"
                  label="Description"
                  :rules="[(v) => v.length <= 2048 || 'Max 2048 characters']"
                  :counter="2048"
                  v-model="description"
                  textarea clearable
                  required
                ></v-text-field>
              </v-flex>
            </v-layout>

            <!--image-->
            <v-layout row class="mt-3">
              <v-flex xs12 sm10 offset-sm1>
                <v-btn style="width: 120px; margin-left: 0px;" @click="$refs.imageUploadInput.click()">Upload Image</v-btn>
                <input type="file" style="display: none" ref="imageUploadInput" @change="onImageSelected" accept="image/*">
                <img :src="imagePreview" width="400" class="d-block">
              </v-flex>
            </v-layout>

            <!--sections subtitle-->
            <v-layout row class="mt-5">
              <v-flex xs12 sm10 offset-sm1 offset-lg1>
                <div class="headline">Sections</div>
              </v-flex>
            </v-layout>

            <!--sections-->
            <v-layout row class="mt-3">
              <v-flex xs12 sm10 offset-sm1>
                <v-card class="mb-3">
                  <v-card-text>
                    <v-text-field
                      label="Number of sections"
                      :value="sections"
                      @input="onSectionsNumberChanged"
                      min="1"
                      :max="courseSectionsMax"
                      hint="A course can have a maximum of 12 sections"
                      type="number"
                      persistent-hint
                    ></v-text-field>
                  </v-card-text>
                </v-card>
                <v-stepper v-model="currentSection" alt-labels>
                  <v-stepper-header>
                    <template v-for="n in sections">
                      <v-stepper-step
                        :key="`${n}-step`"
                        :step="n"
                        :complete="currentSection > n"
                        editable
                      >
                        Section {{ n }}
                      </v-stepper-step>
                      <v-divider v-if="n !== sections" :key="n"></v-divider>
                    </template>
                  </v-stepper-header>
                  <v-stepper-items>
                    <v-stepper-content
                      :step="n"
                      v-for="n in sections"
                      :key="`${n}-content`"
                    >
                      <v-container class="mb-1">

                        <!--section name-->
                        <v-layout row wrap>
                          <v-flex xs12 class="pl-2">
                            <v-text-field
                              label="Section name"
                              v-model="sectionsNames[n]"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>

                        <!--section description-->
                        <v-layout row wrap>
                          <v-flex xs12 class="pl-2">
                            <v-text-field label="Section description"
                                          :rules="[(v) => v.length <= 512 || 'Max 512 characters']"
                                          :counter="512"
                                          multi-line clearable
                                          v-model="sectionsDescriptions[n]"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>

                        <!--section video-->
                        <v-layout row wrap>
                          <v-flex sm4 lg3>
                            <v-btn style="width: 120px" @click="$refs.videoUploadInput[0].click()">Upload Video</v-btn>
                          </v-flex>
                          <v-flex>
                            <input type="file" style="display: none" ref="videoUploadInput" @change="onVideoSelected">
                            <v-text-field disabled v-model="sectionsVideo[n].name"></v-text-field>
                          </v-flex>
                        </v-layout>

                        <!--section document-->
                        <v-layout row wrap>
                          <v-flex sm4 lg3>
                            <v-btn style="width: 120px" @click="$refs.pdfUploadInput[0].click()">Upload PDF</v-btn>
                          </v-flex>
                          <v-flex>
                            <input type="file" style="display: none" ref="pdfUploadInput" @change="onPdfSelected">
                            <v-text-field disabled v-model="sectionsPdf[n].name"></v-text-field>
                          </v-flex>
                        </v-layout>

                        <!--section test-->
                        <v-layout row wrap>
                          <v-flex>
                            <v-btn @click="showCreateTestWindow = true">Create section test</v-btn>
                            <v-icon v-show="sectionsTests[currentSection]" color="success">done</v-icon>
                          </v-flex>
                        </v-layout>
                      </v-container>

                      <v-btn color="primary" :disabled="n === sections" @click="goToSection(n)">Next</v-btn>
                      <v-btn flat :disabled="n === 1" @click="onBackButtonClicked">Back</v-btn>
                    </v-stepper-content>
                  </v-stepper-items>
                </v-stepper>
              </v-flex>
            </v-layout>

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

            <!--Create button-->
            <v-layout row class="mt-4">
              <v-flex class="text-xs-center">
                <v-btn
                  :loading="creatingCourse"
                  :disabled="creatingCourse"
                  class="secondary"
                  type="submit">Create course</v-btn>
              </v-flex>
            </v-layout>

            <!--Create section test window-->
            <create-test-window
              :section="currentSection"
              v-model="showCreateTestWindow"
              @testCreated="onSectionTestCreated"
              @close="showCreateTestWindow = false"
            ></create-test-window>

          </form>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script>
  import constants from '@/constants';
  import createTestWindow from './CreateTestWindow'

  export default {
    name: 'CreateCourse',

    components: {
      'create-test-window': createTestWindow
    },

    data() {
      return {
        courseSectionsMax: 12,

        currentSection: 1,
        categories: constants.courseCategories,

        name: '',
        description: '',
        selectedCategory: '',
        price: '',
        image: '',
        imagePreview: '',

        sections: 1,
        sectionsNames: [],
        sectionsDescriptions: [],
        sectionsVideo: [],
        sectionsPdf: [],
        sectionsTests: [],

        showCreateTestWindow: false,

        errors: [],
        creatingCourse: false
      }
    },

    computed: {

    },

    watch: {
      sections (val) {
        if (this.currentSection > val) {
          this.sectionsNames[this.currentSection] = '';
          this.sectionsDescriptions[this.currentSection] = '';
          this.sectionsPdf[this.currentSection] = '';
          this.sectionsVideo[this.currentSection] = '';
          this.sectionsTests[this.currentSection] = '';
          this.currentSection = val;
        }
      }
    },

    methods: {

      /**
       * Called when user clicks Create Course button
       *
       */
      onCreateCourse() {
        let course = {
          name: this.name,
          description: this.description,
          category: this.selectedCategory,
          price: this.price,
          image: this.image,
          sectionsData: {}
        };

        for(let i = 1; i <= this.sections; i++) {
          course.sectionsData[i] = {};
          course.sectionsData[i].name = this.sectionsNames[i];
          course.sectionsData[i].description = this.sectionsDescriptions[i];
          course.sectionsData[i].video = this.sectionsVideo[i];
          course.sectionsData[i].pdf = this.sectionsPdf[i];
          course.sectionsData[i].exam = this.sectionsTests[i];

          if(course.sectionsData[i].video === '' || course.sectionsData[i].pdf === '' || course.sectionsData[i].exam === '') {
            this.showSnackbar('All course sections must be completed!', 'error', true, false);
            return;
          }
        }

        this.creatingCourse = true;

        this.$store.dispatch('createCourse', course)
          .then(response => {
            this.creatingCourse = false;
            this.showSnackbar('Course created', 'success', true, true);
            this.errors = [];
          })
          .catch(err => {
            this.errors = err;
          });
      },

      /**
       * Called when user has selected an image for upload
       *
       * @param event
       */
      onImageSelected(event) {
        if(!event.target.files.length) {
          return;
        }

        let validExtensions = ['png', 'PNG', 'jpg', 'jpeg'];
        let file = event.target.files[0];
        let fileNameTokens = file.name.split('.');
        if(fileNameTokens.length < 2 || validExtensions.indexOf(fileNameTokens[1]) == -1) {
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
       * Called when user has selected a video for upload
       *
       * @param event
       */
      onVideoSelected(event) {
        if(!event.target.files.length) {
          return;
        }

        let validExtensions = ['mp4', 'mpeg'];
        let file = event.target.files[0];
        let fileNameTokens = file.name.split('.');
        if(fileNameTokens.length < 2 || validExtensions.indexOf(fileNameTokens[1]) == -1) {
          this.showSnackbar('Please choose a valid video file!', 'error', true, false);
          return;
        }

        this.$set(this.sectionsVideo, this.currentSection, event.target.files[0]);
      },

      /**
       * Called when user has selected a pdf for upload
       *
       * @param event
       */
      onPdfSelected(event) {
        if(!event.target.files.length) {
          return;
        }

        let file = event.target.files[0];
        let fileNameTokens = file.name.split('.');
        if(fileNameTokens.length < 2 || fileNameTokens[1] !== 'pdf') {
          this.showSnackbar('Please choose a valid pdf file!', 'error', true, false);
          return;
        }

        this.$set(this.sectionsPdf, this.currentSection, event.target.files[0]);
      },

      /**
       * Called when user creates a section test
       *
       * @param event
       */
      onSectionTestCreated(event) {
        this.sectionsTests[this.currentSection] = event;
      },


      /**
       * Handle section stepper component logic
       *
       * @param event
       */
      onSectionsNumberChanged (val) {
        this.sections = parseInt(val)
      },
      goToSection (n) {
        if (n !== this.sections) {
          this.currentSection = n + 1
        }
      },
      onBackButtonClicked() {
        if(this.currentSection > 1) {
          this.currentSection -= 1;
        }
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

    created() {
      // scroll to top
      window.scrollTo(0, 0);

      for(let i = 1; i <= this.courseSectionsMax; i++) {
        this.sectionsNames[i] = '';
        this.sectionsDescriptions[i] = '';
        this.sectionsVideo[i] = '';
        this.sectionsPdf[i] = '';
        this.sectionsTests[i] = '';
      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
