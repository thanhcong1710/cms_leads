import u from '../../utilities/utility'
const ParentsList = () => import('../../views/customer/parents/list')
const ParentsAdd = () => import('../../views/customer/parents/add')
const ParentsEdit = () => import('../../views/customer/parents/edit')

export default {
  router: {
    path: '/',
    name: 'Khách Hàng',
    component: {
      render (c) {
        return c('router-view')
      }
    },
    children: [
      {
        path: '/parents',
        name: 'Danh Sách Khách Hàng',
        component: ParentsList
      },
      {
        path: '/parents/add',
        name: 'Thêm Mới Khách Hàng',
        component: ParentsAdd
      },
      {
        path: '/parents/:id/edit',
        name: 'Cập Nhật Khách Hàng',
        component: ParentsEdit
      }
    ]
  }
}
