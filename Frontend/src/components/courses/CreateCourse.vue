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
                  :rules="[(v) => v.length <= 255 || 'Max 255 characters']"
                  :counter="255"
                  v-model="description"
                  multi-line clearable
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
                        <v-layout row wrap>
                          <v-flex sm4 lg3>
                            <v-btn style="width: 120px" @click="$refs.videoUploadInput[0].click()">Upload Video</v-btn>
                          </v-flex>
                          <v-flex>
                            <input type="file" style="display: none" ref="videoUploadInput" @change="onVideoSelected">
                            <v-text-field disabled v-model="sectionsVideo[n].name"></v-text-field>
                          </v-flex>
                        </v-layout>

                        <v-layout row wrap>
                          <v-flex sm4 lg3>
                            <v-btn style="width: 120px" @click="$refs.pdfUploadInput[0].click()">Upload PDF</v-btn>
                          </v-flex>
                          <v-flex>
                            <input type="file" style="display: none" ref="pdfUploadInput" @change="onPdfSelected">
                            <v-text-field disabled v-model="sectionsPdf[n].name"></v-text-field>
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
                  class="secondary"
                  type="submit">Create course</v-btn>
              </v-flex>
            </v-layout>

          </form>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script>
  import constants from '@/constants';

  export default {
    name: 'CreateCourse',

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
        sectionsVideo: [],
        sectionsPdf: [],

        errors: [],
      }
    },

    computed: {

    },

    watch: {
      sections (val) {
        if (this.currentSection > val) {
          this.sectionsPdf[this.currentSection] = '';
          this.sectionsVideo[this.currentSection] = '';
          this.currentSection = val
        }
      }
    },

    methods: {
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
          course.sectionsData[i].video = this.sectionsVideo[i];
          course.sectionsData[i].pdf = this.sectionsPdf[i];

          if(course.sectionsData[i].video === '' || course.sectionsData[i].pdf === '') {
            this.showSnackbar('All course sections need to have data uploaded!', 'error', true, false);
            return;
          }
        }

        // TODO add the course exam

        this.$store.dispatch('createCourse', course)
          .then(response => {
            this.showSnackbar('Course created', 'success', true, true);
            this.errors = [];
          })
          .catch(err => {
            this.errors = err;
          });
      },
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
        this.sectionsVideo[i] = '';
        this.sectionsPdf[i] = '';
      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
