<template>
  <v-app>
      <navbar :auth="auth" sectionTitle="CRUD Api"></navbar>
      <v-sheet class="mt-10">
        <crud-section :csrf="csrf" :auth="auth"></crud-section>
        <ai-section class="blue p-5 mt-md-16" v-if="open"></ai-section>
      </v-sheet>
      
      <v-snackbar
        v-model="snackbar"
        timeout="10000"
      >
        {{ text }}

        <template v-slot:action="{ attrs }">
            <v-btn
            color="red"
            text
            v-bind="attrs"
            @click="snackbar = false"
            >
            No
            </v-btn>
            <v-btn
            color="red"
            v-bind="attrs"
            @click="logout"
            >
            Yes
            </v-btn>
        </template>
        </v-snackbar>

      <v-footer app color="transparent">
          <v-btn color="primary" text @click="open = !open">{{ !open ? 'Simple Face Recognition': 'Close Face Recognition' }}</v-btn>
          <v-spacer/>
          <v-btn color="red" dark rounded @click="snackbar = true">logout</v-btn>
      </v-footer>
  </v-app>
</template>

<script>
import Axios from 'axios'

import Nav from '../components/navbar/Authorized'
import CRUD from '../components/sections/Crud'
import AI from '../components/sections/ArtificialInt'

export default {
    components: {
        'navbar' : Nav,
        'crud-section' : CRUD,
        'ai-section': AI
    },
    props: [
        'auth', 'csrf'
    ],
    mounted(){
        Axios.defaults.headers.common['X-CSRF-TOKEN'] = this.csrf;
    },
    data(){
      return{
          snackbar: false,
          text: 'Are you sure??',
          open: false
      }
    },
    methods: {
        logout(){
            Axios.post('/logout');
            window.location.replace('/login')
        }
    }
}
</script>

<style>

</style>