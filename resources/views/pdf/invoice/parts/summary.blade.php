<div class="contract-draft__el-box">
    <div class="contract-draft__title-box">
        <div class="contract-draft__title">
            Payment request summary
        </div>
    </div>
    <div class="contract-draft__slide-main-box contract-draft__gray-box_invoice contract-draft__gray-box contract-draft__gray-box_full">
        <table style="width: 100%;">
            <tr style="width: 100%;" class="row">
                <td style="position: relative;width: 50%;padding-right: 15px;" class="col-xl-6">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">Works List Total</label>
                        <p>
                            {{$invoice_summary['works_cost']}}
                        </p>
                    </div>
                </td>
                <td style="position: relative;width: 50%;"
                    class="col-xl-6">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">Materials Total</label>
                        <p>
                            {{$invoice_summary['materials_cost']}}

                        </p>
                    </div>
                </td>
            </tr>
            <tr style="width: 100%;" class="row">
                <td style="position: relative;width: 50%;padding-right: 15px;"
                    class="col-xl-6">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">Taxes Included</label>
                        <p>
                            {{$invoice['taxes']}}
                        </p>
                    </div>
                </td>
                <td style="position: relative;width: 50%;"
                    class="col-xl-6">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">Total</label>
                        <p>
                            {{$invoice['total_price']}}
                        </p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

