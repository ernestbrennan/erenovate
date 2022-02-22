<div>
    <div style="font-family: Roboto,sans-serif,sans-serif;font-style: normal;font-weight: 500;line-height: 30px;font-size: 18px;color: #666;border-bottom: 1px solid #b2b2b2;padding: 10px 0;margin-bottom: 40px;" class="contract-draft__border-title">List of Materials</div>
    <div style="position: relative;" class="contract-materials">
        <table class="contract-materials__table">
            <tr style="margin-bottom: 15px; width: 100%;">
                <td style="width: 30%;" class="contract-materials__name">
                    <div class="table-label">Item Name</div>
                </td>
                <td style="width: 10%;padding-left: 15px;" class="contract-materials__quantity">
                    <div class="table-label">Quantity</div>
                </td>
                <td style="width: 25%;padding-left: 15px;" class="contract-materials__link">
                    <div class="table-label">Product link (optional)</div>
                </td>
                @if($material_supply_side === \App\Models\MilestoneContent::MATERIAL_SUPPLY_SIDE_CONTRACTOR)

                    <td style="width: 15%;padding-left: 15px;" class="contract-materials__price-per-unit">
                        <div class="table-label">Price per Unit</div>
                    </td>
                    <td style="width: 20%;padding-left: 15px;" class="contract-materials__total">
                        <div class="table-label">Total Per Item</div>
                    </td>

                @endif

            </tr>

            @foreach($materials as $material)

                <tr style="margin-bottom: 15px; width: 100%;">
                    <td style="width: 30%;" class="contract-materials__name">
                        <p>
                            {{$material['title']}}
                        </p>
                    </td>
                    <td style="width: 10%;padding-left: 15px;" class="contract-materials__quantity">
                        <p>
                            {{$material['quantity']}}
                        </p>
                    </td>
                    <td style="width: 25%;padding-left: 15px;" class="contract-materials__link">
                        <p>
                            {{$material['link']}}
                        </p>
                    </td>

                    @if($material_supply_side === \App\Models\MilestoneContent::MATERIAL_SUPPLY_SIDE_CONTRACTOR)

                        <td style="width: 15%;padding-left: 15px;" class="contract-materials__price-per-unit">
                            <p>
                                {{$material['price']}}
                            </p>
                        </td>
                        <td style="width: 20%;padding-left: 15px;">
                            <p>
                                {{ convert_price(format_price($material['price']) *  $material['quantity'])  }}
                            </p>
                        </td>

                    @endif

                </tr>

            @endforeach
        </table>
    </div>
</div>
