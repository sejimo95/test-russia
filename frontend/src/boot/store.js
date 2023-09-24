import { boot } from 'quasar/wrappers'
import { useStore } from 'stores/store'
export default boot(({ app }) => {
  app.config.globalProperties.$s = useStore()
})
