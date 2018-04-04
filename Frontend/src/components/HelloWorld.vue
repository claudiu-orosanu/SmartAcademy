<template>
  <div>

    <ul class="flex-container">
      <li v-for="number in numbers" class="flex-item">{{number}}</li>
    </ul>


    <v-container>
      <v-layout>
        <v-flex xs12 sm6 offset-sm3>
          <v-toolbar color="indigo" dark>
            <v-toolbar-side-icon></v-toolbar-side-icon>
            <v-toolbar-title>Explore</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon>
              <v-icon>search</v-icon>
            </v-btn>
          </v-toolbar>
          <v-card>
            <v-container fluid grid-list-md>
              <v-layout row wrap>
                <v-flex
                  v-bind="{ [`xs${card.flex}`]: true }"
                  v-for="card in cards"
                  :key="card.title"
                >
                  <v-card>
                    <v-card-media
                      :src="card.src"
                      height="200px"
                    >
                      <v-container fill-height fluid>
                        <v-layout fill-height>
                          <v-flex xs12 align-end flexbox>
                            <span class="headline white--text " v-text="card.title" style="background-color: rgba(41,13,12,0.55);"/>
                          </v-flex>
                        </v-layout>
                      </v-container>
                    </v-card-media>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn icon>
                        <v-icon>favorite</v-icon>
                      </v-btn>
                      <v-btn icon>
                        <v-icon>bookmark</v-icon>
                      </v-btn>
                      <v-btn icon>
                        <v-icon>share</v-icon>
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>

    <v-container style="height: 150px;">
      <v-layout class="row wrap justify-center mb-1">
        <v-flex sm3 style="background-color: aqua;">{{msg}}</v-flex>
        <v-flex sm3 style="background-color: red">{{msg}}</v-flex>
        <v-flex sm3 style="background-color: forestgreen">{{msg}}</v-flex>
      </v-layout>
      <v-layout class="row wrap">
        <v-flex sm3 style="background-color: aqua">{{msg}}</v-flex>
        <v-flex sm3 style="background-color: red">{{msg}}</v-flex>
        <v-flex sm3 style="background-color: forestgreen">{{msg}}</v-flex>
      </v-layout>
    </v-container>

    <v-container grid-list-md text-xs-center align-center>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card dark color="primary">
            <v-card-text class="px-0"><p><var>v</var> = <var>u</var> * <var>e</var></p></v-card-text>
          </v-card>
        </v-flex>
        <v-flex xs6 v-for="i in 2" :key="`6${i}`">
          <v-card dark color="secondary">
            <v-card-text class="px-0"><code>6</code></v-card-text>
          </v-card>
        </v-flex>
        <v-flex xs4 v-for="i in 3" :key="`4${i}`">
          <v-card dark color="primary">
            <v-card-text class="px-0">4</v-card-text>
          </v-card>
        </v-flex>
        <v-flex xs3 v-for="i in 4" :key="`3${i}`">
          <v-card dark color="secondary">
            <v-card-text class="px-0">3</v-card-text>
          </v-card>
        </v-flex>
        <v-flex xs2 v-for="i in 6" :key="`2${i}`">
          <v-card dark color="primary">
            <v-card-text class="px-0">2</v-card-text>
          </v-card>
        </v-flex>
        <v-flex xs1 v-for="i in 12" :key="`1${i}`">
          <v-card dark color="secondary">
            <v-card-text class="px-0">1</v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>

    <v-container fluid>
      <v-slide-y-transition mode="out-in">
        <v-layout column align-center>
          <img src="@/assets/logo.png" alt="Vuetify.js" class="mb-5">
          <blockquote>
            &#8220;First, solve the problem. Then, write the code.&#8221;
            <footer>
              <small>
                <em>&mdash;John Johnson</em>
              </small>
            </footer>
          </blockquote>
        </v-layout>
      </v-slide-y-transition>
    </v-container>
  </div>
</template>

<script>
  import axios from 'axios';

  export default {
    name: 'HelloWorld',
    data() {
      return {
        msg: 'Welcome my fellow human!',
        numbers: [],

        cards: [
          { title: 'Pre-fab homes', src: 'https://static.pexels.com/photos/236047/pexels-photo-236047.jpeg', flex: 12 },
          { title: 'Favorite road trips', src: 'https://static.pexels.com/photos/355296/pexels-photo-355296.jpeg', flex: 6 },
          { title: 'Best airlines', src: 'https://static.pexels.com/photos/68147/waterfall-thac-dray-nur-buon-me-thuot-daklak-68147.jpeg', flex: 6 }
        ]
      }
    },

    created() {
      axios.get('http://smart-academy.test/api/v1/test')
        .then(response => {
          this.numbers = response.data;
        })
        .catch(err => {
          console.log(err);
        })
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  h1, h2 {
    font-weight: normal;
  }
  ul {
    list-style-type: none;
    padding: 0;
  }
  li {
    display: inline-block;
    margin: 0 10px;
  }
  a {
    color: #42b983;
  }

  .parent {
    display: flex;
    height: 300px; /* Or whatever */
  }

  .child {
    width: 100px;  /* Or whatever */
    height: 100px; /* Or whatever */
    margin: auto;  /* Magic! */
  }

  .flex-container {
    padding: 0;
    margin: 0;
    list-style: none;
    display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-flex-flow: row wrap;
    justify-content: space-around;
  }

  .flex-item {
    background: tomato;
    padding: 5px;
    width: 200px;
    height: 150px;
    margin-top: 10px;
    line-height: 150px;
    color: white;
    font-weight: bold;
    font-size: 3em;
    text-align: center;
  }
</style>
