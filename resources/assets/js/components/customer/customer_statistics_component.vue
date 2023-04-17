<template>
    
   
  <div class="row">
    <!--  order  -->
    <div class="d-flex align-items-start flex-column mb-3 col-md-3">
      <div class="col-12 p-3 bg-white block-wrapper">
        <div class="d-flex align-items-start flex-column box-content">
          <div class="p-2 theme-color-blue">
            {{ $t("Total No. of Orders") }}
          </div>
          <div class="mt-auto p-2">
            <span class="text-headline">
              <span class="theme-color-blue">{{ count_data.total_order }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex align-items-start flex-column mb-3 col-md-3">
      <div class="col-12 p-3 bg-white block-wrapper">
        <div class="d-flex align-items-start flex-column box-content">
          <div class="p-2 theme-color-blue">
            {{ $t("Total Order Amount(SAR)") }}
          </div>
          <div class="mt-auto p-2">
            <span class="text-headline">
              <span class="theme-color-blue">{{ count_data.total_order_amount
}}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex align-items-start flex-column mb-3 col-md-3">
      <div class="col-12 p-3 bg-white block-wrapper">
        <div class="d-flex align-items-start flex-column box-content">
          <div class="p-2 theme-color-blue">
            {{ $t("Total No. of Returned Orders") }}
          </div>
          <div class="mt-auto p-2">
            <span class="text-headline">
              <span class="theme-color-blue">{{ count_data.total_return_order }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex align-items-start flex-column mb-3 col-md-3">
      <div class="col-12 p-3 bg-white block-wrapper">
        <div class="d-flex align-items-start flex-column box-content">
          <div class="p-2 theme-color-blue">
            {{ $t("Total Returned Order Amount(SAR)") }}
          </div>
          <div class="mt-auto p-2">
            <span class="text-headline">
              <span class="theme-color-blue">{{ count_data.total_return_order_amount }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <!--  invoice  -->
    <div class="d-flex align-items-start flex-column mb-3 col-md-3">
      <div class="col-12 p-3 bg-white block-wrapper">
        <div class="d-flex align-items-start flex-column box-content">
          <div class="p-2 theme-color-blue">
            {{ $t("Total No. of Invoices") }}
          </div>
          <div class="mt-auto p-2">
            <span class="text-headline">
              <span class="theme-color-blue">{{ count_data.total_invoice }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex align-items-start flex-column mb-3 col-md-3">
      <div class="col-12 p-3 bg-white block-wrapper">
        <div class="d-flex align-items-start flex-column box-content">
          <div class="p-2 theme-color-blue">
            {{ $t("Total Invoice Amount(SAR)") }}
          </div>
          <div class="mt-auto p-2">
            <span class="text-headline">
              <span class="theme-color-blue">{{ count_data.total_invoice_amount
                }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex align-items-start flex-column mb-3 col-md-3">
      <div class="col-12 p-3 bg-white block-wrapper">
        <div class="d-flex align-items-start flex-column box-content">
          <div class="p-2 theme-color-blue">
            {{ $t("Total No. of Returned Invoices") }}
          </div>
          <div class="mt-auto p-2">
            <span class="text-headline">
              <span class="theme-color-blue">{{ count_data.total_return_invoice }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex align-items-start flex-column mb-3 col-md-3">
      <div class="col-12 p-3 bg-white block-wrapper">
        <div class="d-flex align-items-start flex-column box-content">
          <div class="p-2 theme-color-blue">
            {{ $t("Total Returned Invoice Amount(SAR)") }}
          </div>
          <div class="mt-auto p-2">
            <span class="text-headline">
              <span class="theme-color-blue">{{ count_data.total_return_invoice_amount }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <!--  other  -->
    <div class="d-flex align-items-start flex-column mb-3 col-md-3">
      <div class="col-12 p-3 bg-white block-wrapper">
        <div class="d-flex align-items-start flex-column box-content">
          <div class="p-2 theme-color-blue">
            {{ $t("Last Order On") }}
          </div>
          <div class="mt-auto p-2">
            <span class="text-headline">
              <span class="theme-color-blue">{{ count_data.last_order_date }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex align-items-start flex-column mb-3 col-md-3">
      <div class="col-12 p-3 bg-white block-wrapper">
        <div class="d-flex align-items-start flex-column box-content">
          <div class="p-2 theme-color-blue">
            {{ $t("Favorite Product") }}
          </div>
          <div class="mt-auto p-2">
            <span class="text-headline">
              <span class="theme-color-blue">{{ count_data.fav_product }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex align-items-start flex-column mb-3 col-md-3">
      <div class="col-12 p-3 bg-white block-wrapper">
        <div class="d-flex align-items-start flex-column box-content">
          <div class="p-2 theme-color-blue">
            {{ $t("Favourite Branch") }}
          </div>
          <div class="mt-auto p-2">
            <span class="text-headline">
              <span class="theme-color-blue">{{ count_data.fav_store }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex align-items-start flex-column mb-3 col-md-3">
      <div class="col-12 p-3 bg-white block-wrapper">
        <div class="d-flex align-items-start flex-column box-content">
          <div class="row">
            <div class="col-4">
              <div class="p-2 theme-color-blue">
                {{ $t("Pending Invoices") }}
              </div>
              <div class="mt-auto p-2">
              <span class="text-headline">
                <span class="theme-color-blue">{{ count_data.pending_invoice }}</span>
              </span>
              </div>
            </div>
            <div class="col-4">
              <div class="p-2 theme-color-blue">
                {{ $t("Closed Invoices") }}
              </div>
              <div class="mt-auto p-2">
              <span class="text-headline">
                <span class="theme-color-blue">{{ count_data.closed_invoice }}</span>
              </span>
              </div>
            </div>
            <div class="col-4">
              <div class="p-2 theme-color-blue">
                {{ $t("Partial Invoices") }}
              </div>
              <div class="mt-auto p-2">
              <span class="text-headline">
                <span class="theme-color-blue">{{ count_data.partial_pay_invoice }}</span>
              </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  </template>
  
  <script>
  'use strict';
  
  export default {
    data() {
      return {
        count_data: this.count_data,
      }
    },
    props: {
      count_data: [Array, Object]
    },
    mounted() {
      console.log('Customer statistics page loaded');
    },
    methods: {}
  }
  </script>