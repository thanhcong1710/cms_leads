import u from '../../utilities/utility'
const SourceDetailList = () => import('../../views/setting/source_detail/list')
const SourceDetailAdd = () => import('../../views/setting/source_detail/add')
const SourceDetailEdit = () => import('../../views/setting/source_detail/edit')

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
        path: '/setting/source_detail',
        name: 'Danh Sách Nguồn Chi Tiết',
        component: SourceDetailList
      },
      {
        path: '/setting/source_detail/add',
        name: 'Thêm Mới Nguồn Chi Tiết',
        component: SourceDetailAdd
      },
      {
        path: '/setting/source_detail/:id/edit',
        name: 'Cập Nhật Nguồn Chi Tiết',
        component: SourceDetailEdit
      }
    ]
  }
}
