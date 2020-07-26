<template>
    <v-container class="mt-10">
     <v-row>
        <v-col cols="12" md="3">
            <div class="text-center green--text accent-4"><h3>Create</h3></div>
            <hr>
            <v-progress-linear
            v-if="progressCreate"
            indeterminate
            color="yellow darken-2"
            ></v-progress-linear>
            <v-row class="yellow lighten-5">
                <v-col cols="12">
                    <v-alert v-if="createError" type="error">Error in the text field</v-alert>
                    <h5>Title</h5>
                    <v-text-field solo hide-details 
                    placeholder="Give random title"
                    v-model="create.title"
                    />
                </v-col>
                <v-col cols="12" class="py-0">
                    <h5>Description</h5>
                    <v-textarea solo placeholder="Random again"
                    v-model="create.description"
                    />
                </v-col>
                <v-col cols="12">
                    <v-btn block dark color="green" @click="submitNew">Submit</v-btn>
                </v-col>
            </v-row>
        </v-col>
        <v-col cols="12" md="6">
            <div class="text-center">
                <h3>
                <span class="blue--text accent-3">Read, </span>
                <span class="light-blue--text accent-3">Update, </span>
                <span class="red--text accent-3">Delete</span>
                </h3>
            </div>
            <hr>
            <div>
                <v-sheet
                class="overflow-y-auto"
                max-height="370"
              >
                <v-alert v-if="error" type="error">{{errorTxt}}</v-alert>
                <v-container class="yellow lighten-5" style="height: 500px; position: relative">
                    <v-progress-linear
                    v-if="progressRead"
                    indeterminate
                    color="blue darken-2"
                    ></v-progress-linear>
                    <v-alert type="info" v-if="!crud_display.length && !progressRead">No Item</v-alert>
                    <v-list class="transparent" v-if="crud_display.length" three-line>
                    <v-list-item-group v-model="item" color="primary">
                        <v-list-item
                        v-for="(item, i) in crud_display"
                        :key="i"
                        @click="showCard(item)"
                        :class="item.author_id === auth.id? 'green lighten-5':'yellow lighten-4'"
                        >
                        <v-list-item-content>
                            <v-list-item-title v-text="item.title"></v-list-item-title>
                            <v-list-item-subtitle v-text="item.description"></v-list-item-subtitle>
                            <v-list-item-subtitle>by: {{item.author.name}}</v-list-item-subtitle>
                        </v-list-item-content>
                        <v-list-item-action>
                        <v-btn icon>
                            <v-icon>mdi-eye</v-icon>
                        </v-btn>
                        </v-list-item-action>
                        </v-list-item>
                    </v-list-item-group>
                    </v-list>
                </v-container>
                </v-sheet>
            </div>
            <div class="d-flex px-4 justify-end">
                <v-checkbox color="success" v-model="myworkFilter" class="m-0 ml-1"></v-checkbox>
                <h6 class="m-1 p-1">My Works</h6>
            </div>
        </v-col>
        <v-col cols="12" md="3">
            <div class="text-center"><h3>Json</h3></div>
            <hr>
            <div>
              <v-sheet
                class="overflow-y-auto"
                max-height="370"
              >
                <v-container class="yellow lighten-5" style="height: 500px;">
                    <h5>{{crud_json.request}}</h5>
                    <h6>{{crud_json.response}}</h6>
                </v-container>
                </v-sheet>
            </div>
        </v-col>
    </v-row>

    <v-dialog max-width="400" v-model="show">
        <v-card class="p-3">
            <div class="d-flex justify-end white">
            <v-btn icon @click="show = !show"><v-icon>mdi-close</v-icon></v-btn>
        </div>
            <v-progress-linear
                v-if="progressDelete || progressUpdate"
                indeterminate
                :color="progressDelete ? 'red darken-2':'blue darken-2'"
            ></v-progress-linear>
            <v-row class="yellow lighten-5">
                <v-col cols="12">
                    <v-alert v-if="createError" type="error">Error in the text field</v-alert>
                    <h5>Title</h5>
                    <v-text-field solo hide-details 
                      :readonly="showObj.author_id !== auth.id"
                      v-model="showObj.title"
                      v-on:input="isChange = true"
                    />
                </v-col>
                <v-col cols="12" class="py-0">
                    <h5>Description</h5>
                    <v-textarea :readonly="showObj.author_id !== auth.id"
                    solo
                    v-on:input="isChange = true"
                    v-model="showObj.description"
                    class="blue"
                    hide-details
                    />
                </v-col>
                <v-col cols="12">
                    By: {{showObj.author}}
                </v-col>
                <v-col cols="6" v-if="showObj.author_id === auth.id">
                    <v-btn text block dark color="red" @click="delCrud( showObj.id )">Delete</v-btn>
                </v-col>
                <v-col cols="6" v-if="showObj.author_id === auth.id">
                    <v-btn block color="blue white--text" :disabled="!isChange" @click="patchCrud">Save</v-btn>
                </v-col>
            </v-row>
        </v-card>
    </v-dialog>
    </v-container>
</template>

<script>
//import Axios from 'axios'
export default {
  props: [ 'csrf', 'auth' ],
  async mounted(){
      this.progressRead = true
      try{
        const res = await axios.get('/crud');
        this.crud_json.request = 'GET /crud:';
        this.crud_json.response = res.data;
        this.crud_data = res.data;
        this.progressRead = false
      }catch(err){
          this.error = true;
          this.errorTxt = err;
          throw err;
      }
  },
  data(){
    return{
       create: { title: '', description: '' },
       crud_data: [],
       crud_json: {
           request: '',
           response: null
       },
       isChange: false,
       item: '',
       filter: '',
       error: false,
       errorTxt: '',
       show: false,
       showObj:{
           id: null,
           author_id: null,
           title: '',
           description: '',
           author: ''
       },
       createError: false,
       progressCreate: false,
       progressRead: false,
       progressDelete: false,
       progressUpdate: false,
       myworkFilter: true,
       overlay: false
    }
  },
  computed: {
      crud_display: function(){
          if( this.myworkFilter == true ){
            return this.crud_data.filter(v => v.author_id == this.auth.id)
          } return this.crud_data
      }
  },
  methods:{
      async submitNew(){
        this.progressCreate = true;
        this.progressRead = true;
          try{
            const temp = this.create
            const res = await axios.post('/crud', temp)
            this.crud_json.request = 'POST /crud:';
            this.crud_json.response = res.data;
            this.progressCreate = false
            this.create = { title: '', description: '' }

            const {title, description} = temp

            this.crud_data = [{title, description, author_id: this.auth.id, id: res.data.id, author: this.auth}, ...this.crud_data];
            
            this.progressRead = false;
            
            console.trace('new item is now Stored');

          }catch (err){
              this.createError = true
              throw err;
          }
      },
      async delCrud( id ){
          this.progressDelete = true
          try {
              const res = await axios.delete(`/crud/${id}`);
              this.crud_json.request = `DELETE /crud/${id}:`;
              this.crud_json.response = res.data;
              
              this.crud_data = this.crud_data.filter(v => v.id != id);
              this.show = false
              this.progressDelete = false
              console.trace(`id: ${id} is now at Trash`);
          }catch(err){
              this.error = true;
              this.errorTxt = err;
              throw err;
          }
      },
      async showCard({ id, title, description, author_id, author}){
          this.show = true;
          this.showObj = { id, title, description, author_id, author: author.name };
      },
      async patchCrud(){
          const { id, author_id, title, description, author } = this.showObj;
          this.progressUpdate = true
          try{
              const res = await axios.put(`/crud/${id}`,{title, description});
              this.crud_json.request = `UPDATE /crud/${id}:`;
              this.crud_json.response = res.data;

              this.crud_data.forEach(element => {
                  if(element.id == id){
                    element.title = title
                    element.description = description
                  }
                  return element;
              });

              this.progressUpdate = false
              console.trace(`id: ${id} is now at Updated`)
          }catch(err){
              this.error = true;
              this.errorTxt = err;
              throw err;
          }
          
      }
  }
}
</script>

<style>

</style>