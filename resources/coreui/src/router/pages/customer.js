import u from '../../utilities/utility'
const ParentsList = () => import('../../views/customer/parents/list')
const ParentsAdd = () => import('../../views/customer/parents/add')
const ParentsEdit = () => import('../../views/customer/parents/edit')
const ParentsDetail = () => import('../../views/customer/parents/detail')
const ImportsList = () => import('../../views/customer/imports/list')
const ImportsAdd = () => import('../../views/customer/imports/add')

export default {
  router: {
    path: '/parents',
    name: '',
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
      },
      {
        path: '/parents/:id/detail',
        name: 'Chi tiết Khách Hàng',
        component: ParentsDetail
      },
      {
        path: '/imports',
        name: 'Danh Sách Import',
        component: ImportsList
      },
      {
        path: '/imports/add',
        name: 'Import Khách Hàng',
        component: ImportsAdd
      },
    ]
  }
}
