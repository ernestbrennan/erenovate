<div>
    <div>
        <div class="contract-draft__title-box">
            <table>
                <tr>
                    <td style="width: 65%;">
                        <span class="contract-draft__title">
                            {{ $milestone['milestone_content']['title'] }}
                        </span>
                    </td>
                    <td style="width: 35%; text-align:right">
                        <span class="contract-draft__title">
                            Milestone {{$milestone['sequence']}}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-right: 15px;margin-left: 15px;" class="contract-draft__gray-box">
            <div>
                <label class="contract-draft__input-label">Milestone Title</label>
                <p type="text" class="contract-draft__input">

                    {{$milestone['milestone_content']['title']}}

                </p>
            </div>
            <div>
                <label class="contract-draft__input-label">Milestone Description</label>
                <p type="text" class="contract-draft__input">

                    {{$milestone['milestone_content']['description']}}

                </p>
            </div>
            <table>
                <tr style="width: 100%;">
                    <td style="width: 50%; padding-right: 30px;">
                        <div>
                            <label class="contract-draft__input-label">Approx. Start Date</label>
                            <p type="text" class="contract-draft__input">

                                {{$milestone['milestone_content']['start_date']}}

                            </p>
                        </div>
                    </td>
                    <td style="width: 50%;">
                        <div>
                            <label class="contract-draft__input-label">Approx. end date</label>
                            <p type="text" class="contract-draft__input">

                                {{$milestone['milestone_content']['end_date']}}

                            </p>
                        </div>
                    </td>
                </tr>

                <tr style="width: 100%;">
                    <td style="width: 50%; padding-right: 30px;">
                        <div>
                            <label class="contract-draft__input-label">Milestone Price, CAD</label>
                            <p type="text" class="contract-draft__input">

                                {{$milestone['milestone_content']['price']}}

                            </p>
                        </div>
                    </td>
                </tr>
            </table>

            @if(count($milestone['milestone_content']['materials']))
                <div>
                    <div class="contract-draft__border-title">
                        Materials
                    </div>
                    <div>
                        <p class="common-p">
                            We strongly recommend you use this platform to keep track of supplies and materials used
                            for
                            this project. This will provide a clear overview of materials.
                            (You are also allowed to upload receipts of materials)
                        </p>
                        <table>


                            <tr style="margin-bottom: 15px; width: 100%;">
                                <td style="width: 30%;">
                                    <p class="material-title">Item Name</p>
                                </td>
                                <td style="width: 10%;padding-left: 15px;">
                                    <p class="material-title">Quantity</p>
                                </td>
                                <td style="width: 25%;padding-left: 15px;">
                                    <p class="material-title">Product link (optional)</p>
                                </td>

                                @if($milestone['milestone_content']['material_supply_side'] === \App\Models\MilestoneContent::MATERIAL_SUPPLY_SIDE_CONTRACTOR)

                                    <td style="width: 15%;padding-left: 15px;">
                                        <p class="material-title">Price per Unit</p>
                                    </td>
                                    <td style="width: 20%;padding-left: 15px;">
                                        <p class="material-title">Total Per Item</p>
                                    </td>

                                @endif


                            </tr>

                            @foreach($milestone['milestone_content']['materials'] as $material)

                                <tr style="margin-bottom: 15px; width: 100%;">
                                    <td style="width: 30%;">
                                        <p class="material-elem">

                                            {{$material['title']}}

                                        </p>
                                    </td>
                                    <td style="width: 10%;padding-left: 15px;">
                                        <p class="material-elem">

                                            {{$material['quantity']}}

                                        </p>
                                    </td>
                                    <td style="width: 25%;padding-left: 15px;">
                                        <p class="material-elem">

                                            {{$material['link']}}

                                        </p>
                                    </td>

                                    @if($milestone['milestone_content']['material_supply_side'] === \App\Models\MilestoneContent::MATERIAL_SUPPLY_SIDE_CONTRACTOR)


                                        <td style="width: 15%;padding-left: 15px;">
                                            <p class="material-elem">

                                                {{$material['price']}}

                                            </p>
                                        </td>
                                        <td style="width: 20%;padding-left: 15px;">
                                            <p class="material-elem">

                                                {{ convert_price(format_price($material['price']) *  $material['quantity'])  }}


                                            </p>
                                        </td>

                                    @endif
                                </tr>

                            @endforeach

                        </table>
                    </div>
                </div>

            @endif
        </div>
    </div>
</div>