<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
        
            <div class="row bs-wizard" style="border-bottom:0;">
                
                <div :class="curr_step == 1 ?'col-sm-3 bs-wizard-step active': 'col-sm-3 bs-wizard-step complete'">
                  <div class="text-center bs-wizard-stepnum">Chọn tập tin</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center"></div>
                </div>
                
                <div :class="curr_step == 2 ?'col-sm-3 bs-wizard-step active': (curr_step < 2 ? 'col-sm-3 bs-wizard-step disabled':'col-sm-3 bs-wizard-step complete')" ><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">Kiểm tra dữ liệu</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center"></div>
                </div>
                
                <div :class="curr_step == 3 ?'col-sm-3 bs-wizard-step active': (curr_step < 3 ? 'col-sm-3 bs-wizard-step disabled':'col-sm-3 bs-wizard-step complete')"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">Phân chia dữ liệu</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center"></div>
                </div>
                
                <div :class="curr_step == 4 ?'col-sm-3 bs-wizard-step active': (curr_step < 4 ? 'col-sm-3 bs-wizard-step disabled':'col-sm-3 bs-wizard-step complete')"><!-- active -->
                  <div class="text-center bs-wizard-stepnum">Hoàn thành</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center"></div>
                </div>
            </div>
          </div>
          <div class="card-body" v-if="curr_step==1">
            <div class="row">
              <div class="col-12">
                <p><a href="static/template/import_khach_hang.xlsx" target="blank"><i class="fas fa-download"></i> Tải danh sách khách hàng mẫu</a></p>
              </div>
              <div class="col-8">
                  <input
                    type="file"
                    class="form-control"
                    id="fileUploadExcel"
                    @change="fileChanged"
                  >
              </div>
              <div class="col-2">
                <button class="btn btn-info" @click="btnUpload"> <i class="fas fa-upload"></i> Import</button>
              </div>
            </div>
          </div>
          <div class="card-body" v-if="curr_step==2">
            <div class="row">
              <div class="col-12" style="margin-bottom: 10px;">
                <p><strong>DỮ LIỆU ĐÃ KIỂM TRA</strong></p>
                <input type="checkbox" id="checkbox" v-model="error_checked" v-if="total_error>0">
                <label for="checkbox" v-if="total_error>0">Bỏ qua không nhập dữ liệu lỗi</label>
                <button class="btn btn-secondary fl-right" @click=" reloadPage()"> Hủy</button>
                <button class="btn btn-success fl-right" :disabled="!(total_error==0 || error_checked)" @click="showStep3()"> Tiếp theo</button>
              </div>  
              <div class="col-12">
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>STT</th>
                      <th>Tên khách hàng</th>
                      <th>Số điện thoại</th>
                      <th>Trạng thái</th>
                      <th>Thông tin lỗi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in list_data_check" :key="index">
                      <td>
                        {{ index + 1 }}
                      </td>
                      <td>{{item.name}}</td>
                      <td>{{ item.gud_mobile1 }}</td>
                      <td> <i v-if="item.status ==1 || (item.status==4 && item.is_lock==0)" class="fas fa-check" style="color:rgb(18 152 23);font-size: 20px;"></i>
                        <i v-else class="fas fa-times" style="color:rgb(177 8 8); font-size: 20px"></i></td>
                      <td>{{ item.error_message }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
            </div>
          </div>
          <div class="card-body" v-if="curr_step==3">
            <div class="row">
              <div class="col-12">
                <p><strong>THÔNG TIN DỮ LIỆU</strong></p>
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Thông số</th>
                      <th>Số lượng</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Số khách hàng hợp lệ</td>
                      <td>{{ total_validate }}</td>
                    </tr>
                    <tr>
                      <td>Số khách hàng không hợp lệ</td>
                      <td>{{ total_error }}</td>
                    </tr>
                    <tr>
                      <td>Số khách hàng có thể ghi đè</td>
                      <td>{{ total_open_lock }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-12">
                <p><strong>PHÂN CHIA DỮ LIỆU</strong></p>
                <div class="row no-margin">
                  <div class="form-group col-sm-6">
                    <label for="nf-email">Chọn người phụ trách</label>
                    <multiselect
                      placeholder="Chọn người phụ trách"
                      select-label="Chọn một người phụ trách"
                      v-model="data_assign.owners"
                      :options="list_owner"
                      label="name"
                      :close-on-select="false"
                      :hide-selected="true"
                      :multiple="true"
                      :searchable="true"
                      track-by="id"
                    >
                      <span slot="noResult">Không tìm thấy dữ liệu</span>
                    </multiselect>
                  </div>
                  <div class="form-group col-sm-6">
                    <label for="nf-email">Chọn nguồn</label>
                    <vue-select
                        label="name"
                        placeholder="Chọn nguồn"
                        :options="list_source"
                        v-model="data_assign.source"
                        :searchable="true"
                        language="tv-VN"
                        :onChange="selectSource"
                    ></vue-select>
                  </div>
                  <div class="form-group col-sm-6">
                    <label for="nf-email">Chọn nguồn chi tiết</label>
                    <vue-select
                        label="name"
                        placeholder="Chọn nguồn chi tiết"
                        :options="list_source_detail"
                        v-model="data_assign.source_detail"
                        :searchable="true"
                        language="tv-VN"
                        :onChange="selectSourceDetail"
                    ></vue-select>
                  </div>
                  <div class="form-group col-sm-12">
                    <p style="color:red" v-html="data_assign.error_message"></p>
                    <button class="btn btn-secondary fl-right" @click="reloadPage()"> Hủy</button>
                    <button class="btn btn-success fl-right" @click="assginContact"> Tiếp theo</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body" v-if="curr_step==4">
            <div class="row">
              <div class="col-12">
                <p><strong>KẾT QUẢ TẢI LÊN</strong></p>
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Thông số</th>
                      <th>Số lượng</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Số khách hàng mới được tải lên</td>
                      <td>{{ result_total_success }}</td>
                    </tr>
                    <tr>
                      <td>Số khách hàng bị bỏ qua</td>
                      <td>{{ result_total_error }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="form-group col-sm-12">
                <button class="btn btn-success fl-right" @click="reloadPage()"> Quay lại trang import</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</template>

<script>
import u from "../../../utilities/utility";
import loader from "../../../components/Loading";
import datepicker from "vue2-datepicker";
import Multiselect from "vue-multiselect";
import select from 'vue-select'

export default {
  components: {
    loader: loader,
    datepicker,
    "vue-select": select,
    Multiselect
  },
  name: "Add-Product",
  data() {
    return {
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      attached_file:"",
      file_name:"",
      curr_step:1,
      list_data_check:[],
      error_checked:false,
      total_error:0,
      total_validate:0,
      total_open_lock:0,
      list_owner:[],
      list_source:[],
      list_source_detail:[],
      data_assign:{
        owners:"",
        import_id:"",
        source:"",
        source_id:"",
        source_detail:"",
        source_detail_id:"",
        owners_id:"",
        error_message:"",
      },
      result_total_success:0,
      result_total_error:0,
    };
  },
  created() {
     u.g(`/api/user/get-users-manager`)
      .then(response => {
      this.list_owner = response.data
    })
    u.g(`/api/sources`)
      .then(response => {
      this.list_source = response.data
    })
    u.g(`/api/source_detail`)
      .then(response => {
      this.list_source_detail = response.data
    })
  },
  methods: {
    fileChanged(e) {
      const fileReader = new FileReader();
      const fileName = e.target.value.split("\\").pop();
      this.file_name = fileName
      fileReader.readAsDataURL(e.target.files[0]);
      fileReader.onload = e => {
        this.attached_file = e.target.result;
      };
    },
    btnUpload() {
      if (this.attached_file) {
        this.loading.processing = true
        let dataUpload = {
          files: this.attached_file,
          file_name: this.file_name,
        }
        u.p(`api/imports/upload`, dataUpload)
          .then(response => {
            this.loading.processing = false
            console.log(response.data)
            if(response.data.error){
              alert(response.data.message);
              this.reloadPage();
            }else{
              this.list_data_check = response.data.data
              this.curr_step=2
              this.total_error = response.data.total_error
              this.total_validate = response.data.total_validate
              this.total_open_lock = response.data.total_open_lock
              this.data_assign.import_id = response.data.import_id
            }
          })
          .catch(e => console.log(e))
      }
    },
    reloadPage(){
      location.reload();
    },
    showStep3(){
      this.curr_step=3;
    },
    selectSource(data = null){
      if (data && typeof data === 'object') {
        const source_id = data.id
        this.data_assign.source = data
        this.data_assign.source_id = source_id
      }else{
        this.data_assign.source = ""
        this.data_assign.source_id = ""
      }
    },
    selectSourceDetail(data = null){
      if (data && typeof data === 'object') {
        const source_id = data.id
        this.data_assign.source_detail = data
        this.data_assign.source_detail_id = source_id
      }else{
        this.data_assign.source_detail = ""
        this.data_assign.source_detail_id = ""
      }
    },
    assginContact(){
      const ids = []
      this.data_assign.owners = u.is.obj(this.data_assign.owners) ? [this.data_assign.owners] : this.data_assign.owners
      if (this.data_assign.owners.length) {
        this.data_assign.owners.map(item => {
          ids.push(item.id)
        })
      }
      this.data_assign.owners_id = ids
      let mess = "";
      let resp = true;
      if (this.data_assign.source == "") {
        mess += " - Nguồn dữ liệu không được để trống<br/>";
        resp = false;
      }
      if (!this.data_assign.owners_id.length) {
        mess += " - Người phụ trách không được để trống<br/>";
        resp = false;
      } 
      if(resp){
        this.data_assign.error_message = "";
        this.loading.processing = true;
        u.p(`/api/imports/assign`,this.data_assign)
        .then((response) => {
          this.loading.processing = false;
          console.log(response.data);
          this.result_total_success = response.data.total_success
          this.result_total_error = response.data.total_error
          this.curr_step = 4
        })
        .catch((e) => {
        });
      }else{
        this.data_assign.error_message = mess;
      }
    }
  },
};
</script>
<style>
/*Form Wizard*/
.bs-wizard {border-bottom: solid 1px #e0e0e0; padding: 0 0 10px 0;}
.bs-wizard > .bs-wizard-step {padding: 0; position: relative;}
.bs-wizard > .bs-wizard-step .bs-wizard-stepnum {color: #595959; font-size: 16px; margin-bottom: 5px;}
.bs-wizard > .bs-wizard-step .bs-wizard-info {color: #999; font-size: 14px;}
.bs-wizard > .bs-wizard-step > .bs-wizard-dot {position: absolute; width: 30px; height: 30px; display: block; background: #fbe8aa; top: 45px; left: 50%; margin-top: -15px; margin-left: -15px; border-radius: 50%;} 
.bs-wizard > .bs-wizard-step > .bs-wizard-dot:after {content: ' '; width: 14px; height: 14px; background: #fbbd19; border-radius: 50px; position: absolute; top: 8px; left: 8px; } 
.bs-wizard > .bs-wizard-step > .progress {position: relative; border-radius: 0px; height: 8px; box-shadow: none; margin: 20px 0;}
.bs-wizard > .bs-wizard-step > .progress > .progress-bar {width:0px; box-shadow: none; background: #fbe8aa;}
.bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {width:100%;}
.bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {width:50%;}
.bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {width:0%;}
.bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {width: 100%;}
.bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {background-color: #f5f5f5;}
.bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {opacity: 0;}
.bs-wizard > .bs-wizard-step:first-child  > .progress {left: 50%; width: 50%;}
.bs-wizard > .bs-wizard-step:last-child  > .progress {width: 50%;}
.bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot{ pointer-events: none; }
.fl-right{
  float: right;
  margin-left:10px;
}
</style>