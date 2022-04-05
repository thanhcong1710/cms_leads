<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Thêm mới mục tiêu tuần </strong>
          </div>
          <div class="card-body">
            <form action method="post">
              <div class="row">
                <div class="col-sm-6">
                  <div class="row no-margin">
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Tuần</label>
                      <input
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="target.report_week_label"
                        disabled
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email" >Nhân viên</label>
                      <input
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="target.user_label"
                        disabled
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Call</label>
                      <input
                        class="form-control"
                        type="number"
                        name="title"
                        v-model="target.call"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Talk time</label>
                      <input
                        class="form-control"
                        type="number"
                        name="title"
                        v-model="target.talk_time"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="row no-margin">
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Trial accept</label>
                      <input
                        class="form-control"
                        type="number"
                        name="title"
                        v-model="target.trial_accept"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Trial actual</label>
                      <input
                        class="form-control"
                        type="number"
                        name="title"
                        v-model="target.trial_actual"
                      />
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">New enroll</label>
                      <input
                        class="form-control"
                        type="number"
                        name="title"
                        v-model="target.new_enroll"
                      />
                    </div>
                  </div>
                </div> 
              </div>
            </form>
          </div>
          <div class="card-footer">
            <router-link class="btn btn-danger" :to="`/setting/source_detail`">
              <i class="fas fa-undo-alt"></i> Hủy
            </router-link>
            <button class="btn btn-success" type="button" @click="save">
              <i class="fas fa-save"></i> Cập nhật
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
import u from "../../../utilities/utility";
import loader from "../../../components/Loading";
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
        list_week:[],
        users_manager:[],
        target:{
          week_id:'',
          user_id:'',
          call:'',
          talk_time:'',
          trial_accept:'',
          trial_actual:'',
          new_enroll:'',
          user_label:'',
          report_week_label:'',
        },
        week:'',
        loading: {
          text: "Đang tải dữ liệu...",
          processing: false,
        },
        modal: {
          title: "THÔNG BÁO",
          show: false,
          color: "success",
          body: "Thêm mới nguồn chi tiết thành công",
          closeOnBackdrop: false,
          action_exit: "exit",
        },
        name: '',
    }
  },
  created() {
    u.g(`/api/target/show/${this.$route.params.id}`)
      .then(response => {
      this.target = response.data
    })
  },
  methods: {
    save() {
      let mess = "";
      let resp = true;
      if (this.target.call == "") {
        mess += " - Call không được để trống<br/>";
        resp = false;
      }
      if (this.target.trial_accept == "") {
        mess += " - Trial accept không được để trống<br/>";
        resp = false;
      }
      if (this.target.trial_actual == "") {
        mess += " - Trial actual không được để trống<br/>";
        resp = false;
      }
      if (this.target.new_enroll == "") {
        mess += " - New Enroll không được để trống<br/>";
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
      u.p(`/api/target/update/${this.$route.params.id}`,this.target)
        .then((response) => {
          this.loading.processing = false;
          if(response.status ==1){
            this.modal.color = "success";
            this.modal.body = "Cập nhật mục tiêu tuần thành công";
            this.modal.show = true;
            this.modal.action_exit = "exit";
          }else{
            this.modal.color = "warning";
            this.modal.body = response.message;
            this.modal.show = true;
            this.modal.action_exit = "close";
          }
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
    exit() {
      if (this.modal.action_exit == "exit") {
        this.$router.push({ path: "/setting/target" });
      } else {
        this.modal.show = false;
      }
    },
  },
};
</script>