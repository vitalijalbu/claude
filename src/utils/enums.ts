// Diet
export const mealTimes = [
  { value: "breakfast", label: "Colazione" },
  { value: "lunch", label: "Pranzo" },
  { value: "snack", label: "Merenda" },
  { value: "dinner", label: "Cena" },
];

export function getMealByValue(value) {
  const item = mealTimes.find((item) => item?.value === value);
  return item ? item?.label : null;
}

// Smoke Type Labels
export const smokeTypeLabels = [
  { values: [4, 5, 6], label: "Quantità" },
  { values: [7, 166], label: "Svapate" },
  { values: [3], label: "Fumate" },
];

export function getSmokeLabelByValue(value) {
  const item = smokeTypeLabels.find((item) => item.values.includes(value));
  return item ? item.label : "Quantità";
}


// Sintoms
export const sintomBlood = [
  {
    label: "No sangue",
    value: 0,
  },
  {
    label: "Strisce di sangue",
    value: 1,
  },
  {
    label: "Sangue evidente",
    value: 2,
  },
  {
    label: "Evacuazioni di solo sangue",
    value: 3,
  },
];

export function getSintomBloodValue(value) {
  const item = sintomBlood.find((item) => item?.value === value);
  return item ? item?.label : sintomBlood[0]?.label;
}

// Stress
export const frequencyOptions = [
  { value: 0, label: "Mai" },
  { value: 1, label: "Quasi mai" },
  { value: 2, label: "A volte" },
  { value: 3, label: "Abbastanza spesso" },
  { value: 4, label: "Molto spesso" },
];
export function getFrequencyValue(value) {
  const item = frequencyOptions.find((item) => item?.value === value);
  return item ? item?.label : null;
}
