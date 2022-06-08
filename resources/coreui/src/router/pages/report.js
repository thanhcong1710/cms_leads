import u from '../../utilities/utility'
const ReportList = () => import('../../views/reports/list')
const Report01 = () => import('../../views/reports/report_01')
const Report02 = () => import('../../views/reports/report_02')
const Report03 = () => import('../../views/reports/report_03')
const Report04 = () => import('../../views/reports/report_04')

export default {
  router: {
    path: '/reports',
    name: '',
    component: {
      render (c) {
        return c('router-view')
      }
    },
    children: [
      {
        path: '/reports',
        name: 'Danh Sách Báo Cáo',
        component: ReportList
      },
      {
        path: '/reports/01',
        name: 'Báo cáo thông tin khách hàng',
        component: Report01
      },
      {
        path: '/reports/02',
        name: 'Báo cáo tuần Sale HUB',
        component: Report02
      },
      {
        path: '/reports/03',
        name: 'Báo cáo cuộc gọi',
        component: Report03
      },
      {
        path: '/reports/04',
        name: 'Báo cáo chi tiết cuộc gọi',
        component: Report04
      },
    ]
  }
}
