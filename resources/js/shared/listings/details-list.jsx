import { dateTimeFormatter } from "@/helpers/formatter";
import { Tag } from "antd";

export function productDetailsList(data) {
    const getValue = (value, fallback = "-") => value ?? fallback;


    return [
        {
            label: "Fornitore",
            children: data?.supplier ? <Tag color="blue">{data.supplier.name}</Tag> : "-",
        },
        { label: "ID", children: getValue(data?.id) },
        { label: "Nome", children: getValue(data?.name) },
        { label: "SKU", children: getValue(data?.sku) },
        { label: "Materiale", children: getValue(data?.materials) },
        { label: "Prezzo", children: getValue(data?.price) },
        { label: "Prezzo Regolare", children: getValue(data?.regular_price) },
        { label: "Prezzo Scontato", children: getValue(data?.sale_price) },
        {
            label: "Categorie",
            children: data?.categories?.length
                ? data.categories.map((cat) => cat.name).join(", ")
                : "-",
        },
        { label: "Descrizione", children: getValue(data?.description) },
        {
            label: "Descrizione Breve",
            children: getValue(data?.short_description),
        },
        { label: "Peso", children: getValue(data?.weight) },
        {
            label: "Dimensioni",
            children: `${getValue(data?.length, 0)} x ${getValue(data?.width, 0)} x ${getValue(data?.height, 0)}`,
        },
        { label: "Quantità in Stock", children: getValue(data?.stock_quantity) },
        {
            label: "Data creazione",
            children: getValue(dateTimeFormatter(data?.created_at)),
        },
        {
            label: "Data modifica",
            children: getValue(dateTimeFormatter(data?.updated_at)),
        },
    ];
}
