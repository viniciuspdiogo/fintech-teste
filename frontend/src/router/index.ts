import { createRouter, createWebHistory } from 'vue-router'
import Welcome from "@/views/Welcome.vue";
import Dashboard from "@/views/Dashboard.vue";
import Withdraw from "@/views/Withdraw.vue";
import Deposit from "@/views/Deposit.vue";

import { useAuthStore } from '@/stores/auth';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
      {
          path: '/',
          name: 'Welcome',
          component: Welcome,
          meta: { guest: true }
      },
      {
          path: '/dashboard',
          name: 'Dashboard',
          component: Dashboard,
          meta: { auth: true },
      },
      {
          path: '/withdraw',
          name: 'Withdraw',
          component: Withdraw,
          meta: { auth: true },
      },
      {
          path: '/deposit',
          name: 'Deposit',
          component: Deposit,
          meta: { auth: true },
      },
  ],

})

router.beforeEach((to) => {
    const auth = useAuthStore();

    if (to.meta.auth && !auth.isAuthenticated) return '/';
    if (to.meta.guest && auth.isAuthenticated) return '/dashboard';
});
export default router
