<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <strong>DANH SÁCH BÁO CÁO</strong>
          </div>
          <div class="card-body report-list">
            <p class="h6"><router-link to="/reports/01" >01. Báo cáo thông tin khách hàng</router-link></p>
            <p class="h6"><router-link to="/reports/02" >02. Báo cáo tuần Sale HUB</router-link></p>
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
    }
  },
  created() {
    
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
