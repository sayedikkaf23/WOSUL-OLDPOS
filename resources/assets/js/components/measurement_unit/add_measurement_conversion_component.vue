<template>
  <div class="row">
    <div class="col-md-12">
      <form @submit.prevent="submit_form" class="mb-3">
        <div class="d-flex flex-wrap mb-4">
          <div class="mr-auto">
            <span class="text-title" v-if="measurement_slack == ''">{{
              $t("Measurement Conversion")
            }}</span>
            <span class="text-title" v-else>{{
              $t("Measurement Conversion")
            }}</span>
          </div>
        </div>

        <p v-html="server_errors" v-bind:class="[error_class]"></p>

        <!--    
                <div class="form-row mb-2 col-md-6">
                    <table class="table table-condensed table-bordered">
                        <tr v-if="conversions.length" v-for="(conversion,index) in conversions">
                            <td width="20%">
                                <input type="Number" v-model="conversion.from_measurement_unit" v-validate="'required|max:250'" class="form-control form-control-custom" autocomplete="off" readonly>
                            </td>
                            <td width="20%">
                                <input type="hidden" v-model="conversion.from_measurement_label" v-validate="'required|max:250'" class="form-control form-control-custom" autocomplete="off" readonly>
                                {{ conversion.from_measurement_label }}
                            </td>
                            <td width="20%" class="text-center"><strong>=</strong></td>
                            <td width="20%"><input type="number" v-bind:name="'index'" v-model="conversion.to_measurement_value" v-validate="'required|max:250'" class="form-control form-control-custom" autocomplete="off"></td>
                            <td width="20%">
                                <input type="hidden" v-model="conversion.to_measurement_label" v-validate="'required|max:250'" class="form-control form-control-custom" autocomplete="off" readonly>
                                {{ conversion.to_measurement_label }}
                            </td>
                        </tr>
                    </table>

                </div>
 -->
        <div class="form-row mb-2 col-md-6">
          <table class="table table-condensed table-bordered">
            <!--  <tr>
                            <td>Unit</td>
                            <td>From Measurement</td>
                            <td>=</td>
                            <td>Unit</td>
                            <td>To Measurement</td>
                        </tr> -->
            <tr
              v-if="conversions.length"
              v-for="(conversion, index) in conversions"
            >
              <td width="20%">
                <input
                  type="text"
                  v-model="conversion.from_measurement_unit"
                  v-validate="'required|max:250'"
                  class="form-control form-control-custom"
                  autocomplete="off"
                  readonly
                />
              </td>
              <td width="20%">
                <input
                  type="hidden"
                  v-model="conversion.from_measurement_label"
                  v-validate="'required|max:250'"
                  class="form-control form-control-custom"
                  autocomplete="off"
                  readonly
                />
                {{ conversion.from_measurement_label }}
              </td>
              <td width="20%" class="text-center"><strong>=</strong></td>
              <td width="20%">
                <input
                  type="number"
                  v-bind:name="'index'"
                  v-model="conversion.to_measurement_value"
                  v-validate="'required|max:250'"
                  step="0.00001"
                  class="form-control form-control-custom"
                  autocomplete="off"
                />
              </td>
              <td width="20%">
                <input
                  type="hidden"
                  v-model="conversion.to_measurement_label"
                  v-validate="'required|max:250'"
                  class="form-control form-control-custom"
                  autocomplete="off"
                  readonly
                />
                {{ conversion.to_measurement_label }}
              </td>
            </tr>
          </table>
        </div>

        <div class="flex-wrap mb-4">
          <div class="text-right">
            <button
              type="submit"
              class="btn btn-primary"
              v-bind:disabled="processing == true"
            >
              <i
                class="fa fa-circle-notch fa-spin"
                v-if="processing == true"
              ></i>
              {{ $t("Save") }}
            </button>
          </div>
        </div>
      </form>
    </div>

    <modalcomponent v-if="show_modal" v-on:close="show_modal = false">
      <template v-slot:modal-header>
        {{ $t("Confirm") }}
      </template>
      <template v-slot:modal-body>
        <p v-if="status == 0">
          {{
            $t(
              "If measurement unit is inactive all the products using this measurement unit will get affected."
            )
          }}
        </p>
        {{ $t("Are you sure you want to proceed?") }}
      </template>
      <template v-slot:modal-footer>
        <button type="button" class="btn btn-light" @click="$emit('close')">
          {{ $t("Cancel") }}
        </button>
        <button
          type="button"
          class="btn btn-primary"
          @click="$emit('submit')"
          v-bind:disabled="processing == true"
        >
          <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
          {{ $t("Continue") }}
        </button>
      </template>
    </modalcomponent>
  </div>
</template>

<script>
"use strict";

export default {
  data() {
    return {
      server_errors: "",
      error_class: "",
      processing: false,
      modal: false,
      show_modal: false,
      api_link:
        this.measurement_data == null
          ? "/api/add_measurement_conversion"
          : "/api/update_measurement_conversion/" + this.measurement_data.slack,
      success: this.$t("SUCCESS"),
      measurement_slack: this.measurement_slack_data,
      conversions: this.measurement_conversion_data,
      reload_on_submit: {
        type: String,
        default: true,
      },
    };
  },
  props: {
    statuses: Array,
    measurement_slack_data: String,
    measurement_conversion_data: [Array, Object],
    reload_on_submit: {
      type: String,
      default: true,
    },
  },
  mounted() {
    console.log("Add measurement unit page loaded");
    console.log(this.conversions);
    // return false;
  },
  methods: {
    submit_form() {
      this.$off("submit");
      this.$off("close");

      this.$validator.validateAll().then((result) => {
        if (result) {
          this.show_modal = true;
          this.$on("submit", function () {
            this.processing = true;
            var formData = new FormData();

            formData.append("access_token", window.settings.access_token);
            formData.append(
              "slack",
              this.measurement_slack == null ? "" : this.measurement_slack
            );

            // remove unsetted conversions
            var conversions = this.conversions.filter(function (value) {
              return value.to_measurement_value > 0;
            });
            formData.append("conversions", JSON.stringify(conversions));

            axios
              .post(this.api_link, formData)
              .then((response) => {
                if (response.data.status_code == 200) {
                  console.log(response.data);
                  this.show_response_message(response.data.msg, this.success);
                  // this.$emit('refreshMeasurements', response.data.data);
                  this.show_modal = false;
                  this.processing = false;

                  // setTimeout(function(){
                  //     location.reload();
                  // }, 1000);
                } else {
                  this.show_modal = false;
                  this.processing = false;
                  try {
                    var error_json = JSON.parse(response.data.msg);
                    this.loop_api_errors(error_json);
                  } catch (err) {
                    this.server_errors = response.data.msg;
                  }
                  this.error_class = "error";
                }
              })
              .catch((error) => {
                console.log(error);
              });
          });

          this.$on("close", function () {
            this.show_modal = false;
          });
        }
      });
    },
    showConversions(category_id) {
      let formData = new FormData();
      formData.append("access_token", window.settings.access_token);
      formData.append("category_id", category_id);

      axios.post("/api/get_conversion_units", formData).then((rs) => {
        this.conversions = [];
        rs.data.data.forEach((item) => {
          var conversion_template = {
            from_measurement_unit: 1,
            from_measurement_label: this.label,
            to_measurement_id: item.id,
            to_measurement_label: item.label,
            to_measurement_value: 0,
          };
          this.addItemToConversion(conversion_template);
        });
      });
    },
    addItemToConversion(item) {
      this.conversions.push(item);
      console.log(this.conversions);
    },
  },
};
</script>
