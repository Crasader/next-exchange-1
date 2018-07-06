import Vue from 'vue'
import Router from 'vue-router'

require('../bootstrap.js');
let have_token  = false;

const user    = ConfigJS.get_user();

// Containers
import Full from '../containers/Full'

// Views
// import Dashboard from '../views/Dashboard'
import Wallet from '../views/Wallet'
import Orderbook from '../views/Orderbook'
import Markets from '../views/Markets'
import Coin from '../views/Coin'
import Exchange from '../views/Exchange'
import Transactions from '../views/Transactions'
import UserSettings from '../views/UserSettings'
import UserList from '../views/UserList'
import Profile from '../views/profile'
import Blacklist from '../views/Blacklist'
import ChangePassword from '../views/ChangePassword'
import Tokenmarket from '../views/Tokenmarket'
import TokenExchange from '../views/TokenExchange'
import ICO from '../views/ICO'
import Minepool from '../views/Minepool'
import Community from '../views/Community'
import NotFound from '../views/NotFound';
import AccessList from '../views/AccessList';
import CoinDetails from '../views/CoinDetails'

Vue.use(Router);

export default new Router({
  mode: 'history',
  linkActiveClass: 'open active',
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    {
      path: '/',
      redirect: '/markets',
      name: 'Home',
      component: Full,
      children: [
       /* {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard
        },*/
          {
              path: 'wallet',
              name: 'Wallet',
              component: Wallet
          },
          {
              path: 'wallet/:info',
              name: 'Wallet',
              component: Wallet
          },
          {
              path: 'orderbook/:market?/:coin?',
              name: 'Orderbook',
              component: user.have_token ? Orderbook : NotFound
          },
          {
              path: 'markets',
              name: 'Markets',
              component: Markets
          },
          {
              path: 'coin',
              name: 'Coin',
              component: user.have_token ? Coin : NotFound
          },
          {
              path: 'exchange',
              name: 'Exchange',
              component: user.have_token ? Exchange : NotFound
          },
          {
              path: 'transactions',
              name: 'Transactions',
              component: user.have_token ? Transactions : NotFound
          },
          {
              path: 'user/settings',
              name: 'User Settings',
              component: UserSettings
          },
          {
              path: 'user_list',
              name: 'UserList',
              component: UserList
          },
          {
              path: 'change-password',
              name: 'Change Password',
              component: ChangePassword
          },
          {
              path: 'profile',
              name: 'profile',
              component: Profile
          },
          {
              path: 'black_list',
              name: 'Blacklist',
              component: Blacklist
          },
          {
              path: 'tokenmarket',
              name: 'Token Market',
              component: Tokenmarket
          },
          {
              path: 'tokenexchange/:symbol',
              name: 'Token Exchange',
              component: TokenExchange
          },
          {
              path: 'ico',
              name: 'Ico',
              component: ICO
          },
          {
              path: 'minepool',
              name: 'Minepool',
              component: Minepool
          },
          {
              path: 'community',
              name: 'Community',
              component: Community
          },
          {
              path: 'access',
              name: 'AccessList',
              component: AccessList
          },
          {
              path: 'coin-details/:market?/:coin?',
              name: 'CoinDetails',
              component: user.have_token ? CoinDetails : NotFound
          },
      ]
    }
  ]
})
