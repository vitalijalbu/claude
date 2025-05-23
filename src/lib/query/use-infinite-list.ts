import { useInfiniteQuery } from "@tanstack/react-query";
import { getList } from "@/lib/api/strapi";

export const useInfiniteList = ({
  resource,
  filters,
  sorters,
  meta,
  pagination = {},
}) => {
  return useInfiniteQuery({
    queryKey: [resource, filters, sorters, meta],
    queryFn: async ({ pageParam = pagination?.page || 1 }) => {
      const result = await getList({
        resource,
        pagination: { ...pagination, current: pageParam }, // Ensure correct pagination
        filters,
        sorters,
        meta,
      });
      return result;
    },
    getNextPageParam: (lastPage) => {
      const { page, pageCount } = lastPage?.meta?.pagination || {};
      return page < pageCount ? page + 1 : undefined;
    },
  });
};
