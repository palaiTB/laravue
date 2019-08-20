<template>
    <div class="container">
    <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">User's Table</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModalCenter">
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
                          <th>Reason</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for = "(user,index) in users.data" :key="index">  <!--  Users is an object and data is our required attribute -->
                          <td>{{index+1}}</td>
                          <td>{{user.name}}</td>
                            <td>{{user.email}}</td>
                          <td>{{user.created_at}}</td>
                          <td><span class="tag tag-success">Approved</span></td>
                          <td>
                              <a href=""><img src="https://img.icons8.com/material-two-tone/24/000000/edit--v1.png"></a>
                              <a href=""><img src="https://img.icons8.com/material-outlined/24/000000/delete-sign.png"></a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add New</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <form @submit.prevent="createUser">
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
                        <button type="submit" class="btn btn-primary">Create User</button>
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
              users: {},
              form: new Form({
                name: '',
                email: '',
                password: '',
              })
          }
        },
        methods:{
            loadUser()
            {
                axios.get('api/user').then(({data}) => (this.users = data));
            },
            createUser()
            {   this.$Progress.start();
                this.form.post('api/user');
                $('#exampleModalCenter').modal('hide');
                Toast.fire({
                    type: 'success',
                    title: 'User Created in successfully'
                })
                this.$Progress.finish();
            }
        },
        created() {
            this.loadUser();
            setInterval( () => this.loadUser() , 3000); //A function has to call another function in setInterval
        }
    }
</script>
