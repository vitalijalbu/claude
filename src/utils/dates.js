import dayjs from "dayjs";
import "dayjs/locale/it";
import relativeTime from "dayjs/plugin/relativeTime";

dayjs.extend(relativeTime);
dayjs.locale("it");


// Default date format
const formatTime = (time) => {
  if (!time) return "";
  return dayjs(time, "HH:mm:ss").format("HH:mm");
};

// Default date format
const formatDate = (date) => {
  if (!date) return "";
  return dayjs(date).format("DD-MM-YYYY");
};

// Default date format
const formatDateTime = (date) => {
  if (!date) return "";
  return dayjs(date).format("DD-MM-YYYY HH:mm");
};


export {
  formatDate,
  formatDateTime,
  formatTime,
};
