<template>
  <CFooter :fixed="false">
    <div>
      <a href="https://cmsedu.vn/" target="_blank">CMS</a>
      <span class="ml-1">&copy; {{new Date().getFullYear()}} creativeLabs.</span>
    </div>
    <div class="ml-auto">
      <span class="mr-1">Powered by</span>
      <a href="https://cmsedu.vn/" target="_blank">CMS</a>
    </div>
  </CFooter>
</template>

<script>
export default {
  name: 'TheFooter'
}
</script>
