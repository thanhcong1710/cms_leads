<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <strong>DANH SÁCH BÁO CÁO</strong>
          </div>
          <div class="card-body report-list">
            <p class="h6"><router-link to="/reports/01" v-if="showBC01" >01. Báo cáo thông tin khách hàng</router-link></p>
            <p class="h6"><router-link to="/reports/02" v-if="showBC02" >02. Báo cáo tuần Sale HUB</router-link></p>
            <p class="h6"><router-link to="/reports/03" v-if="showBC03" >03. Báo cáo cuộc gọi</router-link></p>
            <p class="h6"><router-link to="/reports/04" v-if="showBC04" >04. Báo cáo chi tiết cuộc gọi</router-link></p>
            <p class="h6"><router-link to="/reports/05" v-if="showBC05" >04. Báo cáo ghi đè</router-link></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import u from "../../utilities/utility";

export default {
  components: {
  },
  name: "List-Parent",
  data() {
    return {
      showBC01:false,
      showBC02:false,
      showBC03:false,
      showBC04:false,
    }
  },
  created() {
    this.showBC01 = true;
    this.showBC03 = true;
    this.showBC04 = true;
    const arr_role = JSON.parse(localStorage.getItem("roles")).split(",");
    if(arr_role.indexOf("admin")> -1){
      this.showBC02 = true
    }
  },
  methods: {
  }
}
</script>
<style scoped>
 .report-list p{
    border-bottom: 1px dashed grey;
    padding: 5px;
 }
</style>
