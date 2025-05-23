import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";
import "dayjs/locale/it";

// Abilita i plugin
dayjs.extend(utc);
dayjs.extend(timezone);

// Imposta la lingua e il fuso orario predefinito
dayjs.locale("it");
dayjs.tz.setDefault("Europe/Rome");

export default dayjs;
