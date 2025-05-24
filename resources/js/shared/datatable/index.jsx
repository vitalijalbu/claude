import React, { useState, useCallback } from "react";
import { Table, Input, Card } from "antd";
import { router } from "@inertiajs/react";
import FilterInput from "./filter-input";

const Datatable = ({ columns, data = {}, initialFilters = {}, ...props }) => {
    const [filters, setFilters] = useState(initialFilters?.filter || {});
    const [debounceTimeout, setDebounceTimeout] = useState(null);


    const { current_page, per_page = 25, total, data: rows } = data;

    const handleInputChange = (value, key) => {
        if (debounceTimeout) clearTimeout(debounceTimeout);

        const newFilters = { ...filters, [key]: value };
        setFilters(newFilters);

        const timeout = setTimeout(() => {
            router.get(window.location.href, {
                filter: newFilters,
                page: 1,
                per_page: per_page,
            });
        }, 500);

        setDebounceTimeout(timeout);
    };

    const handleTableChange = useCallback(
        (pagination) => {
            const { current, pageSize } = pagination;
            router.get(window.location.href, {
                filter: filters,
                page: current,
                per_page: pageSize,
            });
        },
        [filters] // Only recreate the function when filters change
    );

    const modifiedColumns = columns?.map((col) => ({
        ...col,
        title: col.filterable ? (
            <>
                {col.title}
                <FilterInput
                    type={col.type}
                    value={filters[col.key] || null}
                    placeholder={`Cerca...`}
                    onChange={(value) => handleInputChange(value, col.key)}
                />
            </>
        ) : (
            col.title
        ),
    }));

    return (
        <Card>
            <FilterInput
                type="text"
                value={filters.search || null}
                placeholder="Cerca..."
                onChange={(value) => handleInputChange(value, "search")}/>
        <Table
            columns={modifiedColumns}
            dataSource={rows || []}
            rowKey="id"
            pagination={{
                hideOnSinglePage: true,
                position: ["bottomCenter"],
                current: current_page,
                total: total,
                pageSize: per_page,
            }}
            onChange={handleTableChange}
            {...props}
        />
        </Card>
    );
};

export default Datatable;
