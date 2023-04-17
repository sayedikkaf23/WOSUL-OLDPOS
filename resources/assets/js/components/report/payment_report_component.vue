<template>
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex flex-wrap mb-4">
                <div class="mr-auto">
                   <div class="d-flex">
                        <div>
                            <span class="text-title"> {{ $t("Payment Report") }} </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex">
                    <!-- <date-picker type="month" :lang='date.lang' :format="date.format" v-model="month" @change="month_change" input-class="form-control bg-white"></date-picker> -->
<!--                    <button class="btn btn-primary mr-3 p-0 pb-1 px-3" @click="export_report" :disabled="processing == true" style="line-height: 0;">
                        <i class="fa fa-circle-notch fa-spin" v-if="processing == true"></i>
                        Export
                    </button>-->

                    <date-range-picker
                            ref="picker"
                            :opens="calendar_opens"
                            :locale-data="customPickerData"
                            :showWeekNumbers="true"
                            :showDropdowns="true"
                            v-model="dateRange"
                            @update="updateValues"
                        >
                        <template v-slot:input="picker" style="min-width: 350px;">
                            {{ picker.startDate | date }} - {{ picker.endDate | date }}
                        </template>
                    </date-range-picker>
                </div>
            </div>

            <div class="table-responsive">
                <table id="listing-table" class="table display nowrap w-100">
                    <thead>
                        <tr>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Count</th>
                            <th>Return Amount</th>
                            <th>Return Count</th>
                            <th>Net Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    'use strict';
    
    import DateRangePicker from 'vue2-daterange-picker';
    import moment from "moment";
    import 'vue2-daterange-picker/dist/vue2-daterange-picker.css';
    import {event_bus} from '../../event_bus.js';
    
    export default {
        components: { DateRangePicker },
        data(){
            let today = new Date();
            let startDate = new Date();
            let endDate = today;
            startDate.setDate(startDate.getDate() - 30)
            return {
                processing: false,
                calendar_opens: window.settings.language == 'ar' ? 'right' : 'left',
                dateRange: {startDate, endDate},
                startDateFormatted: moment(startDate).format('YYYY-MM-DD'),
                endDateFormatted: moment(endDate).format('YYYY-MM-DD'),
                customPickerData: {
                    direction: 'ltr',
                    format: 'dd-mm-yyyy',
                    separator: ' - ',
                    applyLabel: 'Apply',
                    cancelLabel: 'Cancel',
                    weekLabel: 'W',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                    monthNames: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    firstDay: 0
                }
            }
        },
        filters: {
            date (val) {
                let date = new Date(val);
                return val ? moment(date).format('DD-MM-YYYY') : ''
            }
        },
        methods: {
            export_report(){
                this.processing = true;

                var formData = new FormData();

                formData.append("access_token", window.settings.access_token);
                formData.append("from_created_date", moment(this.dateRange.startDate).format('YYYY-MM-DD'));
                formData.append("to_created_date",moment(this.dateRange.endDate).format('YYYY-MM-DD'));

                axios
                    .post("/api/payment_report", formData)
                    .then((response) => {
                    this.processing = false;
                        if (response.data.status_code == 200) {
                            if (
                                typeof response.data.link != "undefined" &&
                                response.data.link != ""
                                ) {
                                    const link = document.createElement("a");
                                    link.href = response.data.link;
                                    document.body.appendChild(link);
                                    link.click();
                                    this.$toastr.i(response.data.msg);
                                } else {
                                    location.reload();
                                }
                        } 
                    })
                    .catch((error) => {
                    console.log(error);
                    });
            },
            updateValues(){
                event = new CustomEvent('range',{
                    detail: {
                        start_date: moment(this.dateRange.startDate).format('YYYY-MM-DD'),
                        end_date: moment(this.dateRange.endDate).format('YYYY-MM-DD')
                    }
                });
                document.dispatchEvent(event);
            }
        }
    }
</script>