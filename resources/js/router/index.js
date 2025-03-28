/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
import {createRouter, createWebHistory} from "vue-router";
import store from "../store";
import {Roli} from "../utils/enums/Roli.js";

const AllRoles = [
    Roli.Name.Admin,
    Roli.Name.Manager,
    Roli.Name.Courier,
    Roli.Name.Storekeeper,
    Roli.Name.Operator,
    Roli.Name.Customer,
];

const TopRoles = [
  Roli.Name.Admin,
  Roli.Name.Manager
];

const reports_available = () => {
    return store.getters['authStore/user']?.reports_available;
}

const routes = [
    {
        path: '/signup',
        name: 'signup',
        component: () => import("../stranitsi/SozdatAkkaunt.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: false,
            caption: 'Вход на сервис',
            useSvg: '#house-fill',
            role: [...AllRoles]
        }
    },
    {
        path: '/',
        name: 'login',
        component: () => import("../stranitsi/StranicaVkhodaNaSait.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: false,
            caption: 'Login',
            role: [...AllRoles],
        }
    },
    {
        path: '/password-reset',
        name: 'auth.account_recovery',
        component: () => import("../stranitsi/PasswordChangeRequestForm.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: false,
            caption: 'Заявка на смену пароля',
            role: [...AllRoles]
        }
    },
    {
        path: '/forbidden',
        name: 'forbidden',
        component: () => import("../stranitsi/Zaprescheno.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: false,
            caption: 'Forbidden',
            role: [...AllRoles]
        }
    },
    {
        path: '/dostavka',
        name: 'dostavka.parent-route',
        component: () => import("../stranitsi/sdelki/ZakaziPapa.vue"),
        meta: {
            isParent: true,
            isSidebarItem: true,
            requiresAuth: true,
            caption: 'Доставка',
            useSvg: '#bi-dropbox',
            role: [...AllRoles],
            order: 1.49,
        },
        children: [
            {
                path: '',
                name: 'dostavka',
                component: () => import("../stranitsi/sdelki/VseZakazi.vue"),
                meta: {
                    isSidebarItem: true,
                    requiresAuth: true,
                    caption: 'Все заказы',
                    useSvg: '#bi-box',
                    role: [...TopRoles, Roli.Name.Customer],
                    isChild: true,
                    order: 1.5
                }
            },
            {
                path: 'pending',
                name: 'pending',
                component: () => import("../stranitsi/sdelki/JdutOdobrenia.vue"),
                meta: {
                    isSidebarItem: true,
                    requiresAuth: true,
                    caption: 'Ожидают',
                    useSvg: '#bi-hourglass-split',
                    role: [...TopRoles],
                    isChild: true,
                    order: 1.51
                }
            },
            {
                path: 'accepted',
                name: 'dostavka.accepted',
                component: () => import("../stranitsi/sdelki/PrinatiiZakazi.vue"),
                meta: {
                    isSidebarItem: true,
                    requiresAuth: true,
                    caption: 'Приняты',
                    useSvg: '#bi-check2-square',
                    role: [...TopRoles],
                    isChild: true,
                    order: 1.52
                }
            },

            {
                path: 'accepted/:from_id',
                name: 'accepted.from_id',
                component: () => import("../stranitsi/sdelki/PrinatiiZakazi.vue"),
                meta: {
                    isSidebarItem: false,
                    requiresAuth: true,
                    caption: 'Приняты',
                    useSvg: '',
                    role: [...TopRoles],
                    isChild: true,
                    order: 1.521
                }
            },

            {
                path: 'assigned-to-courier',
                name: 'dostavka.assigned_to_courier',
                component: () => import("../stranitsi/sdelki/NaznacheniNaKuriera.vue"),
                meta: {
                    isSidebarItem: true,
                    requiresAuth: true,
                    caption: 'В работе',
                    useSvg: '#bi-truck',
                    role: [...TopRoles, Roli.Name.Courier],
                    isChild: true,
                    order: 1.53
                }
            },

            {
                path: 'assigned-to-courier/:dp_id',
                name: 'dostavka.assigned_to_courier.dp',
                component: () => import("../stranitsi/sdelki/NaznacheniNaKuriera.vue"),
                meta: {
                    isSidebarItem: false,
                    requiresAuth: true,
                    caption: 'Назначенные курьеру',
                    useSvg: '',
                    role: [...TopRoles, Roli.Name.Courier],
                    isChild: true,
                    order: 1.531
                }
            },

            {
                path: 'received-by-courier',
                name: 'dostavka.received_by_courier',
                component: () => import("../stranitsi/sdelki/PolucheniKurierom.vue"),
                meta: {
                    isSidebarItem: true,
                    requiresAuth: true,
                    caption: 'Получены курьером',
                    useSvg: '#bi-truck',
                    role: [...TopRoles, Roli.Name.Courier, Roli.Name.Storekeeper],
                    isChild: true,
                    order: 1.53
                }
            },

            {
                path: 'received-by-courier/:dp_id',
                name: 'dostavka.received_by_courier.dp',
                component: () => import("../stranitsi/sdelki/PolucheniKurierom.vue"),
                meta: {
                    isSidebarItem: false,
                    requiresAuth: true,
                    caption: 'Получены курьером',
                    useSvg: '',
                    role: [...TopRoles, Roli.Name.Courier, Roli.Name.Storekeeper],
                    isChild: true,
                    order: 1.531
                }
            },

            {
                path: 'waiting-for-customers',
                name: 'waiting-for-customers',
                component: () => import("../stranitsi/sdelki/OjidaiutKlienta.vue"),
                meta: {
                    isSidebarItem: true,
                    requiresAuth: true,
                    caption: 'Готовы к выдаче',
                    useSvg: '#bi-bag',
                    role: [...TopRoles, Roli.Name.Operator, Roli.Name.Storekeeper],
                    isChild: true,
                    order: 1.54
                }
            },
            {
                path: 'issued',
                name: 'issued',
                component: () => import("../stranitsi/sdelki/VidannieZakazi.vue"),
                meta: {
                    isSidebarItem: true,
                    requiresAuth: true,
                    caption: 'Выданные',
                    useSvg: '#bi-bag-check',
                    role: [...TopRoles, Roli.Name.Operator],
                    isChild: true,
                    order: 1.55
                }
            },
            {
                path: 'cancelled',
                name: 'cancelled',
                component: () => import("../stranitsi/sdelki/OtmenennieZakazi.vue"),
                meta: {
                    isSidebarItem: true,
                    requiresAuth: true,
                    caption: 'Отмененные',
                    useSvg: '#bi-trash2',
                    role: [...TopRoles],
                    isChild: true,
                    order: 1.56
                }
            },
        ]
    },
    {
        path: '/dostavka/:id',
        name: 'dostavka.view',
        component: () => import("../stranitsi/sdelki/KartochkaZakaza.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            caption: 'Заказ',
            useSvg: '#bi-box',
            role: [...AllRoles],
            isChild: true,
            order: -1
        }
    },
    {
        path: '/outgoings',
        name: 'outgoings',
        component: () => import("../stranitsi/GlavnaiaStranitca.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            caption: 'Отправка (возврат)',
            useSvg: '#bi-send-check',
            role: [...TopRoles],
            order: 1.6
        }
    },
    {
        path: '/companies',
        name: 'companies',
        component: () => import("../stranitsi/pvz_i_ostalnoe/KompaniiStr.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            caption: 'Компании',
            useSvg: '#geo-alt',
            role: [...TopRoles],
            order: 1.6
        }
    },
    {
        path: '/companies/:id/edit',
        name: 'companies.edit',
        component: () => import("../stranitsi/pvz_i_ostalnoe/KompaniRedaktirovatStr.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            caption: 'Редактирование компании',
            useSvg: '#geo-alt',
            role: [...TopRoles],
            order: 1.6
        }
    },
    {
        path: '/pick-up-points',
        name: 'pickuppoints',
        component: () => import("../stranitsi/pvz_i_ostalnoe/PvzsStranitsa.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            caption: 'ПВЗ и Офисы',
            useSvg: '#geo-alt',
            role: [...TopRoles],
            order: 1.6
        }
    },
    {
        path: '/pick-up-points/:id/edit',
        name: 'pickuppoints.edit',
        component: () => import("../stranitsi/pvz_i_ostalnoe/PvzStranitsaRedaktorovania.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            caption: 'Редактирование ПВЗ',
            useSvg: '#geo-alt',
            role: [...TopRoles],
            order: 1.61
        }
    },
    {
        path: '/customer/bonus-history',
        name: 'customer.bonus-history-page',
        component: () => import("../stranitsi/klienti/IstoriaBonusovStr.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            caption: 'История бонусов',
            useSvg: '#bi-gift',
            role: [Roli.Name.Customer],
            order: 1.62
        }
    },
    {
        path: '/reports',
        name: 'reports',
        component: () => import("../stranitsi/otcheti/StranitsaOtchetov.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#file-earmark-text',
            caption: 'Отчеты',
            role: [Roli.Name.Admin, reports_available() && Roli.Name.Manager, Roli.Name.Operator],
            order: 100,
        }
    },
    {
        path: '/employees',
        name: 'employees',
        component: () => import("../stranitsi/sotrudniki/SotrudnikiStr.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#bi-file-person',
            caption: 'Сотрудники',
            role: [Roli.Name.Admin],
            order: 4
        }
    },
    {
        path: '/employees/:id',
        name: 'employee.profile',
        component: () => import("../stranitsi/sotrudniki/ProfilSotrudnikaStr.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            useSvg: '#bi-file-person',
            caption: 'Сотрудники',
            role: [Roli.Name.Admin],
            order: 4.5
        },
        children: [

        ]
    },
    {
        path: '/customers',
        name: 'customers',
        component: () => import("../stranitsi/klienti/VseKlienti.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#people',
            caption: 'Клиенты',
            role: [...TopRoles],
            order: 4
        }
    },
    {
        path: '/customers/:id',
        //name: 'customers.profile',
        component: () => import("../stranitsi/klienti/KlientStranitsa.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            useSvg: '#people',
            caption: 'Карточка клиента',
            role: [...TopRoles],
        },
        children: [
            {
                path: '',
                name: 'customers.profile',
                component: () => import("../stranitsi/klienti/ProfilPolzovateliaStr.vue"),
                meta: {
                    isSidebarItem: false,
                    requiresAuth: true,
                    useSvg: '#people',
                    caption: 'Карточка клиента',
                    role: [...TopRoles, Roli.Name.Operator]
                },
            },
            {
                path: 'current-orders',
                name: 'customers.current_orders',
                component: () => import("../stranitsi/klienti/TekuschieZakazi.vue"),
                meta: {
                    isSidebarItem: false,
                    requiresAuth: true,
                    useSvg: '#people',
                    caption: 'Текущие заказы',
                    role: [...TopRoles, Roli.Name.Operator]
                },
            },
            {
                path: 'completed-orders',
                name: 'customers.completed-orders',
                component: () => import("../stranitsi/klienti/ZavershennieZakazi.vue"),
                meta: {
                    isSidebarItem: false,
                    requiresAuth: true,
                    useSvg: '#people',
                    caption: 'Выполненные заказы',
                    role: [...TopRoles, Roli.Name.Operator]
                },
            },
            {
                path: 'bonus-history',
                name: 'customers.bonus-history',
                component: () => import("../stranitsi/klienti/IstoriaBonusov.vue"),
                meta: {
                    isSidebarItem: false,
                    requiresAuth: true,
                    useSvg: '#people',
                    caption: 'История бонусов',
                    role: [...TopRoles, Roli.Name.Operator]
                },
            }
        ]
    },
    {
        path: '/customers/:id/edit',
        name: 'customers.edit',
        component: () => import("../stranitsi/klienti/RedaktirovanieKlientaStr.vue"),
        meta: {
            isSidebarItem: false,
            requiresAuth: true,
            useSvg: '#people',
            caption: 'Редактирование клиента',
            role: [Roli.Name.Admin]
        },
    },
    {
        path: '/files',
        name: 'files',
        component: () => import("../stranitsi/faili/FilesPage.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#bi-file-earmark-image',
            caption: 'Файлы',
            role: [Roli.Name.Admin],
            order: 101
        }
    },
    {
        path: '/logs',
        name: 'logs',
        component: () => import("../stranitsi/UsersActivityLogsPage.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#bi-activity',
            caption: 'Активность',
            role: [Roli.Name.Admin],
            order: 6
        }
    },
    {
        path: '/profile',
        name: 'profile',
        component: () => import("../stranitsi/lichni_kabinet/ProfilStr.vue"),
        meta: {
            isSidebarItem: true,
            requiresAuth: true,
            useSvg: '#gear-wide-connected',
            caption: 'Профиль',
            role: [...AllRoles],
            order: 7
        }
    },
    {
        path: '/logout',
        name: 'logout',
        component: async () => {
            await store.dispatch('authStore/logout').then(() => {

                setTimeout(() => {
                    window.location.href = '/';
                }, 100)

            });

            return {template: '<p>Logout</p>'};
        },
        meta: {
            isSidebarItem: true,
            useSvg: '#door-closed',
            requiresAuth: true,
            caption: 'Выйти',
            role: [...AllRoles],
            order: 8
        }
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        meta: {
            requiresAuth: false,
        },
        component: () => import("../stranitsi/NeNaideno.vue")
    },
];


const
    router = createRouter({
        history: createWebHistory(),
        routes,
        scrollBehavior() {
            return {top: 0}
        },
    });


router.beforeEach((to, from, next) => {
    
    store.commit('SET_QUERY_STRING', to.query);

    const {user, isUserAuthenticated} = store.state.authStore;
    
    if (isUserAuthenticated && to.matched.some(r => ['login', 'signup'].includes(r.name))) {
        return next({name: 'dostavka'});
    }

    if (to.matched.some((route) => route.meta?.requiresAuth)) {
        if (isUserAuthenticated) {

            if (to.meta?.role.includes(user.user_role))
                next()
            else
                next({name: 'forbidden'})
        } else {
            next({name: 'login'})
        }
    } else {
        next()
    }
});

export default router;
