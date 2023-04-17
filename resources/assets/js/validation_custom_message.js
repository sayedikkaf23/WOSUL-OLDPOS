"use strict";

import en from "../../lang/en.json";
import ar from "../../lang/ar.json";
import de from "../../lang/de.json";
import es from "../../lang/es.json";
import fr from "../../lang/fr.json";
import it from "../../lang/it.json";
import ms from "../../lang/ms.json";
import no from "../../lang/no.json";
import sv from "../../lang/sv.json";
import th from "../../lang/th.json";
import zh from "../../lang/zh.json";

export const dictionary = {
  en: {
    attributes: en.attributes,
    messages: {
      required: (field) => field + ` is required`,
      email: (field) => `Provide a valid ` + field,
      min: (field, params) =>
        field + ` must be at least ${params[0]} characters`,
      max: (field, params) =>
        field + ` must not be more than ${params[0]} characters`,
      min_value: (field, params) => field + ` must be more than ${params[0]}`,
      between: (field, params) =>
        field + ` must be between ${params[0]} and ${params[1]}`,
      confirmed: (field) => `Passwords doesn't match`,
    },
  },
  ar: {
    attributes: ar.attributes,
    messages: {
      required: (field) => {
        if (field == "فاتورة الى") {
          return `الرجاء الاختيار`;
        } else {
          return field + ` مطلوب`;
        }
      },
      email: (field) => `تقديم صالح ` + field,
      min: (field, params) => field + ` يجب ألا يقل عن ${params[0]} حرفًا`,
      max: (field, params) => field + ` يجب ألا يزيد عن ${params[0]} حرفًا`,
      min_value: (field, params) =>
        field + ` يجب ألا يزيد عن ${params[0]} حرفًا`,
      between: (field, params) =>
        field + ` يجب أن تكون بين ${params[0]} و ${params[1]}`,
      confirmed: (field) => `كلمات المرور غير متطابقة`,
      unique: (field) => field + `تم استخدام ${params[0]} بالفعل`,
      alpha_dash: (field) => field + " القيمة غير صحيحة",
      ext: (field) => field + " يجب أن يكون الحقل ملفًا صالحًا",
      decimal: (field) =>
        "يجب أن يكون حقل النسبة الضريبية رقميًا وقد يحتوي على علامات عشرية",
    },
  },
  de: {
    attributes: de.attributes,
    messages: {
      required: (field) => field + ` is required`,
      email: (field) => `Provide a valid ` + field,
      min: (field, params) =>
        field + ` must be at least ${params[0]} characters`,
      max: (field, params) =>
        field + ` must not be more than ${params[0]} characters`,
      min_value: (field, params) => field + ` must be more than ${params[0]}`,
      between: (field, params) =>
        field + ` must be between ${params[0]} and ${params[1]}`,
      confirmed: (field) => `Passwords doesn't match`,
    },
  },
  es: {
    attributes: es.attributes,
    messages: {
      required: (field) => field + ` is required`,
      email: (field) => `Provide a valid ` + field,
      min: (field, params) =>
        field + ` must be at least ${params[0]} characters`,
      max: (field, params) =>
        field + ` must not be more than ${params[0]} characters`,
      min_value: (field, params) => field + ` must be more than ${params[0]}`,
      between: (field, params) =>
        field + ` must be between ${params[0]} and ${params[1]}`,
      confirmed: (field) => `Passwords doesn't match`,
    },
  },
  fr: {
    attributes: fr.attributes,
    messages: {
      required: (field) => field + ` is required`,
      email: (field) => `Provide a valid ` + field,
      min: (field, params) =>
        field + ` must be at least ${params[0]} characters`,
      max: (field, params) =>
        field + ` must not be more than ${params[0]} characters`,
      min_value: (field, params) => field + ` must be more than ${params[0]}`,
      between: (field, params) =>
        field + ` must be between ${params[0]} and ${params[1]}`,
      confirmed: (field) => `Passwords doesn't match`,
    },
  },
  it: {
    attributes: it.attributes,
    messages: {
      required: (field) => field + ` is required`,
      email: (field) => `Provide a valid ` + field,
      min: (field, params) =>
        field + ` must be at least ${params[0]} characters`,
      max: (field, params) =>
        field + ` must not be more than ${params[0]} characters`,
      min_value: (field, params) => field + ` must be more than ${params[0]}`,
      between: (field, params) =>
        field + ` must be between ${params[0]} and ${params[1]}`,
      confirmed: (field) => `Passwords doesn't match`,
    },
  },
  ms: {
    attributes: ms.attributes,
    messages: {
      required: (field) => field + ` is required`,
      email: (field) => `Provide a valid ` + field,
      min: (field, params) =>
        field + ` must be at least ${params[0]} characters`,
      max: (field, params) =>
        field + ` must not be more than ${params[0]} characters`,
      min_value: (field, params) => field + ` must be more than ${params[0]}`,
      between: (field, params) =>
        field + ` must be between ${params[0]} and ${params[1]}`,
      confirmed: (field) => `Passwords doesn't match`,
    },
  },
  no: {
    attributes: no.attributes,
    messages: {
      required: (field) => field + ` is required`,
      email: (field) => `Provide a valid ` + field,
      min: (field, params) =>
        field + ` must be at least ${params[0]} characters`,
      max: (field, params) =>
        field + ` must not be more than ${params[0]} characters`,
      min_value: (field, params) => field + ` must be more than ${params[0]}`,
      between: (field, params) =>
        field + ` must be between ${params[0]} and ${params[1]}`,
      confirmed: (field) => `Passwords doesn't match`,
    },
  },
  sv: {
    attributes: sv.attributes,
    messages: {
      required: (field) => field + ` is required`,
      email: (field) => `Provide a valid ` + field,
      min: (field, params) =>
        field + ` must be at least ${params[0]} characters`,
      max: (field, params) =>
        field + ` must not be more than ${params[0]} characters`,
      min_value: (field, params) => field + ` must be more than ${params[0]}`,
      between: (field, params) =>
        field + ` must be between ${params[0]} and ${params[1]}`,
      confirmed: (field) => `Passwords doesn't match`,
    },
  },
  th: {
    attributes: th.attributes,
    messages: {
      required: (field) => field + ` is required`,
      email: (field) => `Provide a valid ` + field,
      min: (field, params) =>
        field + ` must be at least ${params[0]} characters`,
      max: (field, params) =>
        field + ` must not be more than ${params[0]} characters`,
      min_value: (field, params) => field + ` must be more than ${params[0]}`,
      between: (field, params) =>
        field + ` must be between ${params[0]} and ${params[1]}`,
      confirmed: (field) => `Passwords doesn't match`,
    },
  },
  zh: {
    attributes: zh.attributes,
    messages: {
      required: (field) => field + ` is required`,
      email: (field) => `Provide a valid ` + field,
      min: (field, params) =>
        field + ` must be at least ${params[0]} characters`,
      max: (field, params) =>
        field + ` must not be more than ${params[0]} characters`,
      min_value: (field, params) => field + ` must be more than ${params[0]}`,
      between: (field, params) =>
        field + ` must be between ${params[0]} and ${params[1]}`,
      confirmed: (field) => `Passwords doesn't match`,
    },
  },
};
