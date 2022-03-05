import u from '../../utilities/utility'
const SourceDetailList = () => import('../../views/setting/source_detail/list')
const SourceDetailAdd = () => import('../../views/setting/source_detail/add')
const SourceDetailEdit = () => import('../../views/setting/source_detail/edit')
const TargetList = () => import('../../views/setting/target/list')
const TargetAdd = () => import('../../views/setting/target/add')
const TargetEdit = () => import('../../views/setting/target/edit')

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
      },
      {
        path: '/setting/target',
        name: 'Danh Sách Mục Tiêu Tuần',
        component: TargetList
      },
      {
        path: '/setting/target/add',
        name: 'Thêm Mới Mục Tiêu Tuần',
        component: TargetAdd
      },
      {
        path: '/setting/target/:id/edit',
        name: 'Cập Nhật Mục Tiêu Tuần',
        component: TargetEdit
      }
    ]
  }
}
