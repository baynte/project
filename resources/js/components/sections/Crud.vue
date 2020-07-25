<template>
    <v-container class="mt-10">
     <v-row>
        <v-col cols="12" md="3">
            <div class="text-center green--text accent-4"><h3>Create</h3></div>
            <v-progress-linear
            v-if="progressCreate"
            indeterminate
            color="yellow darken-2"
            ></v-progress-linear>
            <v-row class="grey lighten-5">
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
            <div>
                <v-sheet
                class="overflow-y-auto"
                max-height="370"
              >
                <v-container class="grey lighten-5" style="height: 500px;">
                    <v-list class="transparent">
                    <v-list-item-group v-model="item" color="primary">
                        <v-list-item
                        v-for="(item, i) in crud_data"
                        :key="i"
                        >
                        <v-list-item-content>
                            <v-list-item-title v-text="item.title"></v-list-item-title>
                            <v-list-item-subtitle v-text="item.description"></v-list-item-subtitle>
                        </v-list-item-content>
                        <v-list-item-action>
                        <v-btn icon @click="delCrud(item.id)" v-if="item.author_id === authId">
                            <v-icon color="red">mdi-delete</v-icon>
                        </v-btn>
                        </v-list-item-action>
                        </v-list-item>
                    </v-list-item-group>
                    </v-list>
                </v-container>
                </v-sheet>
            </div>
        </v-col>
        <v-col cols="12" md="3">
            <div class="text-center"><h3>Json</h3></div>
            <div>
              <v-sheet
                class="overflow-y-auto"
                max-height="370"
              >
                <v-container class="grey lighten-5" style="height: 500px;">
                    <h5>{{crud_json.request}}</h5>
                    <h6>{{crud_json.response}}</h6>
                </v-container>
                </v-sheet>
            </div>
        </v-col>
    </v-row>
    </v-container>
</template>

<script>
//import Axios from 'axios'
export default {
  props: [ 'csrf', 'authId' ],
  async mounted(){
        const res = await axios.get('/crud');
        this.crud_json.request = 'GET /crud:';
        this.crud_json.response = res.data;
        this.crud_data = res.data;
  },
  data(){
    return{
       create: { title: '', description: '' },
       crud_data: null,
       crud_json: {
           request: '',
           response: null
       },
       item: '',
       createError: false,
       progressCreate: false
    }
  },
  methods:{
      async submitNew(){
        this.progressCreate = true  
          try{
            const res = await axios.post('/crud', this.create)
            this.crud_json.request = 'POST /crud:';
            this.crud_json.response = res.data;
            this.create = { title: '', description: '' }
            this.progressCreate = false
          }catch (err){
              this.createError = true
              throw err;
          }
      },
      async delCrud( id ){
          const res = await axios.delete(`/crud/${id}`);
          console.log(res.data)
      }
  }
}
</script>

<style>

</style>