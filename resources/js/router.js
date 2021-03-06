import Emails from  './components/emails/emails';
import Users  from  './components/users/users';
import Login  from  './components/templates/auth/login';
import SMS    from  './components/sms/sms';
import Track  from  './components/templates/track';
import Audit  from  './components/audit/audit';


function isAuth(from, to, next) {
  let user = localStorage.getItem('user');
  let csrf = localStorage.getItem('csrf_token');
  user = (user !== undefined) ? JSON.parse(user) : '';
  (user && Object.keys(user).length && csrf.length) ? next() : next('/login');
}

export const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login,
    // beforeEnter: isAuth,
  },
  {
    path: '/emails',
    name: 'Emails',
    component: Emails,
    beforeEnter: isAuth
  },
  {
    path: '/sms',
    name: 'SMS',
    component: SMS,
    beforeEnter: isAuth
  },
  {
    path: '/hello',
    name: 'Track',
    component: Track,
  },
  {
    path: '/audit',
    name: 'Audit',
    component: Audit,
    beforeEnter: isAuth
  }
  // {
  //   path: '/users',
  //   name: 'Users',
  //   component: Users,
  //   beforeEnter: isAuth
  // },
];