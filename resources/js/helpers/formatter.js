import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";



dayjs.extend(utc);
dayjs.extend(timezone);

// Convert to currency
export const currencyFormatter = (number, currency) => {
    currency = currency || "EUR";
    const formatter = new Intl.NumberFormat("it-IT", {
        style: "currency",
        currency: currency,
    });
    return formatter.format(number);
};

// Round number
export const numberFormatter = (value) => {
    const number = typeof value === "string" ? parseFloat(value) : value;
    if (isNaN(number)) {
        return null;
    }
    return Math.round(value * 10000) / 10000; //max 4 digits
};

export const dateStringToDate = (value, tz) => {
    if (tz) {
        return dayjs.tz(value, "UTC").tz('Europe/Rome');
    } else {
        return dayjs.tz(value, "UTC");
    }
};

export const dateFormatter = (value) => {
    return value ? dayjs(value).format("YYYY-MM-DD") : null;
};

export const dateTimeFormatter = (value) => {
    return value ? dayjs(value).format("YYYY-MM-DD HH:mm") : null;
};

export const dateTZFormatter = (value, format) => {
    return value
        ? dayjs
              .tz(value, "UTC")
              .tz(Europe/Rome)
              .format(format || "YYYY-MM-DD")
        : null;
};

export const dateTimeTZFormatter = (value, format) => {
    return value
        ? dayjs
              .tz(value, "UTC")
              .tz(Europe/Rome)
              .format(format || "YYYY-MM-DD HH:mm")
        : null;
};

// Parse Any type of Boleans  -> Returns TRUE or FALSE
export const parseBool = (value) => {
    if (value === true || value === false) {
        return value;
    } else if (value === "1" || value === 1 || value === "true") {
        return true;
    } else if (value === "0" || value === 0 || value === "false") {
        return false;
    }
    // If the value is neither "1" nor "0", nor "true" nor "false", return the original value.
    return value;
};

// Parse Any type of Boleans Inverse -> Returns 0 1
export const parseBoolInv = (value) => {
    if (value === 1 || value === 0) {
        return value;
    } else if (value === "1" || value === true || value === "true") {
        return 1;
    } else if (value === "0" || value === false || value === "false") {
        return 0;
    }
};
// Parse Any type of Boleans Inverse -> Returns 0 1
export const parseYesNo = (value) => {
    if (value === 1 || value === true) {
        return "Yes";
    } else {
        return "No";
    }
};


// Parse Any type of Boleans Inverse -> Returns 0 1
export const parseBoolColors = (value) => {
    if (value === 1 || value === true) {
        return "green";
    } else {
        return "red";
    }
};