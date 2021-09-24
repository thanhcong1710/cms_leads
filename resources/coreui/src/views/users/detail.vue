<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Cập nhật khách hàng </strong>
          </div>
          <div class="card-body">
            <form action method="post">
              <div class="row">
                <div class="col-sm-6">
                  <div class="row no-margin">
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Họ tên <span class="text-danger"> (*)</span></label>
                      <input
                      disabled
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="name"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email" >Điện thoại <span class="text-danger"> (*)</span></label>
                      <input
                      disabled
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="phone"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Email <span class="text-danger"> (*)</span></label>
                      <input
                      disabled
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="email"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Mã Nhân Viên <span class="text-danger"> (*)</span></label>
                      <input
                       disabled
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="hrm_id"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Mã Quản Lý</label>
                      <input
                       disabled
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="manager_hrm_id"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Trung Tâm <span class="text-danger"> (*)</span></label>
                      <input
                        disabled
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="branch_name"
                      />
                    </div>
                    <div class="col-sm-12 form-group">
                      <label> Trạng thái </label>
                      <select class="form-control" v-model="status" disabled>
                        <option value="0">Không kích hoạt</option>
                        <option value="1">Kích hoạt</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="row no-margin">
                    <div class="col-sm-12" >
                      <label> Phân quyền <span class="text-danger"> (*)</span></label>
                      <div v-for="(role,index) in roles" :key="index">
                        <input type="checkbox" id="checkbox" v-model="role.checked" disabled>
                        <label for="checkbox">{{ role.name }}</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <router-link class="btn btn-danger" :to="`/users`">
              <i class="fas fa-undo-alt"></i> Thoát
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import u from "../../utilities/utility";
import loader from "../../components/Loading";
import Editor from "@tinymce/tinymce-vue";
import datepicker from "vue2-datepicker";
import moment from 'moment';
import select from 'vue-select'

export default {
  components: {
    loader: loader,
    editor: Editor,
    datepicker,
    "vue-select": select
  },
  name: "Add-Product",
  data() {
    return {
        loading: {
          text: "Đang tải dữ liệu...",
          processing: false,
        },
        modal: {
          title: "THÔNG BÁO",
          show: false,
          color: "success",
          body: "Thêm mới nhân viên thành công",
          closeOnBackdrop: false,
          action_exit: "exit",
        },
        roles: [],
        name: '',
        email: '',
        phone:'',
        hrm_id:'',
        manager_hrm_id:'',
        password: '',
        showMessage: false,
        message: '',
        dismissSecs: 7,
        dismissCountDown: 0,
        showDismissibleAlert: false,
        status:1,
        branch_name:''
    }
  },
  created() {
    let self = this;
    axios.get(  '/api/roles?token=' + localStorage.getItem("api_token") )
    .then(function (response) {
      self.roles = response.data;
    }).catch(function (error) {
      // self.$router.push({ path: '/login' });
    });
     axios.get(  '/api/users/' + self.$route.params.id + '/edit?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
        self.name = response.data.name;
        self.email = response.data.email;
        self.phone = response.data.phone;
        self.status = response.data.status;
        self.hrm_id = response.data.hrm_id;
        self.manager_hrm_id = response.data.manager_hrm_id; 
        self.branch_name = response.data.branch_name; 
        let arr_role = response.data.roles.split(",");
        self.roles.map(item => {
          if (arr_role.indexOf(item.name) != -1) {
            item.checked= true
          }else{
            item.checked= false
          }
          return item
        })
    }).catch(function (error) {
        console.log(error);
        // self.$router.push({ path: '/login' });
    });
  },
  methods: {
    
  },
};
</script>