
// Common Functions

"use strict";

export const commonFunctions = { // API Group 1
  install: function (_Vue) {
    if(!_Vue.prototype.$myFunctions) {
      _Vue.prototype.$myFunctions = {}
    }

    // convert date from "dd-mm-yyyy" "yyyy-mm-dd"
    _Vue.prototype.$myFunctions.invertDateFormat = function(date) {
    	return date.split("-").reverse().join("-")
    } 

  }
}