<div class="contract-draft__el-box">
    <div class="contract-draft__title-box">
        <div class="contract-draft__title">
            Project Summary
        </div>
    </div>
    <div class="contract-draft__gray-box">
        <table>
            <tr>
                <td style="width:50%; padding-right: 30px;">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">Approx. Start Date</label>
                        <p>
                            {{$summary['start_date']}}
                        </p>
                    </div>
                </td>
                <td>
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">Approx. End Date</label>
                        <p>
                            {{$summary['end_date']}}
                        </p>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width:50%; padding-right: 30px;">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">Cost of Services, CAD</label>
                        <p>
                            {{$summary['service_cost']}}

                        </p>
                    </div>
                </td>
                <td>
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">Cost of Materials, CAD</label>
                        <p>
                            {{$summary['material_cost']}}

                        </p>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width:50%; padding-right: 30px;">
                    <div class="contract-draft__input-box">
                        <label class="contract-draft__input-label">Total project price</label>
                        <p>

                            {{ convert_price(format_price($summary['service_cost']) + format_price($summary['material_cost']))  }}

                        </p>
                    </div>
                </td>
                <td></td>
            </tr>
        </table>
    </div>
</div>
