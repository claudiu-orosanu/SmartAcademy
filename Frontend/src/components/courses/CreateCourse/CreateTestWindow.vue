<template>
  <v-dialog
    :value="value" persistent max-width="850px">

    <v-card tile>

      <v-toolbar card dark color="primary">
        <v-btn icon dark @click.native="closeWindow">
          <v-icon>close</v-icon>
        </v-btn>
        <v-toolbar-title>Section {{section}} - Create test</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-btn dark flat @click.native="createSectionTest">Save</v-btn>
        </v-toolbar-items>
      </v-toolbar>

      <v-card-text>
        <v-container>
          <v-form>

            <v-layout row>
              <v-flex>
                <v-text-field
                  label="How many questions?"
                  :value="questionsTotal"
                  @input="onQuestionsTotalChanged"
                  min="0"
                  max="100"
                  type="number"
                  required
                ></v-text-field>
              </v-flex>
            </v-layout>

            <div v-for="i in questionsTotal" class="mt-5">
              <v-layout row>
                <v-flex>
                  <div class="title text-xs-center">Question {{i}}</div>
                </v-flex>
              </v-layout>

              <v-layout row>
                <v-flex>
                  <v-text-field
                    label="Text"
                    v-model="questions[i].text"
                  ></v-text-field>
                </v-flex>
              </v-layout>

              <v-layout row>
                <v-flex>
                  <v-text-field
                    label="How many answers for this question?"
                    min="1"
                    max="20"
                    type="number"
                    :value="questions[i].answersTotal"
                    @input="onAnswersTotalChanged($event,i)"
                  ></v-text-field>
                </v-flex>
              </v-layout>

              <div>
                <v-radio-group v-model="questions[i].correctAnswer">
                  <v-layout row v-for="answer in questions[i].answersTotal" :key="answer" class="pb-2">
                    <v-flex sm1>
                      <v-radio :value="answer" color="success" style="top:15px"></v-radio>
                    </v-flex>
                    <v-flex>
                      <v-text-field class="pl-2"
                        v-model="questions[i].answers[answer].text"
                        style="border: 1px solid gray">
                      </v-text-field>
                    </v-flex>
                  </v-layout>
                </v-radio-group>
              </div>
            </div>
          </v-form>

        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" flat @click.native="closeWindow">Close</v-btn>
        <v-btn color="blue darken-1" flat @click.native="createSectionTest">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

</template>

<script>

  export default {
    name: 'CreateTestWindow',

    props: [
      'value',
      'section'
    ],

    data() {
      return {
        questionsTotal: '',
        questions: {},
      }
    },

    computed: {

    },

    watch: {

    },

    methods: {

      /**
       * Called when user changes total number of questions
       *
       */
      onQuestionsTotalChanged(value) {
        let maxQuestions = 100;

        if(value > maxQuestions)
          return;

        this.questionsTotal = parseInt(value);
        let answersTotal = 4;

        for(let i = 1; i <= this.questionsTotal; i++) {

          if(!this.questions[i]) {
            this.$set(this.questions, i, {
              text: '',
              answersTotal: answersTotal,
              correctAnswer: 1,
              answers: {}
            });
          }

          for(let j = 1; j <= answersTotal; j++) {
            if(!this.questions[i].answers[j]) {
              this.$set(this.questions[i].answers, j, {
                text: ''
              });
            }
          }
        }

        for(let i = this.questionsTotal+1; i <= maxQuestions; i++) {
          if(typeof this.questions[i] === 'object') {
            delete this.questions[i];
          }
        }
      },

      /**
       * Called when user changes total number of answers for a question
       *
       */
      onAnswersTotalChanged(value, question) {
        let maxAnswers = 20;

        if(value > maxAnswers)
          return;

        let total = parseInt(value);
        this.questions[question].answersTotal = total;

        for(let i = 1; i <= total; i++) {
          if(!this.questions[question].answers[i]) {
            this.questions[question].answers[i] = {
              text: ''
            }
          }
        }

        for(let i = total+1; i <= maxAnswers; i++) {
          if(typeof this.questions[question].answers[i] === 'object') {
            delete this.questions[question].answers[i];
          }
        }
      },

      /**
       * Called when user clicks 'Save' button in create section test window
       *
       */
      createSectionTest() {
        let test = {
          questionsTotal: this.questionsTotal,
          questions: this.questions
        };

        this.questionsTotal = '';
        this.questions = {};

        this.$emit('testCreated', test);
        this.closeWindow();
      },

      /**
       * Closes this window
       *
       */
      closeWindow() {
        this.$emit('close');
      },
    },

    created() {

    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
