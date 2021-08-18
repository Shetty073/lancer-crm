// brokerage amount = % brokerage of booking amount
document.querySelector('#brokerage_percent').addEventListener('keyup', (e) => {
    let agreement_value = document.querySelector('#agreement_value').value;
    let brokerage_amount = document.querySelector('#brokerage_amount');
    brokerage_amount.value = e.currentTarget.value / 100 * agreement_value;
});

document.querySelector('#agreement_value').addEventListener('keyup', (e) => {
    let brokerage_percent = document.querySelector('#brokerage_percent').value;
    let brokerage_amount = document.querySelector('#brokerage_amount');
    brokerage_amount.value = brokerage_percent / 100 * e.currentTarget.value;
});
