<template>
  <div class="content-wrapper">
    <div class="nav-wrapper" :class="fold ? 'nav-wrapper-fold' :''">
      <div class="nav-detail">
        <div class="nav-logo"></div>
        <div class="nav-name">
          <p>{{!fold ? 'Vance Inventry' : 'VI'}}</p>
        </div>
      </div>
      <ul>
        <router-link to="/dashboard" tag="li">
          <div class="nav-list" @click="openDiv(6)">
            <div class="nav-icon">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="icon icon-tabler icon-tabler-home"
                width="30"
                height="30"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="#fff"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path stroke="none" d="M0 0h24v24H0z" />
                <polyline points="5 12 3 12 12 3 21 12 19 12" />
                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
              </svg>
            </div>
            <div class="nav-text">
              <p>Dashboard</p>
            </div>
          </div>
        </router-link>
        <li>
          <div class="nav-list" @click="openDiv(1)">
            <div class="nav-icon">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="icon icon-tabler icon-tabler-sort-ascending"
                width="30"
                height="30"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="#fff"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path stroke="none" d="M0 0h24v24H0z" />
                <line x1="4" y1="6" x2="11" y2="6" />
                <line x1="4" y1="12" x2="11" y2="12" />
                <line x1="4" y1="18" x2="13" y2="18" />
                <polyline points="15 9 18 6 21 9" />
                <line x1="18" y1="6" x2="18" y2="18" />
              </svg>
            </div>
            <div class="nav-text">
              <p>Sales</p>
            </div>
            <div class="nav-indicator">
              <div class="indicator-icon" :class=" openDrop.fold_1 ? 'indicator-icon-fold':''">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="icon icon-tabler icon-tabler-chevron-left"
                  width="30"
                  height="30"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="#fff"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path stroke="none" d="M0 0h24v24H0z" />
                  <polyline points="15 6 9 12 15 18" />
                </svg>
              </div>
            </div>
          </div>
          <div class="Controldrop" :class="openDrop.fold_1 ? 'Controldrop-open' :' '">
            <ul>
              <router-link to="/dashboard/sales/makesales" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-shopping-cart"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="2"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <circle cx="9" cy="19" r="2" />
                      <circle cx="17" cy="19" r="2" />
                      <path d="M3 3h2l2 12a3 3 0 0 0 3 2h7a3 3 0 0 0 3 -2l1 -7h-15.2" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">Make Sale</div>
                </div>
              </router-link>
              <router-link to="/dashboard/sales/viewsales" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-arrows-maximize"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="1"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <polyline points="16 4 20 4 20 8" />
                      <line x1="14" y1="10" x2="20" y2="4" />
                      <polyline points="8 20 4 20 4 16" />
                      <line x1="4" y1="20" x2="10" y2="14" />
                      <polyline points="16 20 20 20 20 16" />
                      <line x1="14" y1="14" x2="20" y2="20" />
                      <polyline points="8 4 4 4 4 8" />
                      <line x1="4" y1="4" x2="10" y2="10" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">View Sales</div>
                </div>
              </router-link>
              <router-link to="/dashboard/sales/todaysales" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-clipboard"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="2"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <path
                        d="M9 5H7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2V7a2 2 0 0 0 -2 -2h-2"
                      />
                      <rect x="9" y="3" width="6" height="4" rx="2" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">Today Sale</div>
                </div>
              </router-link>
            </ul>
          </div>
        </li>
        <li>
          <div class="nav-list" @click="openDiv(2)">
            <div class="nav-icon">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="icon icon-tabler icon-tabler-sort-descending"
                width="30"
                height="30"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="#fff"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path stroke="none" d="M0 0h24v24H0z" />
                <line x1="4" y1="6" x2="13" y2="6" />
                <line x1="4" y1="12" x2="11" y2="12" />
                <line x1="4" y1="18" x2="11" y2="18" />
                <polyline points="15 15 18 18 21 15" />
                <line x1="18" y1="6" x2="18" y2="18" />
              </svg>
            </div>
            <div class="nav-text">
              <p>Purchase</p>
            </div>
            <div class="nav-indicator">
              <div class="indicator-icon" :class=" openDrop.fold_2 ? 'indicator-icon-fold':''">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="icon icon-tabler icon-tabler-chevron-left"
                  width="30"
                  height="30"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="#fff"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path stroke="none" d="M0 0h24v24H0z" />
                  <polyline points="15 6 9 12 15 18" />
                </svg>
              </div>
            </div>
          </div>
          <div class="Controldrop" :class="openDrop.fold_2 ? 'Controldrop-open' :' '">
            <ul>
              <router-link to="/dashboard/purchase/makepurchase" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-shopping-cart"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="2"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <circle cx="9" cy="19" r="2" />
                      <circle cx="17" cy="19" r="2" />
                      <path d="M3 3h2l2 12a3 3 0 0 0 3 2h7a3 3 0 0 0 3 -2l1 -7h-15.2" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">Make Purchase</div>
                </div>
              </router-link>
              <router-link to="/dashboard/purchase/viewpurchase" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-arrows-maximize"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="1"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <polyline points="16 4 20 4 20 8" />
                      <line x1="14" y1="10" x2="20" y2="4" />
                      <polyline points="8 20 4 20 4 16" />
                      <line x1="4" y1="20" x2="10" y2="14" />
                      <polyline points="16 20 20 20 20 16" />
                      <line x1="14" y1="14" x2="20" y2="20" />
                      <polyline points="8 4 4 4 4 8" />
                      <line x1="4" y1="4" x2="10" y2="10" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">View Purchase</div>
                </div>
              </router-link>
              <router-link to="/dashboard/purchase/todaypurchase" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-clipboard"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="2"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <path
                        d="M9 5H7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2V7a2 2 0 0 0 -2 -2h-2"
                      />
                      <rect x="9" y="3" width="6" height="4" rx="2" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">Today Purchase</div>
                </div>
              </router-link>
            </ul>
          </div>
        </li>
        <li>
          <div class="nav-list" @click="openDiv(3)">
            <div class="nav-icon">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="icon icon-tabler icon-tabler-stack"
                width="30"
                height="30"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="#fff"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path stroke="none" d="M0 0h24v24H0z" />
                <polyline points="12 4 4 8 12 12 20 8 12 4" />
                <polyline points="4 12 12 16 20 12" />
                <polyline points="4 16 12 20 20 16" />
              </svg>
            </div>
            <div class="nav-text">
              <p>Store</p>
            </div>
            <div class="nav-indicator">
              <div class="indicator-icon" :class=" openDrop.fold_3 ? 'indicator-icon-fold':''">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="icon icon-tabler icon-tabler-chevron-left"
                  width="30"
                  height="30"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="#fff"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path stroke="none" d="M0 0h24v24H0z" />
                  <polyline points="15 6 9 12 15 18" />
                </svg>
              </div>
            </div>
          </div>
          <div class="Controldrop" :class="openDrop.fold_3 ? 'Controldrop-open' :' '">
            <ul>
              <router-link to="/dashboard/store/product" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-shopping-cart"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="2"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <circle cx="9" cy="19" r="2" />
                      <circle cx="17" cy="19" r="2" />
                      <path d="M3 3h2l2 12a3 3 0 0 0 3 2h7a3 3 0 0 0 3 -2l1 -7h-15.2" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">Product</div>
                </div>
              </router-link>
              <router-link to="#" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-arrows-maximize"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="1"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <polyline points="16 4 20 4 20 8" />
                      <line x1="14" y1="10" x2="20" y2="4" />
                      <polyline points="8 20 4 20 4 16" />
                      <line x1="4" y1="20" x2="10" y2="14" />
                      <polyline points="16 20 20 20 20 16" />
                      <line x1="14" y1="14" x2="20" y2="20" />
                      <polyline points="8 4 4 4 4 8" />
                      <line x1="4" y1="4" x2="10" y2="10" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">View Sales</div>
                </div>
              </router-link>
              <router-link to="#" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-clipboard"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="2"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <path
                        d="M9 5H7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2V7a2 2 0 0 0 -2 -2h-2"
                      />
                      <rect x="9" y="3" width="6" height="4" rx="2" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">Today Sale</div>
                </div>
              </router-link>
            </ul>
          </div>
        </li>
        <li>
          <div class="nav-list" @click="openDiv(4)">
            <div class="nav-icon">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="icon icon-tabler icon-tabler-clipboard-check"
                width="30"
                height="30"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="#fff"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path stroke="none" d="M0 0h24v24H0z" />
                <path
                  d="M9 5H7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2V7a2 2 0 0 0 -2 -2h-2"
                />
                <rect x="9" y="3" width="6" height="4" rx="2" />
                <path d="M9 14l2 2l4 -4" />
              </svg>
            </div>
            <div class="nav-text">
              <p>Report</p>
            </div>
            <div class="nav-indicator">
              <div class="indicator-icon" :class=" openDrop.fold_4 ? 'indicator-icon-fold':''">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="icon icon-tabler icon-tabler-chevron-left"
                  width="30"
                  height="30"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="#fff"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path stroke="none" d="M0 0h24v24H0z" />
                  <polyline points="15 6 9 12 15 18" />
                </svg>
              </div>
            </div>
          </div>
          <div class="Controldrop" :class="openDrop.fold_4 ? 'Controldrop-open' :' '">
            <ul>
              <router-link to="#" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-shopping-cart"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="2"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <circle cx="9" cy="19" r="2" />
                      <circle cx="17" cy="19" r="2" />
                      <path d="M3 3h2l2 12a3 3 0 0 0 3 2h7a3 3 0 0 0 3 -2l1 -7h-15.2" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">Make Sale</div>
                </div>
              </router-link>
              <router-link to="#" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-arrows-maximize"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="1"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <polyline points="16 4 20 4 20 8" />
                      <line x1="14" y1="10" x2="20" y2="4" />
                      <polyline points="8 20 4 20 4 16" />
                      <line x1="4" y1="20" x2="10" y2="14" />
                      <polyline points="16 20 20 20 20 16" />
                      <line x1="14" y1="14" x2="20" y2="20" />
                      <polyline points="8 4 4 4 4 8" />
                      <line x1="4" y1="4" x2="10" y2="10" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">View Sales</div>
                </div>
              </router-link>
              <router-link to="#" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-clipboard"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="2"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <path
                        d="M9 5H7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2V7a2 2 0 0 0 -2 -2h-2"
                      />
                      <rect x="9" y="3" width="6" height="4" rx="2" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">Today Sale</div>
                </div>
              </router-link>
            </ul>
          </div>
        </li>
        <li>
          <div class="nav-list" @click="openDiv(5)">
            <div class="nav-icon">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="icon icon-tabler icon-tabler-settings"
                width="30"
                height="30"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="#fff"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path stroke="none" d="M0 0h24v24H0z" />
                <path
                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                />
                <circle cx="12" cy="12" r="3" />
              </svg>
            </div>
            <div class="nav-text">
              <p>Settings</p>
            </div>
            <div class="nav-indicator">
              <div class="indicator-icon" :class=" openDrop.fold_5 ? 'indicator-icon-fold':''">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="icon icon-tabler icon-tabler-chevron-left"
                  width="30"
                  height="30"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="#fff"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path stroke="none" d="M0 0h24v24H0z" />
                  <polyline points="15 6 9 12 15 18" />
                </svg>
              </div>
            </div>
          </div>
          <div class="Controldrop" :class="openDrop.fold_5 ? 'Controldrop-open' :' '">
            <ul>
              <router-link to="#" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-shopping-cart"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="2"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <circle cx="9" cy="19" r="2" />
                      <circle cx="17" cy="19" r="2" />
                      <path d="M3 3h2l2 12a3 3 0 0 0 3 2h7a3 3 0 0 0 3 -2l1 -7h-15.2" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">Make Sale</div>
                </div>
              </router-link>
              <router-link to="#" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-arrows-maximize"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="1"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <polyline points="16 4 20 4 20 8" />
                      <line x1="14" y1="10" x2="20" y2="4" />
                      <polyline points="8 20 4 20 4 16" />
                      <line x1="4" y1="20" x2="10" y2="14" />
                      <polyline points="16 20 20 20 20 16" />
                      <line x1="14" y1="14" x2="20" y2="20" />
                      <polyline points="8 4 4 4 4 8" />
                      <line x1="4" y1="4" x2="10" y2="10" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">View Sales</div>
                </div>
              </router-link>
              <router-link to="#" tag="li">
                <div class="nav-link-Controldrop">
                  <div class="nav-icon-Controldrop">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="icon icon-tabler icon-tabler-clipboard"
                      width="25"
                      height="25"
                      viewBox="0 0 24 24"
                      stroke-width="2"
                      stroke="#fff"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path stroke="none" d="M0 0h24v24H0z" />
                      <path
                        d="M9 5H7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2V7a2 2 0 0 0 -2 -2h-2"
                      />
                      <rect x="9" y="3" width="6" height="4" rx="2" />
                    </svg>
                  </div>
                  <div class="nav-text-Controldrop">Today Sale</div>
                </div>
              </router-link>
            </ul>
          </div>
        </li>
        <router-link to="#" tag="li">
          <div class="nav-list">
            <div class="nav-icon">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="icon icon-tabler icon-tabler-power"
                width="30"
                height="30"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="#fff"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path stroke="none" d="M0 0h24v24H0z" />
                <path d="M7 6a7.75 7.75 0 1 0 10 0" />
                <line x1="12" y1="4" x2="12" y2="12" />
              </svg>
            </div>
            <div class="nav-text">
              <p>Logout</p>
            </div>
          </div>
        </router-link>
      </ul>
    </div>
    <div class="page-wrapper" :class="fold?'paper-wrapper-fold':''">
      <div class="page-content">
        <div class="page-header">
          <div class="page-header-control" @click="getfolded()">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="icon icon-tabler icon-tabler-menu"
              width="44"
              height="44"
              viewBox="0 0 24 24"
              stroke-width="1"
              stroke="#2c3e50"
              fill="none"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path stroke="none" d="M0 0h24v24H0z" />
              <line x1="4" y1="3" x2="20" y2="3" />
              <line x1="4" y1="6" x2="20" y2="6" />
              <line x1="4" y1="9" x2="20" y2="9" />
              <line x1="4" y1="12" x2="20" y2="12" />
            </svg>
          </div>
          <div class="page-header-control" @click="logoutUser()">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="icon icon-tabler icon-tabler-power"
              width="30"
              height="30"
              viewBox="0 0 24 24"
              stroke-width="1"
              stroke="#3F51B5"
              fill="none"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path stroke="none" d="M0 0h24v24H0z" />
              <path d="M7 6a7.75 7.75 0 1 0 10 0" />
              <line x1="12" y1="4" x2="12" y2="12" />
            </svg>
          </div>
        </div>
        <div class="page-body">
          <router-view></router-view>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Form, HasError, AlertError } from "vform";
// Vue.component(HasError.name, HasError)
// Vue.component(AlertError.name, AlertError)
export default {
  data() {
    return {
      fold: false,
      openDrop: {
        fold_1: false,
        fold_2: false,
        fold_3: true,
        fold_4: false,
        fold_5: false
      }
    };
  },
  methods: {
    closeAll() {
      var a = this.openDrop;
      a.fold_1 = false;
      a.fold_2 = false;
      a.fold_3 = false;
      a.fold_4 = false;
      a.fold_5 = false;
    },
    openDiv(id) {
      //   this.closeAll()
      if (this.fold) {
        this.fold = false;
      } else {
      }

      if (id == 1) {
        this.openDrop.fold_1 = !this.openDrop.fold_1;
      }
      if (id == 2) {
        this.openDrop.fold_2 = !this.openDrop.fold_2;
      }
      if (id == 3) {
        this.openDrop.fold_3 = !this.openDrop.fold_3;
      }
      if (id == 4) {
        this.openDrop.fold_4 = !this.openDrop.fold_4;
      }
      if (id == 5) {
        this.openDrop.fold_5 = !this.openDrop.fold_5;
      }
    },
    getfolded() {
      this.closeAll();
      this.fold = !this.fold;
    },
    logoutUser() {
      localStorage.clear();
      return this.$router.push("/");
    },
    checkUser() {
      this.$Progress.start();
      var token = localStorage.getItem("access_token");
      if (token != null) {
        axios
          .get("api/checkuser", {
            headers: {
              authorization: "Bearer " + token
            }
          })
          .then(response => {
            if (response) {
              return this.$router.push("/dashboard");
            }
          })
          .catch(response => {
            console.log(response);
          });
        this.$Progress.finish();
      } else {
        return this.$router.push("/");
        this.$Progress.fail();
      }
    }
  },

  mounted() {
    // this.checkUser();
  }
};
</script>
