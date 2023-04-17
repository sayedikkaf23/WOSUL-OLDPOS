var lang = window.settings.language;
class CommonDatatableLanguage {

    constructor(key = null,value = null){
        this.key = key;
        this.value = value;
    }

    commonDatatable() {
        let datatableObj = {
            sSearch: lang == 'ar' ? "يبحث" : "Search",
            sLengthMenu: lang == 'ar' ? "تبين _MENU_ إدخالات" : "Show _MENU_ entries",
            sInfo: lang == 'ar' ? "إظهار _START_ إلى _END_ من أصل _TOTAL_ مُدخل" : "Showing _START_ to _END_ of _TOTAL_ entries",
            sZeroRecords: lang == 'ar' ? "لم يتم العثور على سجلات مطابقة" : "No matching records found",
            sProcessing: lang == 'ar' ? "معالجة..." : "Processing...",
            sLoadingRecords: lang == 'ar' ? "جار التحميل..." : "Loading...",
            sEmptyTable: lang == 'ar' ? "لا توجد بيانات متوفرة في الجدول" : "No data available in table",
            sFirst: lang == 'ar' ? "أولا" : "First",
            sLast: lang == 'ar' ? "آخر" : "Last",
            sPrevious: lang == 'ar' ? "سابق" : "Previous",
            sNext: lang == 'ar' ? "التالي" : "Next",
            sInfoEmpty: lang == 'ar' ? "إظهار 0 إلى 0 من 0 مدخلات" : "Showing 0 to 0 of 0 entries",
            sInfoFiltered: lang == 'ar' ? "(تمت تصفيته من إجمالي إدخالات _MAX_)" : "(filtered from _MAX_ total entries)"
        };

        if(this.key != null && this.value != null){
            datatableObj[this.key] = this.value;
        }

        return datatableObj;
    }

}
