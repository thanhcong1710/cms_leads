import u from '../../utilities/utility'
const ReportList = () => import('../../views/reports/list')
const Report01 = () => import('../../views/reports/report_01')
const Report02 = () => import('../../views/reports/report_02')
const Report03 = () => import('../../views/reports/report_03')
const Report04 = () => import('../../views/reports/report_04')
const Report05 = () => import('../../views/reports/report_05')
const Report06 = () => import('../../views/reports/report_06')
const ReportOverdue = () => import('../../views/reports/report_overdue')
const ReportCareDetail = () => import('../../views/reports/report_care_detail')
const ReportCareOverview = () => import('../../views/reports/report_care_overview')

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
        name: 'Báo cáo chi tiết khách hàng quá hạn xử lý',
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
      {
        path: '/reports/06',
        name: 'Báo cáo tổng đài',
        component: Report06
      },
      {
        path: '/reports/05',
        name: 'Báo cáo bàn giao khách hàng',
        component: Report05
      },
      {
        path: '/reports/overdue-overview',
        name: 'Báo cáo tổng quan KH quá hạn xử lý',
        component: ReportOverdue
      },
      {
        path: '/reports/care-overview',
        name: 'Báo cáo tổng quan chăm sóc khách hàng',
        component: ReportCareOverview
      },
      {
        path: '/reports/care-detail',
        name: 'Báo cáo chi tiết chăm sóc khách hàng',
        component: ReportCareDetail
      },
    ]
  }
}

