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
              localStorage.setItem("api_token", response.data.access_token);
              localStorage.setItem('roles', response.data.roles);
              this.$router.push({ path: '/dashboard' });
          })
          .catch((e) => {
            u.processAuthen(e);
          });
      },
      methods: {
      }
    }

</script>
