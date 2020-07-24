<template>
  <v-app-bar
      app
      flat
      color="white"
      class="px-5"
      height="80"
      light
    >

      <v-img :src="auth.avatar" max-height="60" max-width="60"/>
      <v-toolbar-title id="name" class="text-capitalize mx-2" style="font-size: 2rem; font-family: 'Indie Flower', cursive;">
          {{ auth.name }}
      </v-toolbar-title>

      <v-spacer></v-spacer>
      
      <v-toolbar-title class="mx-2" style="font-size: 2rem; font-family: 'Ranchers', cursive;">
          My
      </v-toolbar-title>

      <v-toolbar-title id="title" class="mx-2" style="font-size: 2rem; font-family: 'Ranchers', cursive;">
          {{ sectionTitle }}
      </v-toolbar-title>

      <v-spacer></v-spacer>
    </v-app-bar>
</template>

<script>
import Axios from 'axios'
import anime from 'animejs/lib/anime.es.js';

export default {
    props: [
        'auth', 'sectionTitle'
    ],
    data: () => ({
      collapseOnScroll: false,
    }),
    mounted(){
        anime({
            targets: '#title',
            translateY: -100,
            easing: 'cubicBezier(.5, .05, .1, .3)',
            direction: 'reverse',
        });
    }
}
</script>