import Register from './components/Authentication/RegisterComponent.vue'
import Dashboard from './components/Authentication/DashboardComponent.vue'
import BaseDashboard from './components/Authentication/BaseDashboardComponent.vue'



//sales
import BaseSales from './components/Sales/SharedComponent/BaseSalesComponent.vue'
import MakeSales from './components/Sales/MakeSalesComponent.vue'
import TodaySales from './components/Sales/TodaySalesComponent.vue'
import ViewSales from './components/Sales/ViewSalesComponent.vue'


//purchase
import BasePurchase from './components/Purchase/SharedComponent/BasePurchaseComponent.vue'
import MakePurchase from './components/Purchase/MakePurchaseComponent.vue'
import TodayPurchase from './components/Purchase/TodayPurchaseComponent.vue'
import ViewPurchase from './components/Purchase/ViewPurchaseComponent.vue'


export const routes = [
  { path: '/', component: Register },

  {
    path: '/dashboard',
    component: Dashboard,
    children: [
          { path: '', component: BaseDashboard },
            {
              path: 'sales',
              component: BaseSales,
              children: [
                    { path: '', component: MakeSales },
                    { path: 'makesales', component: MakeSales },
                    { path: 'todaysales', component: TodaySales },
                    { path: 'viewsales', component: ViewSales }
                  ]
            },
            {
              path: 'purchase',
              component: BasePurchase,
              children: [
                    { path: '', component: MakePurchase },
                    { path: 'makepurchase', component: MakePurchase },
                    { path: 'todaypurchase', component: TodayPurchase },
                    { path: 'viewpurchase', component: ViewPurchase }
                  ]
            },
            

    ]
  }
];
// export const routes = [
//   { path: '/', component: Register },

//   {
//     path: '/dashboard',
//     component: Dashboard,
//     children: [
//       { path: '', component: BaseDashboard },
//       { path: 'makesales', component: MakeSales },
//       { path: 'todaysales', component: TodaySales },
//       { path: 'viewsales', component: ViewSales }
//     ]
//   }
// ];
