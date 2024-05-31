export default defineAppConfig({
  name: 'Laravel/Nuxt Boilerplate',
  ui: {
    primary: 'emerald',
    strategy: 'override',
    gray: 'neutral',
    container: {
      constrained: 'max-w-7xl w-full'
    },
    avatar: {
      default: {
        icon: 'i-heroicons-user',
      }
    },
    notifications: {
      // Show toasts at the top right of the screen
      position: 'top-0 bottom-auto right-0'
    }
  }
})
