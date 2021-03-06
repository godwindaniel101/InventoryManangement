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


//
import BaseStore from './components/Store/SharedComponent/BaseStoreComponent.vue'
import Product from './components/Store/ProductComponent.vue'
import ProductStock from './components/Store/ProductStockComponent.vue'
import Branch from './components/Store/BranchComponent.vue'




//settings
import BaseSettings from './components/Settings/SharedComponent/BaseSettingsComponent.vue'
import DefaultSettings from './components/Settings/DefaultSettingsComponent.vue'
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
            {
            path: 'store',
              component: BaseStore,
              children: [
                    { path: '', component: Product },
                    { path: 'product', component: Product },
                    { path: 'product_stock', component: ProductStock },
                    { path: 'branch', component: Branch },
                  ]
            },
           {
            path: 'settings',
            component: BaseSettings,
            children:[
              {path : '' , component : DefaultSettings},
              {path : 'default_settings' , component : DefaultSettings}
            ]
           }
            

    ]
  }
];
