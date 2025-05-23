import { atom } from "jotai";
import { getSession } from "@/lib/session";
import dayjs from "dayjs";
dayjs.locale('it')

export const dateFilterAtom = atom({
  start: dayjs().startOf("day").toISOString(),
  end: dayjs().endOf("day").toISOString(),
});

export const filtersAtom = atom((get) => {
  const { user } = getSession();
  const dateFilter = get(dateFilterAtom);

  return [
    {
      field: "user",
      operator: "eq",
      value: user?.id ?? null,
    },
    {
      field: "createdAt",
      operator: "gte",
      value:
        dateFilter?.start ??
        dayjs().startOf("day").toISOString(),
    },
    {
      field: "createdAt",
      operator: "lte",
      value:
        dateFilter?.end ??
        dayjs().endOf("day").toISOString(),
    },
  ];
});

export const sortersAtom = atom((get) => {
  return [
    {
      field: "createdAt",
      order: "desc",
    },
  ];
});
