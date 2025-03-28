/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <obertka-adminki>
    <zagolovok-stranitc :caption="caption">
      <router-link :to="previous_page()" class="btn btn-sm btn-outline-warning">Вернуться</router-link>
    </zagolovok-stranitc>

    <div class="modal fade" id="barcodesImageViewModal" tabindex="-1" v-touch:swipe.top="barcodeModalClose">
      <div class="modal-dialog modal-lg modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Штрих-код ({{ order?.barcodes?.length }} шт.)</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="barcodeImageViewModalButton">
            </button>
          </div>
          <div class="modal-body d-flex justify-content-center">
            <slider :images="order?.barcodes" />
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="barcodeImageViewModal" tabindex="-1">
      <div class="modal-dialog modal-lg modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Штрих-код</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="barcodeImageViewModalButton">
            </button>
          </div>
          <div class="modal-body d-flex justify-content-center">
            <img class="img-fluid object-fit-contain"
                 style="max-height: 600px"
                 alt="Штрихкод заказа"
                 :src="upload_path(order?.barcode_image)"
                 v-if="order?.barcode_image && !barcode_image_file"
            />
            <img class="img-fluid" style="max-height: 200px" alt="Выбранный штрих-код" :src="barcode_image" v-else/>
          </div>
          <div class="modal-footer" v-if="!barcode_image_update_closed">
            <file-chooser caption="Выбрать изображение" class="btn btn-sm btn-secondary" accept="image/*"
                          @on-file-choose="onBarcodeImageSelected"/>
            <!--            <file-upload caption="Обновить штрих-код" class="btn btn-sm btn-success" :disabled="!barcode_image" />-->
            <input type="button" class="btn btn-sm btn-success" value="Обновить штрих-код" :disabled="!barcode_image"
                   @click="onBarcodeImageUpdate"/>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="orderPhotoModal" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Фото заказа</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body d-flex justify-content-center">
            <img class="img-fluid w-50" alt="Выбранное фото заказа" :src="order_photo"/>
          </div>
        </div>
      </div>
    </div>


    <div class="alert alert-warning" role="alert"
         v-if="barcode_updated_every_day && is_order_barcode_alert_message_visible">
      Штрих-код для данного ПВЗ обновляется ежедневно.
      Для того, чтобы заказ можно было бы забрать, необходимо прикрепить актуальный штрих-код
    </div>

    <div class="alert alert-warning" role="alert" v-if="is_amount_to_pay_for_customer_visible">
      ⚡️ Заказ нужно оплатить за клиента. Сумма - {{ money(order?.amount_to_pay_for_customer) }}
    </div>

    <div class="card">

      <div class="card-header d-flex justify-content-between" v-if="is_courier || is_storekeeper">
        <div :style="{backgroundColor: order?.shipment_from_color, color: order?.shipment_from_label_color}"
             style="padding: 3px; font-size: .85rem" class="rounded-2">
          <span>{{ order?.shipment_from }}</span>
        </div>
        <div>
          <span :style="{backgroundColor: order?.shipment_to_color, color: order?.shipment_to_label_color}"
                style="padding: 3px; font-size: .85rem" class="rounded-2">
          {{ order?.shipment_to_short_name ?? order?.shipment_to }}
          </span>
        </div>
      </div>

      <div :class="`card-header d-flex justify-content-between bg-${order_status?.clazz}-subtle`" v-else>
        <div :class="`badge bg-${order_status?.clazz} d-inline-block`">{{ order_status?.caption }}</div>
        <div class="text-secondary">{{ dt_format2(order?.created_at) }}</div>
      </div>

      <div class="card-body">

        <table class="table table-striped table-hover">
          <tbody>
          <tr v-if="false">
            <td class="text-secondary">Статус</td>
            <td>{{ orderStatusTranslate(selected_order_status) }}</td>
          </tr>
          <tr v-if="is_card_row_visible('dp_from')">
            <td class="text-secondary">Откуда</td>
            <td>
              <span :style="{backgroundColor: order?.shipment_from_color, color: order?.shipment_from_label_color}"
                    style="padding: 3px; font-size: .85rem" class="rounded-2">
                <span v-if="is_employee">
                  {{ order?.shipment_from }}
                </span>
                <span v-else>
                    {{ order?.shipment_from_company }},
                    {{ order?.shipment_from_city }},
                    {{ order?.shipment_from_address }}
                </span>
              </span>
            </td>
          </tr>
          <tr v-if="is_card_row_visible('dp_to')">
            <td class="text-secondary">Куда</td>
            <!-- v-if="is_admin_or_manager" -->
            <td>
               <span :style="{backgroundColor: order?.shipment_to_color, color: order?.shipment_to_label_color}"
                     style="padding: 3px; font-size: .85rem" class="rounded-2">
                 {{ order?.shipment_to_short_name ?? order?.shipment_to }}
               </span>
            </td>
            <!--<td v-else>
              {{ order?.shipment_to }}&nbsp;{{ destination_type }}
            </td>-->
          </tr>
          <template v-if="!is_customer">
            <tr v-if="!is_courier">
              <td class="text-secondary">Курьер</td>
              <td>{{ shrink_fullname(order?.courier) || '-' }}</td>
            </tr>
            <tr>
              <td class="text-secondary">Клиент</td>
              <td>
                <span v-if="is_courier">{{ order?.customer }}</span>
                <router-link :to="url('customers.profile', { id: order?.customer_id })" v-else>{{
                    order?.customer
                  }}
                </router-link>
              </td>
            </tr>
          </template>
          <tr v-if="is_employee">
            <td class="text-secondary">Контактный номер</td>
            <td>
              <span class="badge bg-primary cursor-pointer" @click="showCustomerContact" v-if="!contact">
                Посмотреть номер
              </span>
              <span v-else>{{ contact }} | <a :href="'tel:+' + contact" target="_blank">Позвонить</a></span>
            </td>
          </tr>
          <tr v-if="order?.pickup_only_paid">
            <td class="text-secondary">Опции</td>
            <td>
              <span class="badge bg-danger cursor-pointer">
                Забрать только оплаченные
              </span>
            </td>
          </tr>
          <tr v-if="order?.barcodes?.length > 0">
            <td class="text-secondary">Штрих-код(ы)</td>
            <td>
              <button
                  type="button"
                  class="btn btn-sm btn-danger"
                  data-bs-toggle="modal"
                  data-bs-target="#barcodesImageViewModal"
                  value="Просмотреть штрих-коды"
              >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upc" viewBox="0 0 16 16">
                  <path d="M3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0z"/>
                </svg>
              </button>
            </td>
          </tr>
          <tr v-if="order?.barcode_image">
            <td class="text-secondary">Штрих-код</td>
            <td>
              <img class="img-fluid img-thumbnail barcode-image"
                   alt="Штрихкод заказа"
                   data-bs-toggle="modal" data-bs-target="#barcodeImageViewModal"
                   :src="upload_path(order?.barcode_image)"
                   v-if="order?.barcode_image && !barcode_image"
              />
              <img
                  data-bs-toggle="modal" data-bs-target="#barcodeImageViewModal"
                  class="img-fluid img-thumbnail barcode-image"
                  alt="Выбранный штрих-код" :src="barcode_image" v-else/>
            </td>
          </tr>
          <tr>
            <td class="text-secondary">Код получения</td>
            <td>{{ order?.barcode_text || '-' }}</td>
          </tr>
          <tr v-if="is_element_available('order_bonuses')">
            <td class="text-secondary">Начисленные бонусы</td>
            <td>{{ order?.order_bonuses || 0 }}</td>
          </tr>
          <tr v-if="is_element_available('amount_to_pay')">
            <td class="text-secondary">Сумма к оплате</td>
            <td>
              <span :class="{'bg-warning-subtle p-1 rounded-2': amount_to_pay > 0}">
                {{ money(amount_to_pay) || '-' }}
              </span>
            </td>
          </tr>
          <tr v-if="is_element_available('price')">
            <td class="text-secondary">Стоимость доставки/отправки</td>
            <td>{{ money(order?.price) || '-' }}</td>
          </tr>
          <tr v-if="order?.additional_payment">
            <td class="text-secondary">Доплата за ПВЗ</td>
            <td>
              <span class="bg-success p-1 rounded-2">
                {{ money(order?.additional_payment) }}
              </span>
            </td>
          </tr>
          <tr v-if="is_element_available('client_debt_in_dp')">
            <td class="text-secondary"> Курьер оплатил за клиента на ПВЗ</td>
            <td @dblclick="onCustomerDebtChange" @blur="onCustomerDebtFocusLost">
              {{ money(order?.client_debt) || (is_employee ? '👉 Нажмите сюда два раза' : '-') }}
            </td>
          </tr>
          <tr v-if="is_element_available('paid_total')">
            <td class="text-secondary">Уплачено</td>
            <td>
              Всего: {{ money(paid_total) }}<br/>
              Наличными: {{ money(order?.amount_paid_cash) }}<br/>
              Безналичными: {{ money(order?.amount_paid_cashless) }}<br/>
              Бонусами: {{ order?.amount_paid_bonus }}<br/>
            </td>
          </tr>
          <tr v-if="is_element_available('common_debt')">
            <td class="text-secondary" title="Задолженность, которую клиент доложен погасить">
              Неоплаченный остаток
            </td>
            <td>
              <span :class="{'bg-danger p-1 rounded-2': common_debt > 0}">
                {{ money(common_debt) }}
              </span>
            </td>
          </tr>
          <tr v-if="is_courier">
            <td class="text-secondary">Создан</td>
            <td>{{ dt_format2(order?.created_at) || '-' }}</td>
          </tr>
          <tr v-if="is_element_available('received_at')">
            <td class="text-secondary">Получен курьером</td>
            <td>{{ dt_format2(order?.received_at) || '-' }}</td>
          </tr>
          <tr v-if="is_element_available('issued_at')">
            <td class="text-secondary">Выдан клиенту</td>
            <td>{{ dt_format2(order?.issued_at) || '-' }}</td>
          </tr>
          </tbody>
        </table>

      </div>

      <!-- Customer -->
      <div class="card-footer" v-if="is_order_cancellation_available_for_customer">
        <div class="col-md-auto">
          <button class="btn btn-sm w-100 btn-outline-danger" @click="cancelOrderX">Отменить</button>
        </div>
      </div>
    </div>

    <div class="row" v-if="is_courier && order_history?.length > 0">

      <div class="col-md-12">
        <hr class="fw-bold mt-4 mb-2">
      </div>

      <div class="col-md-12">
        <courier-order-status-card :item="item" v-for="item in order_history" @on-photo-click="onOrderHistoryPhotoClick" />
      </div>
    </div>

    <!-- Change status -->
    <div class="card my-3" v-if="!is_customer">

      <div class="card-header" v-if="is_admin_or_manager && show_couriers_select">
        <div class="row g-3">
          <!-- <div class="col-auto">
            <select class="form-select form-select-sm" v-model="selected_order_status"
                    title="Выберите статус">
              <option disabled value="">Выберите статус</option>
              <option :value="status.code" v-for="status in order_statuses" :key="status.code">
                {{ status.caption }}
              </option>
            </select>
          </div>-->
          <div class="col-auto">
            <select class="form-select form-select-sm w-auto" @change="onCourierChange" v-model="order.courier_id"
                    v-if="show_couriers_select">
              <option :value="null" disabled selected>Выберите курьера</option>
              <option value="0" v-if="is_status_assigned_to_courier">Отменить для курьеров</option>
              <option :value="courier.id" v-for="courier in couriers">
                {{ courier.value }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <template v-if="is_courier && is_status_assigned_to_courier">
        <div class="card-body">
          <div class="row mt-2 mb-4" v-if="order_status_images_data?.length > 0">
            <div class="col me-3" @click="onOrderPhotoClick">
              <div class="d-inline-block position-relative me-2"
                   v-for="(image, counter) in order_status_images_data" :data-name="image.name"
              >
                <remove-icon class="text-danger cursor-pointer position-absolute" @click="onOrderPhotoRemove"/>
                <img class="img-fluid img-thumbnail barcode-image me-2"
                     alt="Фото заказа"
                     data-bs-toggle="modal"
                     data-bs-target="#orderPhotoModal"
                     :data-name="image.name"
                     :src="image.data"
                     :key="counter"
                />
              </div>
            </div>
          </div>
          <div class="row g-3 justify-content-between">
            <div class="col-sm-12 col-md-auto">
              <file-chooser id="choose_barcode" caption="Добавить фото"
                            :class="`btn btn-sm btn-outline-${order_status.clazz}`"
                            accept="image/*"
                            @on-file-choose="onOrderPhotoSelected"
                            v-if="is_element_available('photo_add')"
              />
            </div>
            <div class="col-sm-12 col-md-auto">
              <div class="row g-3 justify-content-between">
                <div class="col-sm-12 col-md-auto" v-if="is_element_available('postpone_button')">
                  <button class="btn btn-sm w-100 btn-outline-warning" @click="postponeOrder">Отложить</button>
                </div>
                <div class="col-sm-12 col-md-auto">
                  <button
                      class="w-100"
                      :class="`btn btn-sm btn-${order_status.clazz}`" @click="onOrderStatusChange"
                      :disabled="order_status_change_disabled">
                    {{ changeOrderStatusButtonLabel }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row gy-3">
            <div class="col-md-12">
              <textarea class="form-control" rows="3" placeholder="Комментарий..." required
                        v-model="comment"></textarea>
            </div>

            <div class="col-sm-12 col-md-auto" v-if="is_element_available('cancel_button')">
              <button class="btn btn-sm w-100 btn-outline-danger" @click="cancelOrder">Отменить</button>
            </div>

            <!-- courier -->
            <div class="col-md-12" v-if="is_element_available('client_debt')">
              <label for="client_debt" class="form-label">Долг клиента</label>
              <input type="number" class="form-control" min="0" v-model="client_debt" id="client_debt"
                     @focus="onClientDebtFocused">
            </div>

            <!-- storekeeper -->
            <div class="col-md-2" v-if="is_element_available('weight')">
              <label for="weight" class="form-label">Вес (кг)</label>
              <input type="number" class="form-control" min="0" v-model="weight" id="weight">
            </div>

            <div class="col-md-4" v-if="is_element_available('price_list')">
              <label for="price_list" class="form-label">Прайс</label>
              <select class="form-select" @change="" v-model="tariff">
                <option value="" disabled selected>Выберите прайс</option>
                <option :value="item" v-for="item in prices_list" :key="item.id">
                  {{ item.name }}
                </option>
              </select>
            </div>

            <div class="col-md-2" v-if="is_element_available('service_price')">
              <label for="service_price" class="form-label">Стоимость доставки</label>
              <input type="number" class="form-control" min="0" v-model="_service_price" id="service_price">
            </div>

            <div class="col-md-4" v-if="is_element_available('wh_cell')">
              <label for="warehouse_cells" class="form-label">Ячейки полки</label>
              <input type="text" class="form-control" v-model="warehouse_cells" id="warehouse_cells">
            </div>

            <!-- operator -->
            <div class="col-md-3" v-if="is_element_available('amount_paid_cash')">
              <label for="amount_paid_cash" class="form-label">Наличными</label>
              <input type="number" class="form-control" min="0" v-model="amount_paid_cash" id="amount_paid_cash">
            </div>

            <div class="col-md-3" v-if="is_element_available('amount_paid_cashless')">
              <label for="amount_paid_cashless" class="form-label">Безналичным</label>
              <input type="number" class="form-control" min="0" v-model="amount_paid_cashless"
                     id="amount_paid_cashless">
            </div>

            <div class="col-md-3" v-if="is_element_available('amount_paid_bonus')">
              <label for="amount_paid_bonus" class="form-label">Бонусами (макс. {{ order?.customer_bonuses }})</label>
              <input type="number" class="form-control" min="0" :max="order?.customer_bonuses"
                     v-model="amount_paid_bonus"
                     id="amount_paid_bonus">
            </div>

            <div class="col-md-3" v-if="is_element_available('amount_deposited')">
              <label for="amount_deposited" class="form-label">Сумма оплаты</label>
              <input type="number" class="form-control" min="0" v-model="amount_deposited" id="amount_deposited">
            </div>

          </div>
        </div>
      </template>

      <!-- for all another roles of employees -->
      <template v-else>
        <div class="card-body px-3">
          <div class="row gy-3">
            <div class="col-md-12">
              <textarea class="form-control" rows="3" placeholder="Комментарий..." required
                        v-model="comment"></textarea>
            </div>

            <!-- courier -->
            <div class="col-md-12" v-if="is_element_available('client_debt')">
              <label for="client_debt" class="form-label">Долг клиента</label>
              <input type="number" class="form-control" min="0" v-model="client_debt" id="client_debt"
                     @focus="onClientDebtFocused">
            </div>

            <!-- storekeeper -->
            <div class="col-md-2" v-if="is_element_available('weight')">
              <label for="weight" class="form-label">Вес (кг)</label>
              <input type="number" class="form-control" min="0" v-model="weight" id="weight">
            </div>

            <div class="col-md-4" v-if="is_element_available('price_list')">
              <label for="price_list" class="form-label">Прайс</label>
              <select class="form-select" @change="" v-model="tariff">
                <option value="" disabled selected>Выберите прайс</option>
                <option :value="item" v-for="item in prices_list" :key="item.id">
                  {{ item.name }}
                </option>
              </select>
            </div>

            <div class="col-md-2" v-if="is_element_available('service_price')">
              <label for="service_price" class="form-label">Стоимость доставки</label>
              <input type="number" class="form-control" min="0" v-model="_service_price" id="service_price">
            </div>

            <div class="col-md-4" v-if="is_element_available('wh_cell')">
              <label for="warehouse_cells" class="form-label">Ячейки полки</label>
              <input type="text" class="form-control" v-model="warehouse_cells" id="warehouse_cells">
            </div>

            <!-- operator -->
            <div class="col-md-3" v-if="is_element_available('amount_paid_cash')">
              <label for="amount_paid_cash" class="form-label">Наличными</label>
              <input type="number" class="form-control" min="0" v-model="amount_paid_cash" id="amount_paid_cash">
            </div>

            <div class="col-md-3" v-if="is_element_available('amount_paid_cashless')">
              <label for="amount_paid_cashless" class="form-label">Безналичным</label>
              <input type="number" class="form-control" min="0" v-model="amount_paid_cashless"
                     id="amount_paid_cashless">
            </div>

            <div class="col-md-3" v-if="is_element_available('amount_paid_bonus')">
              <label for="amount_paid_bonus" class="form-label">Бонусами (макс. {{ order?.customer_bonuses }})</label>
              <input type="number" class="form-control" min="0" :max="order?.customer_bonuses"
                     v-model="amount_paid_bonus"
                     id="amount_paid_bonus">
            </div>

            <div class="col-md-3" v-if="is_element_available('amount_deposited')">
              <label for="amount_deposited" class="form-label">Сумма оплаты</label>
              <input type="number" class="form-control" min="0" v-model="amount_deposited" id="amount_deposited">
            </div>

          </div>
          <div class="row mt-4 mb-2" v-if="order_status_images_data?.length > 0">
            <div class="col me-3" @click="onOrderPhotoClick">
              <div class="d-inline-block position-relative me-2"
                   v-for="(image, counter) in order_status_images_data" :data-name="image.name"
              >
                <remove-icon class="text-danger cursor-pointer position-absolute" @click="onOrderPhotoRemove"/>
                <img class="img-fluid img-thumbnail barcode-image me-2"
                     alt="Фото заказа"
                     data-bs-toggle="modal"
                     data-bs-target="#orderPhotoModal"
                     :data-name="image.name"
                     :src="image.data"
                     :key="counter"
                />
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer" v-if="is_order_action_buttons_visible">
          <div class="row g-3 justify-content-between">
            <div class="col-sm-12 col-md-auto">
              <file-chooser id="choose_barcode" caption="Добавить фото"
                            :class="`btn btn-sm btn-outline-${order_status.clazz}`"
                            accept="image/*"
                            @on-file-choose="onOrderPhotoSelected"
                            v-if="is_element_available('photo_add')"
              />
            </div>
            <div class="col-sm-12 col-md-auto">
              <div class="row g-3 justify-content-between">
                <div class="col-sm-12 col-md-auto" v-if="is_element_available('postpone_button')">
                  <button class="btn btn-sm w-100 btn-outline-warning" @click="postponeOrder">Отложить</button>
                </div>
                <div class="col-sm-12 col-md-auto" v-if="is_element_available('cancel_button')">
                  <button class="btn btn-sm w-100 btn-outline-danger" @click="cancelOrder">Отменить</button>
                </div>
                <div class="col-sm-12 col-md-auto">
                  <button
                      class="w-100"
                      :class="`btn btn-sm btn-${order_status.clazz}`" @click="onOrderStatusChange"
                      :disabled="order_status_change_disabled">
                    {{ changeOrderStatusButtonLabel }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>

    </div>

    <!-- Order history -->
    <div class="mt-4 row" v-if="is_order_statuses_history_visible">
      <div class="col-md-12">
        <h4 class="h4">История заказа</h4>
      </div>
    </div>

    <hr class="fw-bold mt-2 mb-4" v-if="is_order_statuses_history_visible">

    <template v-if="is_order_statuses_history_visible">
      <div class="card my-3 position-relative" v-for="item in order_history">

        <div class="card-header d-flex justify-content-between" v-if="is_comment_element_visible('header', item)">
          <div><span class="text-secondary">
            Статус: </span>
            {{ orderStatusTranslate(item?.status) }}
          </div>
          <div class="text-secondary">{{ dt_format2(item?.created_at) }}</div>
        </div>

        <div class="card-body px-3">
          <template v-if="is_comment_element_visible('author', item)">
            <p class="text-secondary mb-1">Автор:</p>
            <p>{{ item?.full_name }}</p>
          </template>

          <p class="text-secondary mb-1">Комментарий:</p>
          <p v-html="order_history_message(item)" v-if="is_comment_element_visible('comment_text', item)"></p>

          <div class="row my-3" v-if="item.files?.length > 0">
            <div class="col" @click="onOrderHistoryPhotoClick">
              <img class="img-fluid img-thumbnail barcode-image me-2"
                   alt="Фото заказа"
                   data-bs-toggle="modal"
                   data-bs-target="#orderPhotoModal"
                   :data-image="upload_path(file.generated_name)"
                   v-for="file in item.files"
                   :src="upload_path(file.generated_name)"
                   :key="file.generated_name"
              />
            </div>

          </div>

        </div>

      </div>
    </template>


  </obertka-adminki>
</template>

<script>
import ZagolovokStranitc from "../../komponenti/ZagolovokStranitc.vue";
import ObertkaAdminki from "../../komponenti/ObertkaAdminki.vue";
import {
  defaultType,
  getStatusById,
  OrderStatus,
  statusi_zakaza,
  orderStatusTranslate
} from "../../utils/zakazi/statusi_zakaza.js";
import {servicSdelok} from "../../api/sdelki/SdelkiServis.js";
import FileChooser from "../../komponenti/ViborFaila.vue";
import prochitaFailKakUrlDannie from "../../utils/readAsDataUrl.js";
import FileUpload from "../../komponenti/ZagruzkaFileKnopka.vue";
import {mapGetters, mapState} from "vuex";
import {calculatePrice, DefaultTariff, tceni} from "./obschie/tceni.js";
import {kartaDostupnostiElementovKartochkiZakaza} from "./obschie/kartaDostupnostiElementovKartochkiZakaza.js";
import {soobscheniaStatusovSdelok} from "./obschie/soobscheniaStatusovSdelok.js";
import {nl2br} from "../../utils/nl2br.js";
import {izmenitRazmerIzobrajenia} from "../../utils/resizeImage.js";
import {pngToJpeg} from "../../utils/failovie_raznie.js";
import shrink_fullname from "../../utils/shrink_fullname.js";
import RemoveIcon from "../../komponenti/ikonki/RemoveIcon.vue";
import CourierOrderStatusCard from "./komponenti/CourierOrderStatusCard.vue";
import Slider from "../../komponenti/Slider.vue";

export default {
  name: "OrderCard",
  components: {Slider, CourierOrderStatusCard, RemoveIcon, FileUpload, FileChooser, ObertkaAdminki, ZagolovokStranitc},

  data() {
    return {
      list: [],
      order: null,
      order_statuses: [],
      isFormOpen: false,

      selected_order_status: null,

      cols: {
        order_no: 'Номер',
        order_status: 'Статус',
        shipment_from: 'Откуда',
        shipment_to: 'Куда',
        courier: 'Курьер',
        customer: 'Клиент',
        amount_to_pay: 'Сумма к оплате',
        price: 'Цена',
        debt: 'Долг',
        received_at: 'Получено',
        issued_at: 'Выдано',
      },

      // couriers: [],

      barcode_image_file: null,
      barcode_image: null,

      comment: '',
      client_debt: '',
      weight: '',
      tariff: DefaultTariff,
      service_price: '',
      amount_paid_cash: '',
      amount_paid_cashless: '',
      amount_paid_bonus: '',
      warehouse_cells: '',

      order_status_images: [],
      order_status_images_data: [],
      order_photo: '',

      prices_list: tceni,
      contact: '',

      order_cancelled: false,
      order_postponed: false,
      courier_assigned_to_order: null,
    }
  },

  async mounted() {

    await this.loadOrder(this.$route.params.id);
    this.initForm();

    this.order_statuses = statusi_zakaza();
    this.selected_order_status = this.order.order_status;

    // clean component state when modal closed
    const barcodeModalEl = document.getElementById('barcodeImageViewModal');
    barcodeModalEl.addEventListener('hidden.bs.modal', this.cleanBarcodeImage);

    // disable zooming
    document.addEventListener('touchmove', this.disablePageZooming);
  },


  beforeUnmount() {
    const barcodeModalEl = document.getElementById('barcodeImageViewModal');
    barcodeModalEl.removeEventListener('hidden.bs.modal', this.cleanBarcodeImage);

    document.removeEventListener('touchmove', this.disablePageZooming);
  },

  computed: {
    OrderStatus() {
      return OrderStatus
    },
    ...mapState(['couriers']),
    ...mapGetters('authStore', ['user']),

    caption() {
      const order_no = this.format_order_no(this.$route.params.id);
      const created_at = this.dt_format_ddmmyyy(this.order?.created_at);
      //return `${this.$route.meta.caption} #${order_no} от ${created_at}`;
      return `${this.$route.meta.caption} #${order_no}`;
    },

    is_amount_to_pay_for_customer_visible() {
      return this.is_employee &&
          this.order?.amount_to_pay_for_customer &&
          ![OrderStatus.IssuedToCustomer].includes(this.order?.order_status);
    },

    is_order_cancellation_available_for_customer() {
      return this.is_customer && this.order?.order_status === OrderStatus.Waiting;
    },

    is_order_statuses_history_visible() {
      return this.order_history?.length > 0 && !this.is_courier;
    },

    order_history() {
      const sort_desc = (h1, h2) => h2.id - h1.id;

      if (this.is_courier) {
        return this.order?.history.filter(item => item?.comment || item?.files.length !== 0).sort(sort_desc);
      }

      return this.is_employee ? this.order?.history.sort(sort_desc) : this.order?.history;
    },

    is_order_issued_on_loan() {
      return this.amount_deposited < (this.order?.price + this.order?.client_debt + this.order?.additional_payment);
    },

    paid_total() {
      return this.order?.amount_paid_cash + this.order?.amount_paid_cashless + this.order?.amount_paid_bonus;
    },

    common_debt() {
      return (this.order?.price + this.order?.client_debt + this.order?.additional_payment) - this.paid_total;
    },

    changeOrderStatusButtonLabel() {
      const status = this.order?.order_status;

      switch (status) {
        case OrderStatus.Waiting:
          return 'Принять';

        case OrderStatus.Accepted:
          return 'Назначить курьеру';

        case OrderStatus.AssignedToCourier:
          return this.cancelOrderForCouriers ? 'Вернуть в принятые' : 'Забрать';

          // Todo: is courier should see received orders buttons?
        case OrderStatus.ReceivedByCourier:
          if (this.is_courier && this.is_delivery_to_customer_home_or_address) {
            return this.is_order_issued_on_loan ? 'Выдать в долг' : 'Выдать';
          }
          return 'Готов к выдаче';

        case OrderStatus.WaitingForCustomers:
          return this.is_order_issued_on_loan ? 'Выдать в долг' : 'Выдать';

        case OrderStatus.IssuedPartiallyPaid:
          return 'Закрыть долг';

        case OrderStatus.IssuedToCustomer:
          return 'Выдан';

        case OrderStatus.CustomerDebtClosed:
          return 'Долг закрыт';

        case OrderStatus.Postponed:
          return 'Принять';
      }
    },

    cancelOrderForCouriers() {
      return +this.order?.courier_id === 0;
    },

    is_order_barcode_alert_message_visible() {
      return ![
        OrderStatus.ReceivedByCourier,
        OrderStatus.Waiting,
        OrderStatus.IssuedPartiallyPaid,
        OrderStatus.IssuedToCustomer,
        OrderStatus.CustomerDebtClosed,
        OrderStatus.Canceled
      ].includes(this.order?.order_status)
    },

    is_order_action_buttons_visible() {

      const status = this.order?.order_status;

      switch (status) {
        case OrderStatus.IssuedToCustomer:
        case OrderStatus.CustomerDebtClosed:
        case OrderStatus.Canceled:
          return false;

        default:
          return true
      }
    },

    is_order_attribute_visible() {
      if (this.is_courier) {
        if (this.order?.order_status === OrderStatus.AssignedToCourier) {

        }
      }
    },

    _service_price: {
      get() {
        return (this.tariff && calculatePrice(this.weight, this.tariff)) || this.service_price;
      },
      set(value) {
        return this.service_price = value;
      }

    },

    destination_type() {
      const dst = {
        1: 'Склад',
        2: 'Дом',
        3: 'Адрес клиента'
      }[this.order?.destination_type] || '';

      return dst ? `(${dst})` : '';
    },

    is_delivery_to_customer_home_or_address() {
      return [2, 3].includes(this.order?.destination_type);
    },

    order_status() {
      return getStatusById(this.selected_order_status) || defaultType;
    },

    amount_to_pay() {
      return Number(this.service_price) + Number(this.client_debt) + Number(this.order?.additional_payment);
    },

    amount_deposited() {
      return this.amount_paid_cash + this.amount_paid_cashless + this.amount_paid_bonus;
    },

    show_couriers_select() {
      // Todo: check this
      return this.is_admin_or_manager && ([OrderStatus.Accepted, OrderStatus.AssignedToCourier].includes(this.order?.order_status));
    },

    is_status_assigned_to_courier() {
      return this.order?.order_status === OrderStatus.AssignedToCourier;
    },

    status_issued_to_customer() {
      return this.order?.order_status === OrderStatus.IssuedToCustomer;
    },

    order_status_change_disabled() {
      if((this.order?.order_status === OrderStatus.IssuedPartiallyPaid)
          && this.amount_deposited < this.amount_to_pay)
        return true;

      return (this.order?.order_status === OrderStatus.IssuedToCustomer) && !this.is_admin_or_manager;
    },

    order_status_is_issued() {
      return [OrderStatus.IssuedToCustomer, OrderStatus.IssuedPartiallyPaid].includes(this.order?.order_status);
    },

    barcode_image_update_closed() {
      return [
        OrderStatus.ReceivedByCourier,
        OrderStatus.IssuedToCustomer,
        OrderStatus.IssuedPartiallyPaid,
        OrderStatus.WaitingForCustomers,
        OrderStatus.CustomerDebtClosed,
        OrderStatus.Canceled
      ].includes(this.order?.order_status);
    },

    barcode_updated_every_day() {
      return this.order?.dp_from_barcode_changing &&
          ![OrderStatus.ReceivedByCourier,
            OrderStatus.WaitingForCustomers,
            OrderStatus.IssuedToCustomer,
            OrderStatus.IssuedPartiallyPaid
          ].includes(this.order?.order_status);
    },
  },

  methods: {
    shrink_fullname,
    orderStatusTranslate,

    is_card_row_visible(row_name) {

      if (row_name === 'dp_from') {

        if(this.is_courier) {
          return false;
        }

        if (this.is_storekeeper) {
          return false;
        }
        return true;
      }

      if (row_name === 'dp_to') {
        if(this.is_courier) {
          return false;
        }

        if (this.is_storekeeper) {
          return false;
        }
        return true;
      }

      return false;
    },


    // elements - header, author, body, comment_text
    is_comment_element_visible(element, item) {
      if (this.is_courier) {
        switch (element) {
          case 'header':
            return false;
          case 'author':
            return false;
          case 'body':
            return true;
          case 'comment_text':
            return item?.comment?.trim() !== '';
        }
      }

      if (this.is_customer) {
        switch (element) {
          case 'header':
            return true;
          case 'author':
            return false;
          case 'body':
            return true;
        }
      }

      if (this.is_storekeeper) {
        switch (element) {
          case 'header':
            return false;
          case 'author':
            return true;
          case 'body':
            return true;
          case 'comment_text':
            return item?.comment?.trim() !== '';
        }
      }

      return true;
    },

    /**
     * @link https://stackoverflow.com/a/61167156
     * @param e
     */
    disablePageZooming(e) {
      if (e.scale !== 1) e.preventDefault();
    },

    barcodeModalClose() {
      const closeButton = document.getElementById('barcodeImageViewModalButton');
      closeButton.click();
    },

    onClientDebtFocused() {
      if (this.client_debt === 0) {
        this.client_debt = '';
      }
    },

    async showCustomerContact() {
      const {data} = await servicSdelok().pokazatNomerKlienta(this.order?.id);
      this.contact = data.phone;
    },

    order_history_message(item) {
      if (!item?.comment) {
        return soobscheniaStatusovSdelok[item?.status] || ''
      }
      return nl2br(item?.comment); // Todo: XSS???!!!
    },

    is_element_available(element) {

      // Todo: can throw errors - add appropriate checks
      const map = kartaDostupnostiElementovKartochkiZakaza[this.role];
      //console.log(element, ' - ', map[element]);
      return typeof map[element] === 'function' ? map[element](this.order) : map[element];
    },

    cleanBarcodeImage() {
      this.barcode_image_file = null;
      // this.barcode_image = null;
    },

    async loadOrder(id) {
      const {data} = await servicSdelok().getById(id);
      this.order = data;
      // this.selected_order_status = data?.status_code;
    },

    initForm() {
      this.client_debt = this.order?.client_debt || '';
      this.weight = this.order?.weight || '';
      this.service_price = this.order?.price || '';
      this.amount_paid_cash = this.order?.amount_paid_cash || '';
      this.amount_paid_cashless = this.order?.amount_paid_cashless || '';
      this.amount_paid_bonus = this.order?.amount_paid_bonus || '';
      this.warehouse_cells = this.order?.warehouse_cells || '';
    },

    async onOrderPhotoSelected(file) {

      prochitaFailKakUrlDannie(file)
          .then(async (result) => {

            /*resizeImage(result, 550, file)
                .then(data => {
                  this.order_status_images_data = [...this.order_status_images_data, data];
                });*/

            this.order_status_images_data = [...this.order_status_images_data, {name: file.name, data: result}];

            // not resize image
            const blob = await pngToJpeg(file);
            const image = new File([blob], 'order_image.jpg', {type: 'image/jpeg'});
            this.order_status_images = [...this.order_status_images, {name: file.name, data: image}];
          })
          .catch((error) => {
            alert(error);
          });


    },

    onOrderPhotoClick(e) {
      if (!(e.target instanceof HTMLImageElement)) return;
      const image = this.order_status_images_data.find(image => image.name === e.target.dataset.name);

      if (image) {
        this.order_photo = image.data;
      }
    },

    onOrderHistoryPhotoClick(e) {
      this.order_photo = e.target.dataset.image;
    },

    onOrderPhotoRemove(e) {
      const image_name = e.target.closest('div').dataset.name;

      if (image_name) {
        this.order_status_images = this.order_status_images.filter(image => image.name !== image_name);
        this.order_status_images_data = this.order_status_images_data.filter(image => image.name !== image_name);
      }
    },

    toggleForm() {
      this.isFormOpen = !this.isFormOpen;
    },


    async onCourierChange(e) {
      const data = {order_ids: [this.$route.params.id], courier_id: +e.target.value};
      this.courier_assigned_to_order = data;

      if (data.courier_id === 0) {

        if(!confirm('Вы действительно хотите отменить заказ для курьеров?')) return;

        await servicSdelok()
            .otklonitSdelku(+this.$route.params.id)
            .then(res => {
              if(res.data.result) {
                this.redirect_to('dostavka.assigned_to_courier');
              }
            });
      }
      else {
        await servicSdelok()
            .naznachitNaKuriera(data.courier_id, data.order_ids)
      }
      /*.then(() => {
        alert('Курьер успешно назначен на заказ');
      })
      .catch(() => {
        alert('Невозможно назначить курьера на заказ');
      })*/
    },

    async cancelOrder() {

      if(this.is_courier) {
        await servicSdelok()
            .otklonitSdelku(+this.$route.params.id)
            .then(res => {
              if(res.data.result) {
                this.redirect_to('dostavka.assigned_to_courier');
              }
            });
        return;
      }

      if(this.is_storekeeper && this.order?.order_status === OrderStatus.WaitingForCustomers) {

        if(!confirm("Вы действительно хотите отменить заказ (заказ вернется в статус получено курьером)?")) return;

        await servicSdelok()
            .izmenitStatusNa(+this.$route.params.id, OrderStatus.ReceivedByCourier)
            .then(res => {
              if(res.data.result) {
                this.$router.go()
                // reload page
                //this.redirect_to('dostavka.assigned_to_courier');
              }
            });
        return;
      }

      this.order_cancelled = true;
      await this.onOrderStatusChange();
    },

    async cancelOrderX() {

      if(!confirm("Вы действительно хотите отменить заказ?")) return;

      const order_data = {
        order_id: +this.$route.params.id,
        //status: this.order?.order_status,
        //old_status: this.order?.order_status,
        comment: ''
      };

      const {data} = await servicSdelok().otmenitSdelku(order_data);

      if(data.result) {
        this.$router.go(-1);
      }
    },

    // Todo: change this
    async postponeOrder() {
      this.order_postponed = true;
      await this.onOrderStatusChange();
    },

    next_status(current_status) {

      if (this.order_cancelled)
        return OrderStatus.Canceled;

      if (this.order_postponed)
        return OrderStatus.Postponed;

      switch (current_status) {
        case OrderStatus.Postponed:
          return OrderStatus.Accepted;

        case OrderStatus.Waiting:
          return OrderStatus.Accepted;

        case OrderStatus.Accepted:
          return OrderStatus.AssignedToCourier;

        case OrderStatus.AssignedToCourier:
          return this.cancelOrderForCouriers ? OrderStatus.Accepted : OrderStatus.ReceivedByCourier;

        case OrderStatus.ReceivedByCourier:
          if (this.is_courier && this.is_delivery_to_customer_home_or_address) {
            return this.is_order_issued_on_loan ? OrderStatus.IssuedPartiallyPaid : OrderStatus.IssuedToCustomer;
          }
          return OrderStatus.WaitingForCustomers;

        case OrderStatus.WaitingForCustomers:
          return this.is_order_issued_on_loan ? OrderStatus.IssuedPartiallyPaid : OrderStatus.IssuedToCustomer;

        case OrderStatus.IssuedPartiallyPaid:
          return OrderStatus.CustomerDebtClosed;

        case OrderStatus.CustomerDebtClosed:
          return null;

        case OrderStatus.IssuedToCustomer:
          return null; // Todo: change this
      }
    },

    async onOrderStatusChange() {

      if (!confirm('Вы действительно хотите изменить статус заказа?')) return;

      if (this.amount_paid_bonus > this.order?.customer_bonuses) {
        alert('Максимальное количество бонусов доступных для оплаты заказа составляет: ' + this.order?.customer_bonuses);
        return;
      }


      const order_data = {
        order_id: +this.$route.params.id,
        status_code: this.next_status(this.order?.order_status),
        old_status: this.order?.order_status,
        comment: this.comment,
        weight: this.weight,
        client_debt: this.client_debt,
        price: this._service_price,
        amount_paid_cash: this.amount_paid_cash,
        amount_paid_cashless: this.amount_paid_cashless,
        amount_paid_bonus: this.amount_paid_bonus,
        warehouse_cells: this.warehouse_cells,
        photos: this.order_status_images.map(image => image.data),
        // photos: [...this.order_status_images.map(photo => photo.id)]
      };

      /*console.log(order_data);
      return;*/


      const statusIsAssigned2Courier = this.next_status(this.order?.order_status) === OrderStatus.AssignedToCourier;

      if (!this.order_cancelled && this.is_admin_or_manager) {
        // this.selected_order_status === OrderStatus.AssignedToCourier;

        if (statusIsAssigned2Courier && !this.order.courier_id) {
          alert('Выберите, пожалуйста, курьера!');
          return;
        }
      }

      let message = '';

      try {

        /*if (statusIsAssigned2Courier) {
          await incomingOrdersService()
              .assignToCourier(this.courier_assigned_to_order.courier_id, this.courier_assigned_to_order.order_ids);
        }*/


        const res = await servicSdelok().izmenitStatusSdelki(order_data);

        if (res.data) {

          // Todo: update old order status to reflect server status
          // this.order.order_status = order_data.old_status;

          const history_item = {
            status: order_data.status_code,
            created_at: Date.now(),
            full_name: this.user.full_name,
            comment: order_data.comment,
            files: this.order_status_images.map(image => image.data),
          }

          this.order?.history?.push(history_item);
          this.comment = '';
          this.photos = [];
          message = 'Статус заказа изменен';

          if (![OrderStatus.IssuedToCustomer, OrderStatus.ReceivedByCourier].includes(order_data.status_code)
          ) {
            alert(message);
          }

          this.$router.go(-1); // redirect to previous page

        } else {
          message = 'Ошибка при изменении статуса заказа';
          alert(message);
        }
      } catch (e) {
        message = e?.response?.data?.message;
        alert(message);
      }
    },

    onBarcodeImageSelected(file) {

      prochitaFailKakUrlDannie(file)
          .then(async (result) => {

            izmenitRazmerIzobrajenia(result, 650, file)
                .then(data => this.barcode_image = data);

            this.barcode_image_file = await pngToJpeg(file);
          })
          .catch((error) => {
            console.error(error);
          });
    },

    async onBarcodeImageUpdate() {
      const data = {
        order_id: this.$route.params.id,
        barcode_image: this.barcode_image_file,
      }

      try {
        const res = await servicSdelok().obnovitShtrikhKod(data);

        if (res.data.result) {
          // this.order.barcode_image = this.barcode_image;
          alert("Штрих-код обновлен успешно");
        }
      } catch (e) {
        alert(e)
      }

    },

    onCustomerDebtChange(e) {
      const el = e.target;
      el.setAttribute('contenteditable', 'true');
      el.focus();
      const value = el.innerText.replace('₽', '').trim().replace(' ', '');
      const customer_debt = Number(value);

      //console.log(customer_debt, value);

      if (customer_debt === 0 || isNaN(customer_debt)) {
        el.innerText = '';
      } else {
        el.innerText = value;
      }

    },

    async onCustomerDebtFocusLost(e) {
      const el = e.target;
      const value = Number(el.innerText);

      if (isNaN(value)) {
        alert('Должно быть число!');
        return;
      }

      const {data} = await servicSdelok().obnovitSdelku({
        order_id: this.order?.id,
        customer_debt: value
      });

      if (data.result) {
        el.innerText = `${value} ₽`;
        this.order.client_debt = value;
        this.client_debt = value;
        el.removeAttribute('contenteditable');
      }
    }
  },
}
</script>

<style scoped>
.barcode-image {
  width: 64px;
  cursor: pointer;
}

svg {
  top: -10px;
  right: -5px;
  width: 22px;
  height: 22px;
}

</style>