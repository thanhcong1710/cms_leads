<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Thêm mới nguồn chi tiết </strong>
          </div>
          <div class="card-body">
            <form action method="post">
              <div class="row">
                <div class="col-sm-6">
                  <div class="row no-margin">
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Trung Tâm Áp dụng</label>
                      <select class="form-control" v-model="branch_id">
                        <option value="0">Chọn trung tâm</option>
                        <option :value="item.id" v-for="(item, index) in branches" :key="index">{{item.name}}</option>
                      </select>
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Tên nguồn chi tiết <span class="text-danger"> (*)</span></label>
                      <input
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="name"
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
              </div>
            </form>
          </div>
          <div class="card-footer">
            <router-link class="btn btn-danger" :to="`/setting/source_detail`">
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
        status:1,
        branch_id:'',
        branches:[],
    }
  },
  created() {
    u.g(`/api/branches`)
      .then(response => {
      this.branches = response.data
    })
  },
  methods: {
    save() {
      let mess = "";
      let resp = true;
      if (this.name == "") {
        mess += " - Tên nguồn chi tiết không được để trống<br/>";
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
      u.p("/api/source_detail/add",{
        name: this.name,
        status: this.status,
        branch_id: this.branch_id,
      })
        .then((response) => {
          this.loading.processing = false;
          this.modal.color = "success";
          this.modal.body = "Thêm mới nguồn chi tiết thành công";
          this.modal.show = true;
          this.modal.action_exit = "exit";
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
    exit() {
      if (this.modal.action_exit == "exit") {
        this.$router.push({ path: "/setting/source_detail" });
      } else {
        this.modal.show = false;
      }
    },
  },
};
</script>