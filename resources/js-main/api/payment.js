import request from '@/utils/request';

export function paySummaryFee(order_id, contract_id) {
  return request({
    url: 'payment.summary-fee',
    method: 'post',
    data: {
      order_id: order_id,
      contract_id: contract_id
    }
  });
}

export function payContractFee(order_id, contract_draft_id) {
  return request({
    url: 'payment.contract-fee',
    method: 'post',
    data: {
      order_id: order_id,
      contract_draft_id: contract_draft_id
    }
  });
}
