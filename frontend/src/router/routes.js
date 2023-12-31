
const routes = [
  // panel
  {
    path: '',
    component: () => import('layouts/PanelLayout.vue'),
    children: [
      { path: 'deals', component: () => import('pages/Panel/Deal/IndexPage.vue') },
      { path: 'logs', component: () => import('pages/Panel/Log/IndexPage.vue') }
    ]
  },
  {
    path: '/login',
    component: () => import('layouts/AuthLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Auth/LoginOauthPage.vue') }
      // { path: '', component: () => import('pages/Auth/LoginPage.vue') }
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
