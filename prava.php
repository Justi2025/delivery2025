<?php

use App\Khranilischa\RoliPolzovatelei;

const EmptyRole = [];
const TopRoles = [RoliPolzovatelei::Ahmad, RoliPolzovatelei::ZamAhmada];
const Employees = [RoliPolzovatelei::Ahmad, RoliPolzovatelei::ZamAhmada, RoliPolzovatelei::Voditel, RoliPolzovatelei::Skladovschik, RoliPolzovatelei::Kassir];
const AllRoles = [...Employees, RoliPolzovatelei::Klient];

return [
    "account.address-update" => [...AllRoles],
    "account.avatar-update" => [...AllRoles],
    "account.change-password" => [...AllRoles],
    "account.create_by_employee" => [...TopRoles],

    "auth.change_password" => true,
    "auth.checkCode" => true,
    "auth.login" => true,
    "auth.logout" => true,
    "auth.refresh" => true,
    "auth.register" => true,
    "auth.sendCode" => true,
    "auth.send_recovery_code" => true,

    "bonuses.add" => [...TopRoles],
    "bonuses.withdraw" => [...TopRoles],

    "bot.start" => [],
    "bot.get_message" => [],
    "bot.set_webhook" => [],

    "cities.list" => [...AllRoles],

    "companies.create" => [...TopRoles],
    "companies.index" => [...AllRoles],
    "companies.get" => [...AllRoles],
    "companies.update" => [...TopRoles],

    "customers.get-authenticated" => [...AllRoles],
    "customers.index" => [...TopRoles],
    "customers.create" => [...TopRoles],
    "customers.find_customer" => [...TopRoles, RoliPolzovatelei::Kassir, RoliPolzovatelei::Skladovschik],
    "customer.bonus-history" => [...TopRoles, RoliPolzovatelei::Kassir, RoliPolzovatelei::Klient],
    "customer.completed-orders" => [...TopRoles, RoliPolzovatelei::Kassir, RoliPolzovatelei::Klient],
    "customer.current-orders" => [...TopRoles, RoliPolzovatelei::Kassir, RoliPolzovatelei::Klient],
    "customers.update" => [...TopRoles],
    "customers.profile" => [...TopRoles],

    "delivery_points.create" => [...TopRoles],
    "delivery_points.index" => [...AllRoles],
    "delivery_points.get" => [...TopRoles],
    "delivery_points.update" => [...TopRoles],
    "delivery_points.update-usage-frequency" => [...TopRoles],

    "employees.get_all" => [RoliPolzovatelei::Ahmad],
    "employee.get-by-id" => [...Employees],
    "employees.get_couriers" => [...Employees],
    "employee.update" => [...TopRoles],

    "files.index" => [RoliPolzovatelei::Ahmad],
    "files.search" => [RoliPolzovatelei::Ahmad],
    "files.store" => [RoliPolzovatelei::Ahmad],
    "files.destroy" => [RoliPolzovatelei::Ahmad],

    "dostavka.index" => [...AllRoles],
    "dostavka.pending" => [...TopRoles],
    "dostavka.accepted" => [...TopRoles],
    "dostavka.assign-to-courier" => [...TopRoles],
    "dostavka.assigned-to-courier" => [...TopRoles, RoliPolzovatelei::Voditel],
    "dostavka.received-by-courier" => [...TopRoles, RoliPolzovatelei::Voditel, RoliPolzovatelei::Skladovschik],
    "dostavka.waiting-for-customers" => [...Employees],
    "dostavka.get-by-id" => [...AllRoles],
    "dostavka.get-customer-contact-by-order-id" => [...AllRoles],
    "dostavka.incoming-delivery" => [...TopRoles, RoliPolzovatelei::Klient],
    "dostavka.update-incoming" => [...TopRoles, RoliPolzovatelei::Voditel],
    "dostavka.cancel" => [RoliPolzovatelei::Klient],

    "dostavka.update-barcode-image" => [...TopRoles, RoliPolzovatelei::Klient],
    "dostavka.cancelled" => [...TopRoles],
    "dostavka.get-grouped-by-shipping-from" => [...Employees],
    "dostavka.issued" => [...TopRoles, RoliPolzovatelei::Kassir],
    "dostavka.change-status" => [...Employees],
    "dostavka.upload-photo" => [...Employees],
    "dostavka.refuse-by-courier" => [...TopRoles, RoliPolzovatelei::Voditel],
    "dostavka.change-status-to" => [RoliPolzovatelei::Skladovschik],
    "dostavka.issue" => [...TopRoles, RoliPolzovatelei::Kassir],

    "logs.list" => [RoliPolzovatelei::Ahmad],
    "logs.filter" => [RoliPolzovatelei::Ahmad],
    "logs.ipsinfo" => [RoliPolzovatelei::Ahmad],
    "logs.most-active" => [RoliPolzovatelei::Ahmad],
    "logs.for-user" => [RoliPolzovatelei::Ahmad],

    "reports.debts-by-customers" => [...TopRoles],
    "reports.payments-by-day" => [...TopRoles],
    "reports.payments-by-day-and-pickup-points" => [...TopRoles, RoliPolzovatelei::Kassir],

    "settings.all-settings" => [RoliPolzovatelei::Ahmad],
    "settings.reports-visibility" => [RoliPolzovatelei::Ahmad],
    "settings.set-standard-bonus" => [RoliPolzovatelei::Ahmad],
    "settings.set-vip-bonus" => [RoliPolzovatelei::Ahmad],

    "user-settings.all-settings" => [...AllRoles],
    "user-settings.set-tg-notifications" => [...AllRoles],

    "users.index" => [RoliPolzovatelei::Ahmad],
    "users.update" => [RoliPolzovatelei::Ahmad],
    "users.update-passport" => [RoliPolzovatelei::Klient],
];
