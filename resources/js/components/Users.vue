<template>
    <div class="container">
    <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">User's Table</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-success" @click="newModal">
                            Add New
                        </button>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>User</th>
                            <th>Email</th>
                          <th>Date</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for = "(user,index) in users.data" :key="index">  <!--  Users is an object and data is our required attribute -->
                          <td>{{user.id}}</td>
                          <td>{{user.name}}</td>
                            <td>{{user.email}}</td>
                          <td>{{user.created_at}}</td>
                          <td><span class="tag tag-success">Approved</span></td>
                          <td>
                              <button class="btn" @click="editModal(user)"><img src="https://img.icons8.com/material-two-tone/24/000000/edit--v1.png"></button>
                              <button class="btn" @click="deleteUser(user.id)"><img src="https://img.icons8.com/material-outlined/24/000000/delete-sign.png"></button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                  <div class="text-right">
                      <button class="btn btn-outline-success">Next Page</button>
                  </div>
                  <br>
                <!-- /.card -->
              </div>
            </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-if="!edit" id="exampleModalLongTitle">Add New</h5>
                        <h5 class="modal-title" v-if="edit" id="exampleModalLongTitle">Update Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <form @submit.prevent="!edit? createUser() : updateUser()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input v-model="form.name" type="text" name="name"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input v-model="form.email" type="email" name="email"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input v-model="form.password" type="password" name="password"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                            <has-error :form="form" field="password"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                        <button v-if="!edit" type="submit" class="btn btn-primary">Create User</button>
                        <button v-if="edit" type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
          return{
              edit : false,
              users: {},
              next: '',
              form: new Form({
                id:'',
                name: '',
                email: '',
                password: '',
              })
          }
        },
        methods:{
            updateUser()
            {
                console.log("hello");
                this.form.put('api/user/'+this.form.id)
                    .then(() => {
                        Fire.$emit('AfterCreate'); //Creates a custom event

                        $('#exampleModalCenter').modal('hide');
                        Toast.fire({   //Sweet Alert
                            type: 'success',
                            title: 'User Updated in successfully'
                        })
                        this.$Progress.finish();
                    })
                    .catch(()=> {
                        this.$Progress.fail();
                    });
            },
            editModal(user)
            {
                this.edit = true;
                this.form.reset();
                $('#exampleModalCenter').modal('show');
                this.form.fill(user);
            },
            newModal()
            {   this.edit = false;
                this.form.reset();
                $('#exampleModalCenter').modal('show');
            },
            deleteUser(id){
                //SWEETALERT-----------------------
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {

                        //Send the api request for deletion
                        this.form.delete('api/user/'+ id)
                        .then(()=> {
                            Swal.fire(
                                'Deleted!',
                                'Your record has been deleted.',
                                'success'
                            )
                            Fire.$emit('AfterCreate');
                        })
                    }
                })
                //----------------------------------------------

            },

            loadUser()
            {
                axios.get('api/user')
                    // .then(({data}) => (this.users = data));
                    .then(response => {   //ES6 format
                       console.log(response.data);
                       this.users = response.data;
                    });
            },
            createUser()
            {
                this.$Progress.start();
                this.form.post('api/user')
                .then(() => {
                Fire.$emit('AfterCreate'); //Creates a custom event

                $('#exampleModalCenter').modal('hide');
                    Toast.fire({   //Sweet Alert
                        type: 'success',
                        title: 'User Created in successfully'
                    })
                this.$Progress.finish();
                })
                .catch(()=> {
                    this.$Progress.fail();
            });
            }
        },
        created() {
            this.loadUser();
            Fire.$on('AfterCreate',() => {  //$on receives the event and subsequently calls for the loadUser() and hence our data is updated
                this.loadUser();
            });
            // setInterval( () => this.loadUser() , 3000); //A function has to call another function in setInterval
        }
    }
</script>
