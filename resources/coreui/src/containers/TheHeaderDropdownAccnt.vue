<template>
  <CDropdown
    inNav
    class="c-header-nav-items"
    placement="bottom-end"
    add-menu-classes="pt-0"
  >
    <template #toggler>
      <CHeaderNavLink>
        <div class="c-avatar">
          <img
            src="img/avatars/avatar.png"
            class="c-avatar-img "
          />
        </div>
      </CHeaderNavLink>
    </template>
    <CDropdownHeader tag="div" class="text-center" color="light">
      <strong>{{user_name}}</strong>
    </CDropdownHeader>
    <div class="dropdown-item">
      <i class="fa fa-phone" style="margin-right:3px"></i> Đầu Số
      <input style="width: 80px; margin-left: 10px" v-model="user_sip"/>
      <button class="btn btn-success" style="padding: 2px 5px;"  @click="updateSipID()">Lưu</button>
    </div>
    <!-- <CDropdownItem>
      <CIcon name="cil-bell"/> Updates
      <CBadge color="info" class="ml-auto">{{ itemsCount }}</CBadge>
    </CDropdownItem>
    <CDropdownItem>
      <CIcon name="cil-envelope-open" /> Messages
      <CBadge color="success" class="ml-auto">{{ itemsCount }}</CBadge>
    </CDropdownItem>
    <CDropdownItem>
      <CIcon name="cil-task" /> Tasks
      <CBadge color="danger" class="ml-auto">{{ itemsCount }}</CBadge>
    </CDropdownItem>
    <CDropdownItem>
      <CIcon name="cil-comment-square" /> Comments
      <CBadge color="warning" class="ml-auto">{{ itemsCount }}</CBadge>
    </CDropdownItem>
    <CDropdownHeader
      tag="div"
      class="text-center"
      color="light"
    >
      <strong>Settings</strong>
    </CDropdownHeader>
    <CDropdownItem>
      <CIcon name="cil-user" /> Profile
    </CDropdownItem>
    <CDropdownItem>
      <CIcon name="cil-settings" /> Settings
    </CDropdownItem>
    <CDropdownItem>
      <CIcon name="cil-dollar" /> Payments
      <CBadge color="secondary" class="ml-auto">{{ itemsCount }}</CBadge>
    </CDropdownItem>
    <CDropdownItem>
      <CIcon name="cil-file" /> Projects
      <CBadge color="primary" class="ml-auto">{{ itemsCount }}</CBadge>
    </CDropdownItem>
    <CDropdownDivider/>
    <CDropdownItem>
      <CIcon name="cil-shield-alt" /> Lock Account
    </CDropdownItem> -->
    <CDropdownItem @click="logout()">
      <CIcon name="cil-lock-locked" /> Logout
    </CDropdownItem>
  </CDropdown>
</template>

<script>
import axios from 'axios'
import u from "../utilities/utility";
export default {
  name: 'TheHeaderDropdownAccnt',
  data () {
    return { 
      itemsCount: 42,
      user_name:'',
      user_sip:'',
      user_id:''
    }
  },
  created() {
    var user_info =  JSON.parse(localStorage.getItem('user_info'));
    this.user_name= user_info.name
    this.user_sip= user_info.sip_id
    this.user_id= localStorage.getItem('user_id')
  },
  methods:{
    logout(){
      let self = this;
      axios.post('/api/logout?token=' + localStorage.getItem("api_token"),{})
      .then(function (response) {
        localStorage.setItem('roles', '');
        self.$router.push({ path: '/login' });
      }).catch(function (error) {
        console.log(error); 
      });
    },
    updateSipID(){
      const data = {
        user_sip: this.user_sip,
        user_id: this.user_id
      };
      u.p(`/api/user/update-sip`,data).then(response => {
        if(response.data.status==1){
          alert("Cập nhật thành công, vui lòng đăng nhập lại để tải lại cấu hình đầu số");
          this.logout();
        }else{
          alert(response.data.message);
        }
      })
    }
  }
}
</script>
<style scoped>
  .c-icon {
    margin-right: 0.3rem;
  }
</style>
