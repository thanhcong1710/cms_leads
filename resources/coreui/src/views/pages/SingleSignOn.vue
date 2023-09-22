<template>
</template>

<script>
import u from "../../utilities/utility";
    export default {
      name: 'Login',
      data() {
        return {
          loading: {
            text: "Đang tải dữ liệu...",
            processing: false,
          },
        }
      },
      created() {
        u.p("/api/single-sign-on",{
          hrm_id: this.$route.params.hrm_id,
          token: this.$route.params.token,
        })
          .then((response) => {
              localStorage.setItem("user_id", response.data.user_id);
              localStorage.setItem("branch_id", response.data.branch_id);
              localStorage.setItem("api_token", response.data.access_token);
              localStorage.setItem('roles', JSON.stringify(response.data.roles));
              localStorage.setItem('rules_setting', response.data.rules_setting);
              localStorage.setItem('user_info', JSON.stringify(response.data.user_info));
              if(this.$route.params.type ==1){
                this.$router.push({ path: '/camera-ai' });
              }else{
                this.$router.push({ path: '/dashboard' });
              }
          })
          .catch((e) => {
            u.processAuthen(e);
          });
      },
      methods: {
      }
    }

</script>
