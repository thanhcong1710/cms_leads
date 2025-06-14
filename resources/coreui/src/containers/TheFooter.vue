<template>
  <CFooter :fixed="false">
    <div>
      <a href="https://logiclab.edu.vn/" target="_blank">LogicLab</a>
      <span class="ml-1">&copy; {{new Date().getFullYear()}} creativeLabs.</span>
    </div>
    <div class="ml-auto">
      <span class="mr-1">Powered by</span>
      <a href="https://logiclab.edu.vn/" target="_blank">LogicLab</a>
    </div>
  </CFooter>
</template>

<script>
export default {
  name: 'TheFooter'
}
</script>
