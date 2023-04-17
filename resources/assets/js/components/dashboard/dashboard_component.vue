<template>
  <div>
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="d-flex flex-wrap mb-4 ">
          <div class="mr-auto">
            <div class="d-flex">
              <div>
                <!-- <span class="text-title"> {{ $t("Dashboard") }} </span> -->
                <div class="form-group " v-show="is_master">
                  <div class="custom-control custom-switch">
                    <input
                      type="checkbox"
                      class="custom-control-input"
                      id="combined"
                      v-model="show_combined_stats"
                      @change="changeStats"
                    />
                    <label class="custom-control-label" for="combined">{{
                      $t("Show Combined Statistics")
                    }}</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="pull-right d-flex mr-3">
            <label>{{ $t("From") }}&nbsp;</label
            ><input
              type="date"
              :lang="date.lang"
              :format="date_format + ' HH:mm'"
              :value="new Date(dashboard_from_date).toISOString().split('T')[0]"
              @input="dashboard_from_date = $event.target.valueAsDate"
              @change="dashboard_month_change"
              class="form-control bg-white"
            />
          </div>
          <div class="pull-right d-flex">
            <label>{{ $t("To") }}&nbsp;</label
            ><input
              type="date"
              :lang="date.lang"
              :value="new Date(dashboard_to_date).toISOString().split('T')[0]"
              @input="dashboard_to_date = $event.target.valueAsDate"
              @change="dashboard_month_change"
              id="dashboard_to_date"
              class="form-control bg-white"
            />
          </div>
          <div class="pull-right d-flex">
            <!--<button
              class="btn btn-primary btn-sm mx-2"
              @click="dashboard_month_change"
            >
              Submit
            </button>-->
          </div>
        </div>
      </div>
      <!-- <div class="col-xs-12 col-sm-12 col-md-12 mb-5" v-if="store_names.length">
                <span class="label label-default mr-1" :class="[ store.active_status == true ? 'bg-danger' : 'bg-primary' ] " v-for="(store,index) in store_names" :key="index" @click="selectStore(store)">{{ store.name }}</span>
            </div> -->
      <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="row">
          <div class="col-md-3">
            <div class="block-wrapper custom-block-green">
              <div class="block-wrapper-content custom-block-height">
                <div class="block-wrapper-icon">
                  <p>
                    <i
                      class="fas fa-users custom-icon-green"
                      aria-hidden="true"
                    ></i>
                  </p>
                </div>

                <div class="block-wrapper-count">
                  <h1>{{ todays_order_count.raw }}</h1>
                </div>

                <div class="block-wrapper-text">
                  <p>{{ $t("Today's Sales") }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="block-wrapper custom-block-blue">
              <div class="block-wrapper-content custom-block-height">
                <div class="block-wrapper-icon">
                  <p>
                    <i
                      class="fas fa-users custom-icon-blue"
                      aria-hidden="true"
                    ></i>
                  </p>
                </div>

                <div class="block-wrapper-count">
                  <h1>{{ todays_order_value.raw }} {{ $t("SAR") }}</h1>
                </div>

                <div class="block-wrapper-text">
                  <p>{{ $t("Today's POS Sale Value") }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="block-wrapper radial-chart-block">
              <div class="block-wrapper-content custom-block-height">
                <!-- <p><b>{{ $t("Net Profit") }}</b></p> -->

                <div class="clearfix"></div>

                <div class="row">
                  <div class="col-md-8">
                    <div>
                      <apexchart
                        type="radialBar"
                        height="280"
                        :options="chartOptions"
                        :series="series"
                      ></apexchart>
                    </div>
                  </div>
                  <div class="col-md-4 pt-5">
                    <ul class="list-group">
                      <li class="list-group-item">
                        <!-- <span>15</span><br/> -->

                        <span class="text-primary">{{
                          $t("Total Revenue")
                        }}</span
                        ><br />

                        <span class="text-primary text-bold"
                          >{{ revenue_value.raw | formatDecimal }} ({{
                            $t("SAR")
                          }})</span
                        >
                      </li>

                      <li class="list-group-item">
                        <!-- <span>15</span><br/> -->

                        <span class="text-danger">{{ $t("Expense") }}</span
                        ><br />

                        <span class="text-danger text-bold"
                          >{{ expense.raw | formatDecimal }} ({{
                            $t("SAR")
                          }})</span
                        >
                      </li>

                      <li class="list-group-item">
                        <!-- <span>15</span><br/> -->

                        <span class="text-success"
                          >{{ $t("Total") }} {{ $t("Net Profit") }}</span
                        ><br />

                        <span class="text-success text-bold"
                          >{{ net_profit_value.raw | formatDecimal }} ({{
                            $t("SAR")
                          }})</span
                        >
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3">
            <div class="block-wrapper">
              <div class="block-wrapper-content custom-block-height p-0">
                <p class="p-3">
                  <strong>{{ $t("Invoice Status") }}</strong>
                </p>

                <apexchart
                  type="donut"
                  :options="paid_pending_invoice_chart_options"
                  :series="paid_pending_invoice_chart_series"
                ></apexchart>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="block-wrapper">
              <div class="block-wrapper-content custom-block-height p-0">
                <p class="p-3">
                  <strong>{{ $t("POS Status") }}</strong>
                </p>

                <apexchart
                  type="donut"
                  :options="paid_pending_pos_chart_options"
                  :series="paid_pending_pos_chart_series"
                ></apexchart>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="row block-wrapper custom-bg">
              <div class="col-md-6">
                <div class="block-wrapper-content custom-block-height">
                  <ul class="list-group">
                    <p class="h5">{{ $t("Cashier Revenue") }}</p>

                    <li class="list-group-item">
                      <label>
                        <i
                          class="fa fa-credit-card custom-icon"
                          style="width:50px;"
                          aria-hidden="true"
                          ><br />

                          <Small>{{ $t("Cash") }}</Small>
                        </i>
                      </label>

                      <span>
                        <highlight>{{
                          this.pos_total_revenue_in_cash
                        }}</highlight>
                      </span>

                      <span>{{ $t("SAR") }}</span>
                    </li>

                    <li class="list-group-item">
                      <label>
                        <i
                          class="fa fa-credit-card custom-icon"
                          style="width:50px;"
                          aria-hidden="true"
                          ><br />

                          <Small>{{ $t("Credit") }}</Small>
                        </i>
                      </label>

                      <span>
                        <highlight>{{
                          this.pos_total_revenue_in_credit
                        }}</highlight>
                      </span>

                      <span>{{ $t("SAR") }}</span>
                    </li>

                    <li class="list-group-item">
                      <label>
                        <i
                          class="fa fa-credit-card custom-icon"
                          style="width:50px;"
                          aria-hidden="true"
                          ><br />

                          <Small>{{ $t("Returned") }}</Small>
                        </i>
                      </label>

                      <span>
                        <highlight
                          >- {{ this.pos_total_revenue_in_returned }}</highlight
                        >
                      </span>

                      <span>{{ $t("SAR") }}</span>
                    </li>

                    <li class="list-group-item">
                      <label>
                        <i
                          class="fa fa-credit-card custom-icon"
                          style="width:50px;"
                          aria-hidden="true"
                          ><br />

                          <Small>{{ $t("Change") }}</Small>
                        </i>
                      </label>

                      <span>
                        <highlight
                          >- {{ this.pos_total_revenue_in_change }}</highlight
                        >
                      </span>

                      <span>{{ $t("SAR") }}</span>
                    </li>

                    <li
                      class="list-group-item custom-list-group-item pt-4 mt-2"
                    >
                      <span>
                        <highlight>{{
                          this.pos_total_revenue | formatDecimal
                        }}</highlight>
                      </span>

                      <span>{{ $t("SAR") }}</span>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="col-md-6">
                <div class="block-wrapper-content">
                  <ul class="list-group">
                    <p class="h5">{{ $t("Invoice Revenue") }}</p>

                    <li class="list-group-item">
                      <label>
                        <i
                          class="fa fa-credit-card custom-icon"
                          style="width:50px;"
                          aria-hidden="true"
                          ><br />

                          <Small>{{ $t("Cash") }}</Small>
                        </i>
                      </label>

                      <span>
                        <highlight>{{
                          this.invoice_total_revenue_in_cash
                        }}</highlight>
                      </span>

                      <span>{{ $t("SAR") }}</span>
                    </li>

                    <li class="list-group-item">
                      <label>
                        <i
                          class="fa fa-credit-card custom-icon"
                          style="width:50px;"
                          aria-hidden="true"
                          ><br />

                          <Small>{{ $t("Credit") }}</Small>
                        </i>
                      </label>

                      <span>
                        <highlight>{{
                          this.invoice_total_revenue_in_credit
                        }}</highlight>
                      </span>

                      <span>{{ $t("SAR") }}</span>
                    </li>

                    <li
                      class="list-group-item custom-list-group-item pt-4 mt-2"
                    >
                      <span>
                        <highlight>{{
                          this.invoice_total_revenue | formatDecimal
                        }}</highlight>
                      </span>

                      <span>{{ $t("SAR") }}</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="block-wrapper">
              <div class="block-wrapper-content">
                <ul class="list-group dashboard-progressbar-left-block">
                  <p>
                    <strong>{{ $t("Target") }}</strong>
                  </p>

                  <li class="list-group-item">
                    <div
                      class="list-group-left d-flex flex-column align-items-start"
                    >
                      <p>{{ $t("Income") }} ({{ $t(store_currency) }})</p>

                      <p :class="lang == 'ar' ? 'align-self-end' : ''">
                        {{ target.income_width | formatDecimal }}%
                      </p>
                    </div>
                    <div
                      class="list-group-right d-flex flex-column align-items-end"
                    >
                      <div class="d-flex justify-content-end">
                        <p class="mr-2" v-if="lang == 'ar'">
                          {{ $t("Income") }} {{ $t("Actual") }} :
                        </p>
                        <p class="mr-2" v-else>
                          {{ $t("Actual") }} {{ $t("Income") }} :
                        </p>
                        <p>
                          <i class="fas fa-arrow-up text-success"></i
                          >{{ revenue_value.raw | formatDecimal }}
                        </p>
                      </div>
                      <div class="d-flex justify-content-end">
                        <p class="mr-2">{{ $t("Target") }} :</p>
                        <p>
                          {{ target.income | formatDecimal }}
                        </p>
                      </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="progress">
                      <div
                        class="progress-bar bg-success"
                        role="progressbar"
                        :style="{ width: target.income_width_value + '%' }"
                        aria-valuenow="25"
                        aria-valuemin="0"
                        aria-valuemax="100"
                      ></div>
                    </div>
                  </li>

                  <li class="list-group-item">
                    <div
                      class="list-group-left d-flex flex-column align-items-start"
                    >
                      <p>{{ $t("Expense") }} ({{ $t(store_currency) }})</p>

                      <p :class="lang == 'ar' ? 'align-self-end' : ''">
                        {{ target.expense_width | formatDecimal }}%
                      </p>
                    </div>

                    <div
                      class="list-group-right d-flex flex-column align-items-end"
                    >
                      <div class="d-flex justify-content-end">
                        <p class="mr-2" v-if="lang == 'ar'">
                          {{ $t("Expense") }} {{ $t("Actual") }} :
                        </p>
                        <p class="mr-2" v-else>
                          {{ $t("Actual") }} {{ $t("Expense") }} :
                        </p>
                        <p>
                          <i class="fas fa-arrow-up text-success"></i>
                          {{ expense.raw | formatDecimal }}
                        </p>
                      </div>
                      <div class="d-flex justify-content-end">
                        <p class="mr-2">{{ $t("Target") }} :</p>
                        <p>
                          {{ target.expense | formatDecimal }}
                        </p>
                      </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="progress">
                      <div
                        class="progress-bar bg-danger"
                        role="progressbar"
                        :style="{ width: target.expense_width_value + '%' }"
                        aria-valuenow="25"
                        aria-valuemin="0"
                        aria-valuemax="100"
                      ></div>
                    </div>
                  </li>

                  <li class="list-group-item">
                    <div
                      class="list-group-left d-flex flex-column align-items-start"
                    >
                      <p>{{ $t("POS Value") }} ({{ $t(store_currency) }})</p>

                      <p :class="lang == 'ar' ? 'align-self-end' : ''">
                        {{ target.sales_width_value | formatDecimal }}%
                      </p>
                    </div>

                    <div
                      class="list-group-right d-flex flex-column align-items-end"
                    >
                      <div class="d-flex justify-content-end">
                        <p class="mr-2" v-if="lang == 'ar'">
                          {{ $t("Cashier Revenue") }} {{ $t("Actual") }} :
                        </p>
                        <p class="mr-2" v-else>
                          {{ $t("Actual") }} {{ $t("Cashier Revenue") }} :
                        </p>
                        <p>
                          <i class="fas fa-arrow-up text-success"></i>
                          {{ pos_total_revenue | formatDecimal }}
                        </p>
                      </div>
                      <div class="d-flex justify-content-end">
                        <p class="mr-2">{{ $t("Target") }} :</p>
                        <p>
                          {{ target.sales | formatDecimal }}
                        </p>
                      </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="progress">
                      <div
                        class="progress-bar bg-success"
                        role="progressbar"
                        :style="{ width: target.sales_width_value + '%' }"
                        aria-valuenow="25"
                        aria-valuemin="0"
                        aria-valuemax="100"
                      ></div>
                    </div>
                  </li>

                  <li class="list-group-item">
                    <div
                      class="list-group-left d-flex flex-column align-items-start"
                    >
                      <p>{{ $t("Net Profit") }} ({{ $t(store_currency) }})</p>

                      <p :class="lang == 'ar' ? 'align-self-end' : ''">
                        {{ target.net_profit_width_value | formatDecimal }}%
                      </p>
                    </div>

                    <div
                      class="list-group-right d-flex flex-column align-items-end"
                    >
                      <div class="d-flex justify-content-end">
                        <p class="mr-2" v-if="lang == 'ar'">
                          {{ $t("Net Profit") }} {{ $t("Actual") }} :
                        </p>
                        <p class="mr-2" v-else>
                          {{ $t("Actual") }} {{ $t("Net Profit") }} :
                        </p>
                        <p>
                          <i class="fas fa-arrow-up text-success"></i>
                          {{ net_profit_value.raw | formatDecimal }}
                        </p>
                      </div>
                      <div class="d-flex justify-content-end">
                        <p class="mr-2">{{ $t("Target") }} :</p>
                        <p>
                          {{ target.net_profit | formatDecimal }}
                        </p>
                      </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="progress">
                      <div
                        class="progress-bar bg-success"
                        role="progressbar"
                        :style="{ width: target.net_profit_width_value + '%' }"
                        aria-valuenow="25"
                        aria-valuemin="0"
                        aria-valuemax="100"
                      ></div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="block-wrapper">
              <div
                class="block-wrapper-content  dashboard-progressbar-wrapper-right"
              >
                <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-6">
                    <!-- <li>Sample Text</li> -->

                    <li>
                      {{ $t("Total sold products Quantity") }}
                      <span class="text-primary">
                        <strong> {{ total_sales_quantity }} </strong>
                      </span>
                    </li>

                    <ul
                      class="list-group dashboard-progressbar-right-block column-1"
                      v-show="top_selling_product_data.length"
                      style="margin-top:40px;margin-bottom:40px;"
                    >
                      <p>
                        <b>{{ $t("Top 5 Best Selling Products") }}</b>
                      </p>
                      <li
                        class="list-group-item"
                        v-for="(top_selling_product,
                        index) in top_selling_product_data"
                        :key="index"
                      >
                        <p>
                          {{ top_selling_product.name }} ({{
                            top_selling_product.sum
                          }})
                        </p>

                        <div class="progress">
                          <div
                            :class="[class_loop(index), progress - bar]"
                            role="progressbar"
                            :style="{
                              width: top_selling_product.percent + '%',
                            }"
                            aria-valuenow="47"
                            aria-valuemin="0"
                            aria-valuemax="100"
                          ></div>
                        </div>
                      </li>
                    </ul>
                  </div>

                  <div class="col-xs-12 col-sm-12 col-md-6">
                    <!--   <div class="col-3">
                                            <div class="form-group">
                                                <select class="form-control" id="">
                                                  <option selected="selected">Select</option>
                                                  <option>Text</option>
                                                  <option>Text</option>
                                                </select>
                                            </div>
                                        </div> -->
                    <li>
                      <!-- Total Sales Amount -->
                      {{ $t("Total Sales Margin") }}
                      <span class="text-primary">
                        <strong>
                          {{ total_sales_margin_amount_data }}
                          ({{ $t(store_currency) }})
                        </strong>
                      </span>
                    </li>

                    <ul
                      class="list-group dashboard-progressbar-right-block column-1"
                      v-show="top_earning_products.length"
                      style="margin-top:40px;margin-bottom:40px;"
                    >
                      <p v-if="top_earning_product_data.length > 0">
                        <b>{{ $t("Top 5 Net Profit Products") }}</b>
                      </p>

                      <li
                        class="list-group-item"
                        v-for="(top_earning_product,
                        index) in top_earning_product_data"
                        :key="index"
                      >
                        <p>
                          {{ top_earning_product.name }} ({{
                            top_earning_product.amount
                          }}
                          {{ $t(store_currency) }})
                        </p>

                        <div class="progress">
                          <div
                            :class="[class_loop(index), progress - bar]"
                            role="progressbar"
                            :style="{
                              width: top_earning_product.percent + '%',
                            }"
                            aria-valuenow="47"
                            aria-valuemin="0"
                            aria-valuemax="100"
                          ></div>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- <div class="col-md-3">

            <div class="block-wrapper ">

                <div class="block-wrapper-content custom-block-height">
                    
                    <div class="p-2 theme-color-blue">{{ $t("Todays Sales") }}</div>

                                        <div class="mt-auto p-2">
                                            <span class="text-headline">
                                                <i class='fa fa-circle-notch fa-spin' v-if="stats_processing == true"></i>
                                                <span v-else>{{ todays_order_count.formatted }}</span>
                                            </span>
                                        </div>

                </div>
        
            </div>

        </div> -->

      <div class="col-md-12">
        <div class="d-flex flex-wrap mb-4">
          <div class="col-md-12">
            <div class="row">
              <div
                class="d-flex align-items-start flex-column p-1 mb-1 col-md-6"
              >
                <div class="col-12 p-3 bg-white block-wrapper">
                  <div class="d-flex flex-wrap box-content">
                    <div
                      class="d-flex col-sm-12 col-md-12 col-lg-8 col-xl-8 p-0"
                    >
                      <div class="stat_chart_container">
                        <canvas
                          id="today_sales_count_chart"
                          class="theme-color-blue"
                        ></canvas>
                      </div>
                    </div>

                    <div
                      class="d-flex align-items-end flex-column col-sm-12 col-md-12 col-lg-4 col-xl-4 p-0"
                    >
                      <div class="p-2 theme-color-blue">
                        {{ $t("Today's Sales") }}
                      </div>

                      <div class="mt-auto p-2">
                        <span class="text-headline">
                          <i
                            class="fa fa-circle-notch fa-spin"
                            v-if="stats_processing == true"
                          ></i>
                          <span v-else>{{ todays_order_count.formatted }}</span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="d-flex align-items-start flex-column p-1 mb-1 col-md-6"
              >
                <div class="col-12 p-3 bg-white block-wrapper">
                  <div class="d-flex flex-wrap box-content">
                    <div
                      class="d-flex col-sm-12 col-md-12 col-lg-8 col-xl-8 p-0"
                    >
                      <div class="stat_chart_container">
                        <canvas id="today_sales_value_chart" class=""></canvas>
                      </div>
                    </div>

                    <div
                      class="d-flex align-items-end flex-column col-sm-12 col-md-12 col-lg-4 col-xl-4 p-0"
                    >
                      <div class="p-2">{{ $t("Today's POS Sale Value") }}</div>

                      <div class="mt-auto p-2">
                        <span class="text-headline">
                          <i
                            class="fa fa-circle-notch fa-spin"
                            v-if="stats_processing == true"
                          ></i>
                          <span v-else>{{
                            todays_order_value.formatted | formatDecimal
                          }}</span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex flex-wrap mb-4">
          <div class="col-md-12">
            <div class="row">
              <!--  <div class="d-flex align-items-start flex-column p-1 mb-1 col-md-3" style="padding-right: 5px !important;">
                            <div class="col-12 p-3 bg-white block-wrapper">

                                <div class="d-flex align-items-end flex-column box-content">
                                    <div class="p-2 theme-color-blue">{{ $t("Total Sales") }}</div>

                                    <div class="mt-auto p-2">
                                        <span class="text-headline">
                                            <i class='fa fa-circle-notch fa-spin' v-if="stats_processing == true"></i>
                                            <span class="theme-color-blue" v-else>{{ order_count.formatted }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div> -->

              <div
                class="d-flex align-items-start flex-column p-1 mb-1 col-md-3"
                style="padding-right: 5px !important;"
              >
                <div class="col-12 p-3 bg-white  block-wrapper">
                  <div class="d-flex align-items-end flex-column box-content">
                    <div class="p-2 theme-color-blue">
                      {{ $t("Total Cashier Sales") }}
                    </div>

                    <div class="mt-auto p-2">
                      <span class="text-headline">
                        <i
                          class="fa fa-circle-notch fa-spin"
                          v-if="stats_processing == true"
                        ></i>
                        <span class="theme-color-blue" v-else>{{
                          order_count.raw
                        }}</span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="d-flex align-items-start flex-column p-1 mb-1 col-md-3"
                style="padding-right: 5px !important;"
              >
                <div class="col-12 p-3 bg-white  block-wrapper">
                  <div class="d-flex align-items-end flex-column box-content">
                    <div class="p-2 theme-color-blue">
                      {{ $t("Total Cashiers' Revenue") }} ({{
                        $t(store_currency)
                      }})
                    </div>

                    <div class="mt-auto p-2">
                      <span class="text-headline">
                        <i
                          class="fa fa-circle-notch fa-spin"
                          v-if="stats_processing == true"
                        ></i>
                        <span class="theme-color-blue" v-else>{{
                          pos_total_revenue | formatDecimal
                        }}</span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="d-flex align-items-start flex-column p-1 mb-1 col-md-3"
                style="padding-right: 5px !important;"
              >
                <div class="col-12 p-3 bg-white  block-wrapper">
                  <div class="d-flex align-items-end flex-column box-content">
                    <div class="p-2 theme-color-blue">
                      {{ $t("Total Revenue") }} ({{ $t(store_currency) }})
                    </div>

                    <div class="mt-auto p-2">
                      <span class="text-headline">
                        <i
                          class="fa fa-circle-notch fa-spin"
                          v-if="stats_processing == true"
                        ></i>
                        <span v-else class="theme-color-blue">{{
                          revenue_value.raw | formatDecimal
                        }}</span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="d-flex align-items-start flex-column p-1 mb-1 col-md-3"
                style="padding-right: 5px !important;"
              >
                <div class="col-12 p-3 bg-white  block-wrapper">
                  <div class="d-flex align-items-end flex-column box-content">
                    <div class="p-2 theme-color-blue">
                      {{ $t("Total Invoices") }} ({{ $t("Pending/Paid") }})
                    </div>

                    <div class="mt-auto p-2">
                      <span class="text-headline">
                        <i
                          class="fa fa-circle-notch fa-spin"
                          v-if="stats_processing == true"
                        ></i>
                        <span v-else class="theme-color-blue"
                          >{{ invoices_count.pending_formatted }} /
                          {{ invoices_count.paid_formatted }}</span
                        >
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="d-flex align-items-start flex-column p-1 mb-1 col-md-3"
                style="padding-right: 5px !important;"
              >
                <div class="col-12 p-3 bg-white  block-wrapper">
                  <div class="d-flex align-items-end flex-column box-content">
                    <div class="p-2 theme-color-blue">
                      {{ $t("Total Expense") }} ({{ $t(store_currency) }})
                    </div>

                    <div class="mt-auto p-2">
                      <span class="text-headline">
                        <i
                          class="fa fa-circle-notch fa-spin"
                          v-if="stats_processing == true"
                        ></i>
                        <span v-else class="theme-color-blue">{{
                          expense.raw | formatDecimal
                        }}</span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="d-flex align-items-start flex-column p-1 mb-1 col-md-3"
                style="padding-right: 5px !important;"
              >
                <div class="col-12 p-3 bg-white  block-wrapper">
                  <div class="d-flex align-items-end flex-column box-content">
                    <div class="p-2 theme-color-blue">
                      {{ $t("Net Profit") }} ({{ $t(store_currency) }})
                    </div>

                    <div class="mt-auto p-2">
                      <span class="text-headline">
                        <i
                          class="fa fa-circle-notch fa-spin"
                          v-if="stats_processing == true"
                        ></i>
                        <span v-else class="theme-color-blue">{{
                          net_profit_value.raw | formatDecimal
                        }}</span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="d-flex align-items-start flex-column p-1 mb-1 col-md-3"
                style="padding-right: 5px !important;"
              >
                <div class="col-12 p-3 bg-white  block-wrapper">
                  <div class="d-flex align-items-end flex-column box-content">
                    <div class="p-2 theme-color-blue">
                      {{ $t("Total Customers") }}
                    </div>

                    <div class="mt-auto p-2">
                      <span class="text-headline">
                        <i
                          class="fa fa-circle-notch fa-spin"
                          v-if="stats_processing == true"
                        ></i>
                        <span v-else class="theme-color-blue">{{
                          customer_count.formatted
                        }}</span>
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="d-flex align-items-start flex-column p-1 mb-1 col-md-3"
                style="padding-right: 5px !important;"
              >
                <div class="col-12 p-3 bg-white  block-wrapper">
                  <div class="d-flex align-items-end flex-column box-content">
                    <div class="p-2 theme-color-blue">
                      {{ $t("Total Purchase Orders") }}
                      ({{ $t("Pending/Paid") }})
                    </div>
                    <div class="mt-auto p-2">
                      <span class="text-headline">
                        <i
                          class="fa fa-circle-notch fa-spin"
                          v-if="stats_processing == true"
                        ></i>
                        <span v-else class="theme-color-blue"
                          >{{ purchase_order_count.pending_formatted }} /
                          {{ purchase_order_count.closed_formatted }}</span
                        >
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex flex-wrap">
          <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0">
            <div class="mb-2">
              <span class="text-subhead theme-color-blue">
                {{ $t("POS Order Count Day Wise") }}</span
              >
            </div>
            <div class="">
              <div class="chart_container block-wrapper" style="padding:10px;">
                <canvas id="pos_sales_count_activity_chart" class=""></canvas>
              </div>
            </div>
          </div>

          <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0">
            <div class="mb-2">
              <span class="text-subhead theme-color-blue">
                {{ $t("POS Order Value Day Wise") }}</span
              >
            </div>
            <div class="">
              <div
                class="chart_container block-wrapper"
                style="padding:10px;margin:5px;"
              >
                <canvas id="pos_sales_value_activity_chart" class=""></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex flex-wrap">
          <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0">
            <div class="mb-2">
              <span class="text-subhead theme-color-blue">
                {{ $t("POS Order Count Time Wise") }}</span
              >
              <!-- <div class="d-flex mr-3">
                            <label>{{ $t("From") }}&nbsp;</label>
                            <date-picker type="date" :lang='date.lang' :format="date.format" v-model="pos_order_count_timely_from_date" @change="order_count_activity_chart_timely" input-class="form-control bg-white"></date-picker>
                        </div> -->
            </div>
            <div class="">
              <div class="chart_container block-wrapper" style="padding:10px;">
                <canvas
                  id="pos_sales_count_activity_chart_timely"
                  class=""
                ></canvas>
              </div>
            </div>
          </div>

          <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 p-0">
            <div class="mb-2">
              <span class="text-subhead theme-color-blue">
                {{ $t("POS Order Value Time Wise") }}</span
              >
              <!-- <div class="d-flex mr-3">
                            <label>&nbsp;{{ $t("To") }}&nbsp;</label>
                            <date-picker type="date" :lang='date.lang' :format="date.format" v-model="pos_order_count_timely_to_date" @change="order_count_activity_chart_timely" input-class="form-control bg-white"></date-picker>
                        </div> -->
            </div>
            <div class="">
              <div
                class="chart_container block-wrapper"
                style="padding:10px;margin:5px;"
              >
                <canvas
                  id="pos_sales_value_activity_chart_timely"
                  class=""
                ></canvas>
              </div>
            </div>
          </div>
        </div>

        <!--  <div class="d-flex flex-wrap">
                <div class="mb-2">
                    <span class="text-subhead theme-color-blue"> {{ $t("Targets") }}</span>
                </div>
            </div>
            <div class="d-flex flex-wrap mb-4">
                <div class="col-md-12">
                    <div class="row">

                    <div class="d-flex align-items-start flex-column p-1 mb-1 col-md-3">
                        <div class="col-12 p-3 bg-white block-wrapper">
                            <div class="d-flex align-items-end flex-column box-content">
                                <div class="p-2 theme-color-blue">{{ $t("Income") }} ({{ store_currency }})</div>

                                <div class="mt-auto p-2">
                                    <span class="dashboard-target-label">
                                        <i class='fa fa-circle-notch fa-spin' v-if="stats_processing == true"></i>
                                        <span v-else class="theme-color-blue">{{ revenue_value.raw }} / {{ target.income }}</span>
                                    </span>
                                </div>

                                <div class="progress mt-2 w-100 progress-height">
                                    <div class="progress-bar" role="progressbar" v-bind:style="{'width':target.income_width}" v-bind:aria-valuenow="revenue_value.raw" aria-valuemin="0" v-bind:aria-valuemax="target.income"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start flex-column p-1 mb-1 col-md-3">
                        <div class="col-12 p-3 bg-white block-wrapper">
                            <div class="d-flex align-items-end flex-column box-content">
                                <div class="p-2 theme-color-blue">{{ $t("Expense") }} ({{ store_currency }})</div>

                                <div class="mt-auto p-2">
                                    <span class="dashboard-target-label">
                                        <i class='fa fa-circle-notch fa-spin' v-if="stats_processing == true"></i>
                                        <span v-else class="theme-color-blue">{{ expense.raw }} / {{ target.expense }}</span>
                                    </span>
                                </div>

                                <div class="progress mt-2 w-100 progress-height">
                                    <div class="progress-bar" role="progressbar" v-bind:style="{'width':target.expense_width}" v-bind:aria-valuenow="expense.raw" aria-valuemin="0" v-bind:aria-valuemax="target.expense"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start flex-column p-1 mb-1 col-md-3">
                        <div class="col-12 p-3 bg-white block-wrapper">
                            <div class="d-flex align-items-end flex-column box-content">
                                <div class="p-2 theme-color-blue">{{ $t("POS Sales") }} ({{ store_currency }})</div>

                                <div class="mt-auto p-2">
                                    <span class="dashboard-target-label">
                                        <i class='fa fa-circle-notch fa-spin' v-if="stats_processing == true"></i>
                                        <span v-else class="theme-color-blue">{{ order_value.raw }} / {{ target.sales }}</span>
                                    </span>
                                </div>

                                <div class="progress mt-2 w-100 progress-height">
                                    <div class="progress-bar" role="progressbar" v-bind:style="{'width':target.sales_width}" v-bind:aria-valuenow="order_value.raw" aria-valuemin="0" v-bind:aria-valuemax="target.sales"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start flex-column p-1 mb-1 col-md-3">
                        <div class="col-12 p-3 bg-white block-wrapper">
                            <div class="d-flex align-items-end flex-column box-content">
                                <div class="p-2 theme-color-blue">{{ $t("Net Profit") }} ({{ store_currency }})</div>

                                <div class="mt-auto p-2">
                                    <span class="dashboard-target-label">
                                        <i class='fa fa-circle-notch fa-spin' v-if="stats_processing == true"></i>
                                        <span v-else class="theme-color-blue">{{ net_profit_value.raw }} / {{ target.net_profit }}</span>
                                    </span>
                                </div>

                                <div class="progress mt-2 w-100 progress-height">
                                    <div class="progress-bar" role="progressbar" v-bind:style="{'width':target.net_profit_width}" v-bind:aria-valuenow="net_profit_value.raw" aria-valuemin="0" v-bind:aria-valuemax="target.net_profit"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>
            </div> -->

        <div class="d-flex flex-wrap mb-4">
          <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 p-0 mb-4">
            <div class="mb-2">
              <span class="text-subhead theme-color-blue">
                {{ $t("Income vs Expense") }}</span
              >
            </div>
            <div class="bg-white block-wrapper">
              <div class="chart_container">
                <canvas id="income_expense_chart" class=""></canvas>
              </div>
            </div>
          </div>

          <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 pl-sm-0 pl-md-3">
            <div class="mb-2">
              <span class="text-subhead theme-color-blue">
                {{ $t("Recent Transactions") }}</span
              >
            </div>
            <div class="">
              <div class="table-container block-wrapper">
                <div
                  class="table-responsive mb-2"
                  v-if="transactions.length > 0"
                >
                  <table
                    class="table table-striped display nowrap text-nowrap w-100"
                  >
                    <thead>
                      <tr>
                        <!-- <th scope="col theme-color-blue">#</th> -->
                        <th scope="col theme-color-blue">{{ $t("Code") }}</th>
                        <th scope="col theme-color-blue">{{ $t("Date") }}</th>
                        <th scope="col theme-color-blue">
                          {{ $t("Bill To") }}
                        </th>
                        <th scope="col theme-color-blue">
                          {{ $t("Transaction Type") }}
                        </th>
                        <th scope="col theme-color-blue">
                          {{ $t("Payment Method") }}
                        </th>
                        <th scope="col theme-color-blue">
                          {{ $t("Payment Type") }}
                        </th>
                        <th scope="col theme-color-blue text-right">
                          {{ $t("Cash") }}
                        </th>
                        <th scope="col theme-color-blue text-right">
                          {{ $t("Credit") }}
                        </th>
                        <th scope="col theme-color-blue text-right">
                          <strong> {{ $t("Amount") }} </strong>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(transaction, key, index) in transactions"
                        v-bind:value="transactions.slack"
                        v-bind:key="index"
                      >
                        <!-- <th scope="col">{{ key+1 }}</th> -->
                        <td>{{ transaction.transaction_code }}</td>
                        <td>{{ transaction.transaction_date }}</td>
                        <td>{{ transaction.bill_to }}</td>
                        <td>{{ transaction.transaction_type_data.label }}</td>
                        <td>{{ transaction.payment_method }}</td>
                        <td>{{ transaction.payment_mode }}</td>
                        <td class="text-right">
                          {{ transaction.cash_amount }}
                        </td>
                        <td class="text-right">
                          {{ transaction.credit_amount }}
                        </td>
                        <td
                          class="text-right"
                          :class="[
                            transaction.transaction_type_data
                              .transaction_type_constant == 'INCOME'
                              ? 'text-success'
                              : 'text-danger',
                          ]"
                        >
                          {{ transaction.amount }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div v-else class="theme-color-blue text-center">
                  {{ $t("No transaction found at this moment") }}.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    

  </div>
</template>

<script>
"use strict";

import DatePicker from "vue2-datepicker";
import ApexCharts from "vue-apexcharts";
import Chart from "chart.js";
import moment from "moment";
import Select2 from "v-select2-component";
// import Multiselect from 'vue-multiselect';
import number_format from "locutus/php/strings/number_format";

// import store_opening_time from '../components/Header.vue';

export default {
  components: {
    apexchart: ApexCharts,
    Select2,
    // Multiselect
  },
  data() {
    return {
      date: {
        lang: "en",
        format: "YYYY-MM-DD",
      },

      paid_pending_invoice_chart_series: [0, 1],
      paid_pending_invoice_chart_options: {
        labels: [this.$t("Pending"), this.$t("Paid")],
        chart: {
          type: "donut",
        },
        tooltip: {
          y: {
            formatter: (value) => {
              return (
                this.$options.filters.formatDecimal(value) +
                " " +
                this.$t("SAR")
              );
            },
          },
        },
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 300,
              },
              legend: {
                position: "top",
              },
            },
          },
        ],
      },

      paid_pending_pos_chart_series: [0, 1],
      paid_pending_pos_chart_options: {
        labels: [this.$t("Pending"), this.$t("Paid")],
        chart: {
          type: "donut",
        },
        tooltip: {
          y: {
            formatter: (value) => {
              return (
                this.$options.filters.formatDecimal(value) +
                " " +
                this.$t("SAR")
              );
            },
          },
        },
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 300,
              },
              legend: {
                position: "top",
              },
            },
          },
        ],
      },

      dashboard_from_date: new Date(
        moment()
          // .subtract(1, "months")
          .format("YYYY-MM-DD")
      ),
      dashboard_from_formatted: new Date(
        moment()
          // .subtract(1, "months")
          .format("YYYY-MM-DD")
      ),

      dashboard_to_date: new Date(
        moment()
          .add(1, "days")
          .format("YYYY-MM-DD")
      ),
      dashboard_to_formatted: new Date(
        moment()
          .add(1, "days")
          .format("YYYY-MM-DD")
      ),

      store_currency: this.store.currency_code,

      /* Revenue Data of Invoice and POS with Cash & Credit */
      invoice_credit: null,
      invoice_cash: null,
      pos_credit: null,
      pos_returned: null,
      pos_change: null,
      pos_cash: null,
      invoice_total: null,

      chart_total: null,
      chart_expense: null,
      chart_income: null,
      chart_net_profit: null,
      stats_processing: false,
      todays_order_count: {
        raw: "-",
        formatted: "-",
      },
      todays_order_value: {
        raw: "-",
        formatted: "-",
      },
      order_count: {
        raw: "-",
        formatted: "-",
      },
      order_value: {
        raw: "-",
        formatted: "-",
      },
      revenue_value: {
        raw: "-",
        formatted: "-",
      },
      customer_count: {
        raw: "-",
        formatted: "-",
      },
      expense: {
        raw: "-",
        formatted: "-",
      },
      net_profit_value: {
        raw: "-",
        formatted: "-",
      },
      purchase_order_count: {
        pending_raw: "-",
        pending_formatted: "-",
        closed_raw: "-",
        closed_formatted: "-",
      },
      invoices_count: {
        pending_raw: "-",
        pending_formatted: "-",
        paid_raw: "-",
        paid_formatted: "-",
      },

      target: {
        income: "-",
        income_width: 0,
        income_width_value: 0,

        expense: "-",
        expense_width: 0,
        expense_width_value: 0,

        sales: "-",
        sales_width: 0,
        sales_width_value: 0,

        net_profit: "-",
        net_profit_width: 0,
        net_profit_width_value: 0,
      },

      transactions: [],

      // todays_sales_count_chart_color: '#174f75',
      // todays_sales_count_chart_fill_color: '#174f75',

      // todays_sales_value_chart_color: '#174f75',
      // todays_sales_value_chart_fill_color: '#174f75',

      // pos_sales_count_activity_chart_fill_color : '#174f75',
      // pos_sales_value_activity_chart_fill_color : '#174f75',
      // income_chart_fill_color: '#174f75',
      // expense_chart_fill_color: '#174f75',

      todays_sales_count_chart_color: "#174f75",
      todays_sales_count_chart_fill_color: "#b3e8de",

      todays_sales_value_chart_color: "#174f75",
      todays_sales_value_chart_fill_color: "#adcefe",

      pos_sales_count_activity_chart_fill_color: "#3185fc",
      pos_sales_value_activity_chart_fill_color: "#3185fc",
      pos_sales_count_activity_chart_timely_fill_color: "#F36B6B",
      pos_sales_value_activity_chart_timely_fill_color: "#F36B6B",
      income_chart_fill_color: "#059BFF",
      expense_chart_fill_color: "#FFC233",

      todays_sales_count_chart_config: {
        type: "line",
        data: {
          datasets: [],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          title: {
            display: false,
            text: "",
          },
          elements: {
            line: {
              tension: 0.4,
            },
          },
          layout: {
            padding: {
              top: 10,
            },
          },
          legend: {
            display: false,
          },
          scales: {
            xAxes: [
              {
                ticks: {
                  fontSize: 10,
                },
                display: true,
                scaleLabel: {
                  display: false,
                  labelString: this.$t("Day of the Month"),
                },
                gridLines: {
                  display: false,
                  drawBorder: true,
                  drawOnChartArea: false,
                },
              },
            ],
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
                display: false,
                scaleLabel: {
                  display: true,
                  labelString: "Value",
                },
                gridLines: {
                  display: false,
                  drawBorder: false,
                  drawOnChartArea: false,
                },
              },
            ],
          },
        },
      },

      todays_sales_value_chart_config: {
        type: "line",
        data: {
          datasets: [],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          title: {
            display: false,
            text: "",
          },
          elements: {
            line: {
              tension: 0.4,
            },
          },
          layout: {
            padding: {
              top: 10,
            },
          },
          legend: {
            display: false,
          },
          scales: {
            xAxes: [
              {
                ticks: {
                  fontSize: 10,
                },
                display: true,
                scaleLabel: {
                  display: false,
                  labelString: this.$t("Day of the Month"),
                },
                gridLines: {
                  display: false,
                  drawBorder: true,
                  drawOnChartArea: false,
                },
              },
            ],
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
                display: false,
                scaleLabel: {
                  display: true,
                  labelString: "Value",
                },
                gridLines: {
                  display: false,
                  drawBorder: false,
                  drawOnChartArea: false,
                },
              },
            ],
          },
        },
      },

      pos_sales_count_activity_chart_config: {
        type: "line",
        data: {
          datasets: [],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          title: {
            display: true,
            text: "",
          },
          elements: {
            line: {
              tension: 0.4,
            },
          },
          layout: {
            padding: {
              top: 10,
              bottom: 10,
            },
          },
          scales: {
            xAxes: [
              {
                display: true,
                scaleLabel: {
                  display: true,
                  labelString: this.$t("Day of the Month"),
                },
                gridLines: {
                  display: false,
                  drawBorder: true,
                  drawOnChartArea: false,
                },
              },
            ],
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
                display: false,
                scaleLabel: {
                  display: true,
                  labelString: "Value",
                },
                gridLines: {
                  display: false,
                  drawBorder: false,
                  drawOnChartArea: false,
                },
              },
            ],
          },
        },
      },

      pos_sales_value_activity_chart_config: {
        type: "line",
        data: {
          datasets: [],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          spanGaps: false,
          title: {
            display: true,
            text: "",
          },
          elements: {
            line: {
              tension: 0.4,
            },
          },
          layout: {
            padding: {
              top: 10,
              bottom: 10,
            },
          },
          scales: {
            xAxes: [
              {
                display: true,
                scaleLabel: {
                  display: true,
                  labelString: this.$t("Day of the Month"),
                },
                gridLines: {
                  display: false,
                  drawBorder: true,
                  drawOnChartArea: false,
                },
              },
            ],
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
                display: false,
                scaleLabel: {
                  display: true,
                  labelString: "Value",
                },
                gridLines: {
                  display: false,
                  drawBorder: false,
                  drawOnChartArea: false,
                },
              },
            ],
          },
        },
      },

      pos_sales_count_activity_chart_timely_config: {
        type: "line",
        data: {
          datasets: [],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          title: {
            display: true,
            text: "",
          },
          elements: {
            line: {
              tension: 0.4,
            },
          },
          layout: {
            padding: {
              top: 10,
              bottom: 10,
            },
          },
          scales: {
            xAxes: [
              {
                display: true,
                scaleLabel: {
                  display: true,
                  labelString: this.$t("Time of the Day"),
                },
                gridLines: {
                  display: false,
                  drawBorder: true,
                  drawOnChartArea: false,
                },
              },
            ],
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
                display: false,
                scaleLabel: {
                  display: true,
                  labelString: "Value",
                },
                gridLines: {
                  display: false,
                  drawBorder: false,
                  drawOnChartArea: false,
                },
              },
            ],
          },
        },
      },

      pos_sales_value_activity_chart_timely_config: {
        type: "line",
        data: {
          datasets: [],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          spanGaps: false,
          title: {
            display: true,
            text: "",
          },
          elements: {
            line: {
              tension: 0.4,
            },
          },
          layout: {
            padding: {
              top: 10,
              bottom: 10,
            },
          },
          scales: {
            xAxes: [
              {
                display: true,
                scaleLabel: {
                  display: true,
                  labelString: this.$t("Time of the Day"),
                },
                gridLines: {
                  display: false,
                  drawBorder: true,
                  drawOnChartArea: false,
                },
              },
            ],
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
                display: false,
                scaleLabel: {
                  display: true,
                  labelString: "Value",
                },
                gridLines: {
                  display: false,
                  drawBorder: false,
                  drawOnChartArea: false,
                },
              },
            ],
          },
        },
      },
      income_expense_chart: {
        type: "doughnut",
        data: {
          datasets: [],
          SAR: this.$t("SAR"),
          labels: [this.$t("Income"), this.$t("Expense")],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: "top",
          },
          tooltips: {
            callbacks: {
              title: function(tooltipItem, data) {
                return data["labels"][tooltipItem[0]["index"]];
              },
              label: function(tooltipItem, data) {
                return (
                  Number(
                    data["datasets"][0]["data"][tooltipItem["index"]].toFixed(2)
                  ).toLocaleString({ maximumFractionDigits: 2 }) +
                  " " +
                  data["SAR"]
                );
              },
              afterLabel: function(tooltipItem, data) {},
            },
          },
          title: {
            display: false,
            text: "",
          },
          animation: {
            animateScale: true,
            animateRotate: true,
          },
          layout: {
            padding: {
              top: 20,
              bottom: 20,
              left: 20,
              right: 20,
            },
          },
        },
      },
      chart_series_data: [],
      series: [],
      chartOptions: {
        colors: ["#007BFF", "#DC3545", "#28A745"],
        chart: {
          height: 200,
          type: "radialBar",
        },
        plotOptions: {
          radialBar: {
            dataLabels: {
              name: {
                fontSize: "18px",
              },
              value: {
                fontSize: "14px",
              },
              total: {
                show: true,
                label: this.$t("Total"),
                formatter: function(w) {
                  // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                  return w;
                },
              },
            },
          },
        },
        labels: [this.$t("Income"), this.$t("Expense"), this.$t("Net Profit")],
      },
      // added later
      top_selling_product_data: [],
      top_earning_product_data: "",
      total_sales_amount_data: "",
      total_sales_margin_amount_data: "",
      stores: [],
      selected_store_id: "",
      store_opening_time:
        this.store == null ? "00:00" : this.logged_store_opening_time,
      store_closing_time:
        this.store == null ? "00:00" : this.logged_store_closing_time,
    };
  },
  props: {
    store: [Array, Object],
    revenue: [Array, Object],
    top_selling_products: [Array, Object],
    top_earning_products: [Array, Object],
    total_sales_quantity: Number,
    total_sales_amount: Number,
    total_sales_margin_amount: Number,
    is_admin: Boolean,
    is_master: Boolean,
    show_combined_stats: Boolean,
    lang: String,
    date_format: String,
    logged_store_opening_time: String,
    logged_store_closing_time: String,
    dashboard_start_date: String,
    dashboard_end_date: String,
    // store_names : [Array,Object]
  },
  computed: {
    pos_total_revenue: function() {
      let total =
        parseFloat(this.pos_cash) +
        parseFloat(this.pos_credit) -
        parseFloat(this.pos_returned) -
        parseFloat(this.pos_change);
      return total.toFixed(2);
    },
    pos_total_revenue_in_cash: function() {
      return this.$options.filters.formatDecimal(parseFloat(this.pos_cash));
    },
    pos_total_revenue_in_credit: function() {
      return this.$options.filters.formatDecimal(parseFloat(this.pos_credit));
    },
    pos_total_revenue_in_returned: function() {
      return this.$options.filters.formatDecimal(parseFloat(this.pos_returned));
    },
    pos_total_revenue_in_change: function() {
      return this.$options.filters.formatDecimal(parseFloat(this.pos_change));
    },

    invoice_total_revenue: function() {
      let total =
        parseFloat(this.invoice_cash) + parseFloat(this.invoice_credit);
      return this.$options.filters.formatDecimal(total);
    },
    invoice_total_revenue_in_cash: function() {
      return this.$options.filters.formatDecimal(parseFloat(this.invoice_cash));
    },
    invoice_total_revenue_in_credit: function() {
      return this.$options.filters.formatDecimal(
        parseFloat(this.invoice_credit)
      );
    },
  },
  mounted() {
    this.dashboard_from_date = new Date(
      moment(this.dashboard_start_date)
        // .subtract(1, "months")
        .format("YYYY-MM-DD")
    );
    this.dashboard_from_formatted = new Date(
      moment(this.dashboard_start_date)
        // .subtract(1, "months")
        .format("YYYY-MM-DD")
    );
    this.dashboard_to_date = new Date(
      moment(this.dashboard_end_date)
        // .subtract(1, "months")
        .format("YYYY-MM-DD")
    );
    this.dashboard_to_formatted = new Date(
      moment(this.dashboard_end_date)
        // .subtract(1, "months")
        .format("YYYY-MM-DD")
    );
    this.fire_requests();
    // this.dashboard_month_change();

    // this.$emit('loadTopnavIcon',1);
    // console.log(this.is_admin);
  },
  created() {
    // this.store_names.forEach((store, index) => {
    //   this.stores.push({
    //     id: store.id,
    //     text: store.name,
    //   });
    // });
  },
  filters: {
    formatDecimal: function(value) {
      return Number(number_format(value, 2, ".", "")).toLocaleString({
        maximumFractionDigits: 2,
      });
    },
  },
  methods: {
    convert_date_format(date) {
      return date != "" ? moment(date).format("YYYY-MM-DD HH:mm") : "";
    },

    dashboard_month_change() {
      this.dashboard_to_formatted = this.convert_date_format(
        this.dashboard_to_date
      );
      this.dashboard_from_formatted = this.convert_date_format(
        this.dashboard_from_date
      );

      this.fire_requests();
      // console.log(this.top_selling_earning_products);
    },

    set_form_data() {
      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append(
        "to_date",
        this.convert_date_format(this.dashboard_to_formatted)
      );
      formData.append(
        "from_date",
        this.convert_date_format(this.dashboard_from_formatted)
      );
      return formData;
    },

    fire_requests() {
      this.order_count_activity_chart();
      this.order_count_activity_chart_timely();
      this.get_recent_trasactions();
      this.get_dashboard_stats();

      //this.trending_products();
    },

    get_dashboard_stats() {
      var formData = this.set_form_data();

      this.stats_processing = true;

      axios
        .post("/api/get_dashboard_stats", formData)
        .then((response) => {
          this.stats_processing = false;

          if (response.data.status_code == 200) {
            // console.log('top_selling_earning_products',response.data.data.top_selling_earning_products);

            this.top_selling_product_data =
              response.data.data.top_selling_earning_products.top_selling_products;

            this.total_sales_quantity =
              response.data.data.top_selling_earning_products.total_sales_quantity;

            this.top_earning_product_data =
              response.data.data.top_selling_earning_products.top_earning_products;

            this.total_sales_margin_amount_data = this.$options.filters.formatDecimal(
              response.data.data.top_selling_earning_products
                .total_sales_margin_amount
            );

            this.todays_order_count.raw =
              response.data.data.todays_order_count.count_raw;
            this.todays_order_count.formatted =
              response.data.data.todays_order_count.count_formatted;

            this.todays_order_value.raw =
              response.data.data.todays_order_value.count_raw;
            this.todays_order_value.formatted =
              response.data.data.todays_order_value.count_formatted;

            this.order_count.raw = response.data.data.order_count.count_raw;
            this.order_count.formatted =
              response.data.data.order_count.count_formatted;

            this.order_value.raw = response.data.data.order_value.count_raw;
            this.order_value.formatted =
              response.data.data.order_value.count_formatted;

            this.order_count.raw = response.data.data.order_count.count_raw;
            this.order_count.formatted =
              response.data.data.order_count.count_formatted;

            this.revenue_value.raw = response.data.data.revenue_value.count_raw;
            this.revenue_value.formatted =
              response.data.data.revenue_value.count_formatted;

            this.customer_count.raw =
              response.data.data.customer_count.count_raw;
            this.customer_count.formatted =
              response.data.data.customer_count.count_formatted;

            this.expense.raw = response.data.data.expense.count_raw;
            this.expense.formatted = response.data.data.expense.count_formatted;

            this.net_profit_value.raw =
              response.data.data.net_profit_value.count_raw;
            this.net_profit_value.formatted = this.$options.filters.formatDecimal(
              response.data.data.net_profit_value.count_formatted
            );

            this.chart_income = response.data.data.chart_income;
            this.chart_net_profit = response.data.data.chart_net_profit;
            this.chart_expense = response.data.data.chart_expense;
            this.chart_total = this.$options.filters.formatDecimal(
              response.data.data.chart_total
            );
            this.chart_series_data = [];
            this.chart_series_data.push(
              response.data.data.chart_income,
              response.data.data.chart_expense,
              response.data.data.chart_net_profit
            );
            this.series = this.chart_series_data;

            this.chartOptions = {
              total: this.$options.filters.formatDecimal(
                response.data.data.chart_total
              ),
            };

            this.chartOptions = {
              plotOptions: {
                radialBar: {
                  dataLabels: {
                    total: {
                      formatter: () => {
                        return this.chart_total;
                      },
                    },
                  },
                },
              },
            };

            /* Purchase Order Counts [Pending & Closed] */
            this.purchase_order_count.pending_raw =
              response.data.data.purchase_order_count.pending_po_count_raw;
            this.purchase_order_count.pending_formatted =
              response.data.data.purchase_order_count.pending_po_count_formatted;
            this.purchase_order_count.closed_raw =
              response.data.data.purchase_order_count.closed_po_count_raw;
            this.purchase_order_count.closed_formatted =
              response.data.data.purchase_order_count.closed_po_count_formatted;

            this.invoices_count.paid_raw =
              response.data.data.invoices_count.paid_count_raw;
            this.invoices_count.paid_formatted =
              response.data.data.invoices_count.paid_count_formatted;
            this.invoices_count.pending_raw =
              response.data.data.invoices_count.pending_count_raw;
            this.invoices_count.pending_formatted =
              response.data.data.invoices_count.pending_count_formatted;

            //stats
            this.target.income = response.data.data.targets.income;
            this.target.income_width_value =
              response.data.data.targets.income > 0
                ? (response.data.data.revenue_value.count_raw /
                    response.data.data.targets.income) *
                  100
                : 0;
            /*this.target.income_width_value = this.$options.filters.formatDecimal(
              this.target.income_width_value
            );*/

            this.target.income_width = this.target.income_width_value + "%";

            this.target.expense = response.data.data.targets.expense;
            this.target.expense_width_value =
              response.data.data.targets.expense > 0
                ? (response.data.data.expense.count_raw /
                    response.data.data.targets.expense) *
                  100
                : 0;
            /*this.target.expense_width_value = this.$options.filters.formatDecimal(
              this.target.expense_width_value
            );*/
            this.target.expense_width = this.target.expense_width_value + "%";

            this.pos_cash = response.data.data.get_revenue.pos_cash;
            this.pos_credit = response.data.data.get_revenue.pos_credit;
            this.pos_returned = response.data.data.get_revenue.pos_returned;
            this.pos_change = response.data.data.get_revenue.pos_change;

            this.pos_total_revenue =
              this.pos_cash + this.pos_credit - this.pos_returned;

            this.target.sales = response.data.data.targets.sales;
            this.target.sales_width_value =
              response.data.data.targets.sales > 0
                ? (this.pos_total_revenue / response.data.data.targets.sales) *
                  100
                : 0;
            /*this.target.sales_width_value = this.$options.filters.formatDecimal(
              this.target.sales_width_value
            );

            alert("Pos total Revenue : " + this.pos_total_revenue);
            alert("Target Sales : " + response.data.data.targets.sales);
            alert(
              "Their div : " +
                this.pos_total_revenue / response.data.data.targets.sales
            );
            alert("Sales width value : " + this.target.sales_width_value);*/

            this.target.sales_width = this.target.sales_width_value + "%";

            this.target.net_profit = response.data.data.targets.net_profit;
            this.target.net_profit_width_value =
              response.data.data.targets.sales > 0
                ? (response.data.data.net_profit_value.count_raw /
                    response.data.data.targets.net_profit) *
                  100
                : 0;
            /*this.target.net_profit_width_value = this.$options.filters.formatDecimal(
              this.target.net_profit_width_value
            );*/
            this.target.net_profit_width = this.target.net_profit_width + "%";

            var today_sales_count_chart = this.create_chart(
              "today_sales_count_chart",
              this.todays_sales_count_chart_config
            );
            today_sales_count_chart.data.datasets = [];
            today_sales_count_chart.data.labels =
              response.data.data.todays_order_count.chart.x_axis;
            today_sales_count_chart.data.datasets.push({
              borderWidth: 2,
              borderColor: this.todays_sales_count_chart_color,
              backgroundColor: this.todays_sales_count_chart_fill_color,
              pointRadius: 1,
              data: response.data.data.todays_order_count.chart.y_axis,
            });
            today_sales_count_chart.update();

            var today_sales_value_chart = this.create_chart(
              "today_sales_value_chart",
              this.todays_sales_value_chart_config
            );
            today_sales_value_chart.data.datasets = [];
            today_sales_value_chart.data.labels =
              response.data.data.todays_order_value.chart.x_axis;
            today_sales_value_chart.data.datasets.push({
              borderWidth: 2,
              borderColor: this.todays_sales_value_chart_color,
              backgroundColor: this.todays_sales_value_chart_fill_color,
              pointRadius: 1,
              data: response.data.data.todays_order_value.chart.y_axis,
            });
            today_sales_value_chart.update();

            var income_expense_chart = this.create_chart(
              "income_expense_chart",
              this.income_expense_chart
            );
            income_expense_chart.data.datasets = [];
            income_expense_chart.data.datasets.push({
              backgroundColor: [
                this.income_chart_fill_color,
                this.expense_chart_fill_color,
              ],
              borderColor: "#FFF",
              data: [this.revenue_value.raw, this.expense.raw],
            });
            income_expense_chart.update();

            this.invoice_credit = response.data.data.get_revenue.invoice_credit;
            this.invoice_cash = response.data.data.get_revenue.invoice_cash;
            this.pos_cash = response.data.data.get_revenue.pos_cash;
            this.pos_credit = response.data.data.get_revenue.pos_credit;
            this.pos_returned = response.data.data.get_revenue.pos_returned;
            this.pos_change = response.data.data.get_revenue.pos_change;

            // Paid Pending Invoice Amount - Donut Chart
            let invoice_pending_amount = parseFloat(
              response.data.data.paid_pending_invoice.pending_amount_raw
            );
            let invoice_paid_amount = parseFloat(
              response.data.data.paid_pending_invoice.paid_amount_raw
            );
            this.paid_pending_invoice_chart_series = [
              invoice_pending_amount,
              invoice_paid_amount,
            ];

            // Paid Pending POS Amount - Donut Chart
            let pos_pending_amount = parseFloat(
              response.data.data.paid_pending_pos.pending_amount_raw
            );
            let pos_paid_amount = parseFloat(
              response.data.data.paid_pending_pos.paid_amount_raw
            );
            this.paid_pending_pos_chart_series = [
              pos_pending_amount,
              pos_paid_amount,
            ];
          } else {
            this.todays_order_count.raw = "-";
            this.todays_order_count.formatted = "-";

            this.todays_order_value.raw = "-";
            this.todays_order_value.formatted = "-";

            this.order_count.raw = "-";
            this.order_count.formatted = "-";

            this.order_value.raw = "-";
            this.order_value.formatted = "-";

            this.order_count.raw = "-";
            this.order_count.formatted = "-";

            this.revenue_value.raw = "-";
            this.revenue_value.formatted = "-";

            this.customer_count.raw = "-";
            this.customer_count.formatted = "-";

            this.expense.raw = "-";
            this.expense.formatted = "-";

            this.net_profit_value.raw = "-";
            this.net_profit_value.formatted = "-";

            this.purchase_order_count.raw = "-";
            this.purchase_order_count.formatted = "-";

            this.invoices_count.raw = "-";
            this.invoices_count.formatted = "-";
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    create_chart(canvas_id, chart_data) {
      var ctx = document.getElementById(canvas_id);
      var chart = new Chart(ctx, {
        type: chart_data.type,
        data: chart_data.data,
        options: chart_data.options,
      });
      return chart;
    },

    order_count_activity_chart() {
      var formData = this.set_form_data();

      var pos_sales_count_activity_chart = this.create_chart(
        "pos_sales_count_activity_chart",
        this.pos_sales_count_activity_chart_config
      );
      var pos_sales_value_activity_chart = this.create_chart(
        "pos_sales_value_activity_chart",
        this.pos_sales_value_activity_chart_config
      );

      axios
        .post("/api/get_order_chart_stats", formData)
        .then((response) => {
          if (response.data.status_code == 200) {
            pos_sales_count_activity_chart.data.datasets = [];
            pos_sales_count_activity_chart.data.labels =
              response.data.data.x_axis;
            pos_sales_count_activity_chart.data.datasets.push({
              label: this.$t("Order Count"),
              borderWidth: 1,
              backgroundColor: this.pos_sales_count_activity_chart_fill_color,
              borderColor: this.pos_sales_count_activity_chart_fill_color,
              data: response.data.data.y_axis.order_count,
              pointRadius: 3,
              pointHoverRadius: 6,
              pointBackgroundColor: "#FFF",
            });
            pos_sales_count_activity_chart.update();

            pos_sales_value_activity_chart.data.datasets = [];
            pos_sales_value_activity_chart.data.labels =
              response.data.data.x_axis;
            pos_sales_value_activity_chart.data.datasets.push({
              label:
                this.$t("Order Value") +
                "(" +
                this.$t(this.store_currency) +
                ")",
              borderWidth: 1,
              backgroundColor: this.pos_sales_value_activity_chart_fill_color,
              borderColor: this.pos_sales_value_activity_chart_fill_color,
              data: response.data.data.y_axis.order_value,
              pointRadius: 3,
              pointHoverRadius: 6,
              pointBackgroundColor: "#FFF",
            });
            pos_sales_value_activity_chart.update();
          } else {
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    order_count_activity_chart_timely() {
      var formData = this.set_form_data();

      var pos_sales_count_activity_chart_timely = this.create_chart(
        "pos_sales_count_activity_chart_timely",
        this.pos_sales_count_activity_chart_timely_config
      );
      var pos_sales_value_activity_chart_timely = this.create_chart(
        "pos_sales_value_activity_chart_timely",
        this.pos_sales_value_activity_chart_timely_config
      );

      axios
        .post("/api/get_order_chart_timely_stats", formData)
        .then((response) => {
          if (response.data.status_code == 200) {
            pos_sales_count_activity_chart_timely.data.datasets = [];
            pos_sales_count_activity_chart_timely.data.labels =
              response.data.data.x_axis;
            pos_sales_count_activity_chart_timely.data.datasets.push({
              label: this.$t("Order Count"),
              borderWidth: 1,
              backgroundColor: this
                .pos_sales_count_activity_chart_timely_fill_color,
              borderColor: this
                .pos_sales_count_activity_chart_timely_fill_color,
              data: response.data.data.y_axis.order_count,
              pointRadius: 3,
              pointHoverRadius: 6,
              pointBackgroundColor: "#FFF",
            });
            pos_sales_count_activity_chart_timely.update();

            pos_sales_value_activity_chart_timely.data.datasets = [];
            pos_sales_value_activity_chart_timely.data.labels =
              response.data.data.x_axis;
            pos_sales_value_activity_chart_timely.data.datasets.push({
              label:
                this.$t("Order Value") +
                "(" +
                this.$t(this.store_currency) +
                ")",
              borderWidth: 1,
              backgroundColor: this
                .pos_sales_value_activity_chart_timely_fill_color,
              borderColor: this
                .pos_sales_value_activity_chart_timely_fill_color,
              data: response.data.data.y_axis.order_value,
              pointRadius: 3,
              pointHoverRadius: 6,
              pointBackgroundColor: "#FFF",
            });
            pos_sales_value_activity_chart_timely.update();
          } else {
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    get_recent_trasactions() {
      var formData = this.set_form_data();

      axios
        .post("/api/get_recent_trasactions", formData)
        .then((response) => {
          if (response.data.status_code == 200) {
            this.transactions = response.data.data;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    class_loop(index) {
      let class_name = "";

      if (index == 0) {
        class_name = "bg-primary";
      } else if (index == 1) {
        class_name = "bg-success";
      } else if (index == 2) {
        class_name = "bg-danger";
      } else if (index == 3) {
        class_name = "bg-info";
      }

      return class_name;
    },
    selectStore(store) {
      var stores = [];
      $.each(this.store_names, function(key, value) {
        if (value.id == store.id) {
          value.active_status = !value.active_status;
        }

        stores.push(value);
      });

      this.store_names = stores;
    },
    changeStats() {
      var formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("combined", this.show_combined_stats);

      axios
        .post("/api/set_combined_stores", formData)
        .then((response) => {
          location.reload();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    
  },
};
</script>

<style scoped>
.custom-block-height {
  height: 310px;
}
</style>
