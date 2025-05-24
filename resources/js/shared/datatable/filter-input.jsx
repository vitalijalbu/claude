import React from 'react';
import { Input, InputNumber, DatePicker } from 'antd';

const FilterInput = ({ type, value, placeholder, onChange }) => {
    switch (type) {
        case 'text':
            return (
                <Input
                    placeholder={placeholder || 'Cerca...'}
                    value={value || null}
                    allowClear
                    onChange={(e) => onChange(e.target.value)}
                />
            );
        case 'number':
            return (
                <InputNumber
                    placeholder={placeholder || 'Inserisci un numero'}
                    value={value || null}
                    onChange={onChange}
                />
            );
        case 'date':
            return (
                <DatePicker
                    placeholder={placeholder || 'Seleziona una data'}
                    value={value || null}
                    onChange={(date, dateString) => onChange(dateString)}
                />
            );
        case 'range':
            return (
                <DatePicker.RangePicker
                    placeholder={placeholder || ['Data inizio', 'Data fine']}
                    value={value || null}
                    onChange={(dates, dateStrings) => onChange(dateStrings)}
                />
            );
        default:
            return (
                <Input
                    placeholder={placeholder || 'Cerca...'}
                    value={value || null}
                    allowClear
                    onChange={(e) => onChange(e.target.value)}
                />
            );
    }
};

export default FilterInput;
