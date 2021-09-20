<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard no-header>
        <CCardBody>
          <CForm>
            <template slot="header">
              Add User
            </template>
            <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
            >
              ({{dismissCountDown}}) {{ message }}
            </CAlert>
            <CInput type="text" label="Name" placeholder="Name" v-model="name"></CInput>
            <CInput type="text" label="Email" placeholder="Email" v-model="email"></CInput>
            <CInput type="text" label="Password" placeholder="Password" v-model="password"></CInput>
            <CInput type="text" label="Mã Nhân Viên" placeholder="Mã Nhân Viên" v-model="hrm_id"></CInput>
            <CInput type="text" label="Mã Quản lý" placeholder="Mã Quản Lý" v-model="manager_hrm_id"></CInput>
            <CInput type="text" label="Trung Tâm" placeholder="Trung Tâm" v-model="branch_name"></CInput>
            <div class="form-row form-group">
              <label> Trạng thái </label>
              <select class="form-control" v-model="status">
                <option value="0">Không kích hoạt</option>
                <option value="1">Kích hoạt</option>
              </select>
            </div>
            <div class="form-row form-group">
              <label class="col-form-label col-sm-3"> Roles </label>
              <div class="col-sm-9" >
                <div v-for="(role,index) in roles" :key="index">
                  <input type="checkbox" id="checkbox" v-model="role.checked">
                  <label for="checkbox">{{ role.name }}</label>
                </div>
              </div>
            </div>
            <CButton color="primary" @click="update()">Save</CButton>
            <CButton color="primary" @click="goBack">Back</CButton>
          </CForm>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'
export default {
  name: 'EditUser',
  props: {
    caption: {
      type: String,
      default: 'User id'
    },
  },
  data: () => {
    return {
        roles: [],
        name: '',
        email: '',
        hrm_id:'',
        manager_hrm_id:'',
        password: '',
        showMessage: false,
        message: '',
        dismissSecs: 7,
        dismissCountDown: 0,
        showDismissibleAlert: false,
        status:0,
        branch_name:''
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    },
    update() {
        let self = this;
        axios.post(  '/api/users/add?token=' + localStorage.getItem("api_token"),
        {
            name: self.name,
            email: self.email,
            password: self.password,
            roles:self.roles,
            status: self.status,
            hrm_id: self.hrm_id,
            manager_hrm_id : self.manager_hrm_id,
            branch_name:self.branch_name
        })
        .then(function (response) {
            self.message = 'Successfully updated user.';
            self.showAlert();
        }).catch(function (error) {
            console.log(error);
            self.$router.push({ path: '/login' });
        });
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
    getRoles (){
      let self = this;
      axios.get(  '/api/roles?token=' + localStorage.getItem("api_token") )
      .then(function (response) {
        self.roles = response.data;
      }).catch(function (error) {
        // self.$router.push({ path: '/login' });
      });
    }
  },
  mounted: function(){
    this.getRoles();
  }
}


</script>
