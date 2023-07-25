import u from '../../utilities/utility'
const CameraAction = () => import('../../views/cameraAI/list')
const CameraRegister = () => import('../../views/cameraAI/students')
export default {
  router: {
    path: '/camera-ai',
    name: '',
    component: {
      render (c) {
        return c('router-view')
      }
    },
    children: [
      {
        path: '/camera-ai',
        name: 'Danh Sách Sự Kiện',
        component: CameraAction
      },
      {
        path: '/camera-register',
        name: 'Khởi tạo dữ liệu',
        component: CameraRegister
      },
    ]
  }
}
