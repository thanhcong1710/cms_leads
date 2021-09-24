<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Thêm mới khách hàng </strong>
          </div>
          <div class="card-body">
            <form action method="post">
              <div class="row">
                <div class="col-sm-6">
                  <div class="row no-margin">
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Họ tên <span class="text-danger"> (*)</span></label>
                      <input
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="name"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email" >Điện thoại <span class="text-danger"> (*)</span></label>
                      <input
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="phone"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Email <span class="text-danger"> (*)</span></label>
                      <input
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="email"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Mật khẩu </label>
                      <input
                        disabled
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="password"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Mã Nhân Viên <span class="text-danger"> (*)</span></label>
                      <input
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="hrm_id"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Mã Quản Lý</label>
                      <input
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="manager_hrm_id"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Trung Tâm <span class="text-danger"> (*)</span></label>
                      <input
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="branch_name"
                      />
                    </div>
                    <div class="col-sm-12 form-group">
                      <label> Trạng thái </label>
                      <select class="form-control" v-model="status">
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
                        <input type="checkbox" id="checkbox" v-model="role.checked">
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
              <i class="fas fa-undo-alt"></i> Hủy
            </router-link>
            <button class="btn btn-success" type="button" @click="save">
              <i class="fas fa-save"></i> Thêm mới
            </button>
          </div>
        </div>
      </div>
    </div>
    <CModal
      :title="modal.title"
      :show.sync="modal.show"
      :color="modal.color"
      :closeOnBackdrop="modal.closeOnBackdrop"
    >
      <div v-html="modal.body"></div>
      <template #header>
        <h5 class="modal-title">{{ modal.title }}</h5>
      </template>
      <template #footer>
        <CButton :color="'btn btn-' + modal.color" @click="exit" type="button"
          >Đóng</CButton
        >
      </template>
    </CModal>
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
        password: '123456aA@',
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
  },
  methods: {
    selectDate(date) {
      if (date) {
        this.student.birthday = moment(date).format("YYYY-MM-DD");
      }
    },
    save() {
      let mess = "";
      let resp = true;
      if (this.name == "") {
        mess += " - Họ tên không được để trống<br/>";
        resp = false;
      }
      if (this.email == "") {
        mess += " - Email không được để trống<br/>";
        resp = false;
      }
      if (this.phone == "") {
        mess += " - Số điện thoại không được để trống<br/>";
        resp = false;
      }
      if (this.phone != "" && !u.vld.phone(this.phone)) {
        mess += " - Số điện thoại không đúng định dạng<br/>";
        resp = false;
      }
      if (this.hrm_id == "") {
        mess += " - Mã nhân viên không được để trống<br/>";
        resp = false;
      }
      if (this.hrm_id == "") {
        mess += " - Trung tâm không được để trống<br/>";
        resp = false;
      }
      const new_list = []
      this.roles.map(item => {
          if (item.checked) {
              new_list.push(item)
          }
          return item
      })
      if (!new_list.length) {
        mess += " - Chưa phân quyền cho nhân viên<br/>";
        resp = false;
      }
      if (!resp) {
        this.modal.color = "warning";
        this.modal.body = mess;
        this.modal.show = true;
        this.modal.action_exit = "close";
        return false;
      }
      this.loading.processing = true;
      u.p("/api/users/add",{
        name: this.name,
        email: this.email,
        phone:this.phone,
        password: this.password,
        roles:this.roles,
        status: this.status,
        hrm_id: this.hrm_id,
        manager_hrm_id : this.manager_hrm_id,
        branch_name:this.branch_name
      })
        .then((response) => {
          this.loading.processing = false;
          if (response.status == 200) {
            this.modal.color = "success";
            this.modal.body = "Thêm mới nhân viên thành công";
            this.modal.show = true;
            this.modal.action_exit = "exit";
          }
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
    exit() {
      if (this.modal.action_exit == "exit") {
        this.$router.push({ path: "/users" });
      } else {
        this.modal.show = false;
      }
    },
  },
};
</script>