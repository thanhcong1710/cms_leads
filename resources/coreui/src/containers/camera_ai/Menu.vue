<template>
    <CHeaderNav class="d-md-down-none mr-auto">
      <CHeaderNavItem 
        v-for="n in nav"
        v-bind:key="n.name"
      >
        <CHeaderNavLink
          v-if="n._name == 'CSidebarNavItem'"
          :href="'#' + n.href"
        >
          {{ n.name }}
        </CHeaderNavLink>
        <CDropdown
          v-if="n._name == 'CSidebarNavDropdown'"
          color="link"
          :caret="false"
        >
          <template #toggler-content>
            <a class="c-header-nav-link">
              {{ n.name }}
            </a>
          </template>
          <CDropdownItem 
            v-for="d in n._children"
            v-bind:key="d.name"
            :href="'#' + d.to"
          >
            {{ d.name }}
          </CDropdownItem>
        </CDropdown>
      </CHeaderNavItem>
    </CHeaderNav>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Menu',
  data () {
    return {
      //minimize: false,
      nav: [],
      //show: true,
      buffor: [],
    }
  },
  methods: {
  },
  mounted () {
  }
}
</script>